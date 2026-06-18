<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

/**
 * Quorum de 10 fichiers chiffres (sodium secretbox) stockant la date
 * d'expiration de licence (current/next), avec double vote majoritaire :
 *
 *  1) mtime : tous les fichiers doivent avoir ete ecrits le meme jour.
 *     Un fichier dont le mtime differe de la majorite est exclu du
 *     vote sur le contenu, meme si son contenu se dechiffre.
 *
 *  2) contenu (current_expiry/next_expiry), parmi les fichiers retenus
 *     a l'etape 1.
 */
class LicenseDateQuorumService
{
    private string $key;
    private array $paths;
    private int $threshold;

    public function __construct()
    {
        $secret = (string) config('license_quorum.secret');

        $this->key = sodium_crypto_generichash(
            $secret,
            '',
            SODIUM_CRYPTO_SECRETBOX_KEYBYTES
        );

        $this->paths = config('license_quorum.files', []);
        $this->threshold = (int) config('license_quorum.quorum_threshold', 6);
    }

    public function status(): array
    {
        $present = 0;
        $decrypted = 0;
        $records = [];

        foreach ($this->paths as $relativePath) {
            $fullPath = storage_path($relativePath);

            if (!is_file($fullPath)) {
                continue;
            }
            $present++;

            $mtime = @filemtime($fullPath);
            $mtimeDay = $mtime !== false
                ? Carbon::createFromTimestamp($mtime)->toDateString()
                : null;

            $data = $this->readAndDecrypt($fullPath);
            if ($data !== null) {
                $decrypted++;
            }

            $records[] = [
                'mtime_day' => $mtimeDay,
                'data'      => $data,
            ];
        }

        $mtimeGroups = [];
        foreach ($records as $r) {
            if ($r['mtime_day'] === null) {
                continue;
            }
            $mtimeGroups[$r['mtime_day']] = ($mtimeGroups[$r['mtime_day']] ?? 0) + 1;
        }

        $mtimeMajorityDay = null;
        $mtimeAgreement = 0;
        if (!empty($mtimeGroups)) {
            arsort($mtimeGroups);
            $mtimeMajorityDay = array_key_first($mtimeGroups);
            $mtimeAgreement = $mtimeGroups[$mtimeMajorityDay];
        }

        $mtimeOutliers = $present - $mtimeAgreement;

        $groups = [];
        foreach ($records as $r) {
            if ($r['data'] === null) {
                continue;
            }
            if ($mtimeMajorityDay !== null && $r['mtime_day'] !== $mtimeMajorityDay) {
                continue;
            }

            $pairKey = $r['data']['c'] . '|' . $r['data']['n'];
            $groups[$pairKey] = ($groups[$pairKey] ?? 0) + 1;
        }

        $topPair = null;
        $topCount = 0;
        if (!empty($groups)) {
            arsort($groups);
            $topPair = array_key_first($groups);
            $topCount = $groups[$topPair];
        }

        $consensus = $topCount >= $this->threshold;

        $currentStr = $nextStr = null;
        if ($consensus && $topPair !== null) {
            [$currentStr, $nextStr] = explode('|', $topPair, 2);
        }

        $tamperSuspected = $decrypted < count($this->paths)
            || count($groups) > 1
            || $mtimeOutliers > 0;

        return [
            'total_files'        => count($this->paths),
            'files_present'      => $present,
            'files_decrypted'    => $decrypted,
            'mtime_majority_day' => $mtimeMajorityDay,
            'mtime_agreement'    => $mtimeAgreement,
            'mtime_outliers'     => $mtimeOutliers,
            'agreement_count'    => $topCount,
            'threshold'          => $this->threshold,
            'distinct_pairs'     => count($groups),
            'consensus'          => $consensus,
            'tamper_suspected'   => $tamperSuspected,
            'current_expiry'     => $currentStr ? Carbon::parse($currentStr) : null,
            'next_expiry'        => $nextStr ? Carbon::parse($nextStr) : null,
        ];
    }

    public function activeExpiry(): ?Carbon
    {
        return $this->status()['current_expiry'];
    }

    public function nextExpiry(): ?Carbon
    {
        return $this->status()['next_expiry'];
    }

    public function isValid(): ?bool
    {
        $status = $this->status();

        if (!$status['consensus']) {
            return null;
        }

        return now()->lte($status['current_expiry']);
    }

    /**
     * Re-ecrit les 10 fichiers avec de nouvelles dates. Rotation
     * complete (nouveau nonce + sel aleatoire par fichier) a chaque
     * appel. Corrige aussi automatiquement les permissions
     * (chgrp www-data recursif) qu'il soit appele en root (artisan)
     * ou en www-data (requete web), pour que les deux contextes
     * puissent toujours relire les fichiers ensuite.
     */
    public function write(Carbon $current, Carbon $next): void
    {
        $base = [
            'c'  => $current->toDateString(),
            'n'  => $next->toDateString(),
            'ts' => now()->timestamp,
        ];

        foreach ($this->paths as $relativePath) {
            $fullPath = storage_path($relativePath);
            File::ensureDirectoryExists(dirname($fullPath), 0750);

            $payload = $base + ['r' => bin2hex(random_bytes(8))];
            $json = json_encode($payload, JSON_UNESCAPED_SLASHES);

            $nonce  = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $cipher = sodium_crypto_secretbox($json, $nonce, $this->key);

            File::put($fullPath, $nonce . $cipher);
            @chmod($fullPath, 0640);
            @touch($fullPath);
        }

        $this->fixPermissions();
    }

    /**
     * Force le groupe www-data (lecture) sur tout storage/app/.cache_meta,
     * recursivement, qu'on soit root ou www-data. Echec silencieux par
     * fichier (suffisant pour couvrir le cas courant).
     */
    private function fixPermissions(): void
    {
        $base = storage_path('app/.cache_meta');
        if (!is_dir($base)) {
            return;
        }

        @chgrp($base, 'www-data');
        @chmod($base, 0750);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($base, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            @chgrp($item->getPathname(), 'www-data');
            @chmod($item->getPathname(), $item->isDir() ? 0750 : 0640);
        }
    }

    private function readAndDecrypt(string $fullPath): ?array
    {
        $raw = @file_get_contents($fullPath);

        if ($raw === false || strlen($raw) <= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            return null;
        }

        $nonce  = substr($raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = substr($raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $json = sodium_crypto_secretbox_open($cipher, $nonce, $this->key);
        if ($json === false) {
            return null;
        }

        $data = json_decode($json, true);
        if (!isset($data['c'], $data['n'])) {
            return null;
        }

        return $data;
    }
}
