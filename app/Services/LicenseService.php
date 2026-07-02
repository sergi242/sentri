<?php

namespace App\Services;

use App\Models\License;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LicenseService
{
    const CACHE_KEY = 'dmce_license_validation';
    const CACHE_TTL = 3600; // 1 heure

    // ============================================================
    // 🔐 CONFIGURATION SECRÈTE — NE JAMAIS MODIFIER
    // ============================================================
    const MASTER_PHRASE = 'Anaïs est ma première fille';

    const MONTHLY_SALTS = [
        1  => 'AP1401',  // Janvier   — Anne-Pascale  14/01
        2  => 'EL0602',  // Février   — Ella          06/02
        3  => 'AN1703',  // Mars      — Anaïs         17/03
        4  => 'RO2304',  // Avril     — Rolcia        23/04
        5  => 'GI0205',  // Mai       — Ginette       02/05
        6  => 'CH2306',  // Juin      — Christie      23/06
        7  => 'CM3107',  // Juillet   — Chimène       31/07
        8  => 'DO2808',  // Août      — Doublé        28/08
        9  => 'EU0709',  // Septembre — Eunice        07/09
        10 => 'RU3010',  // Octobre   — Ruth          30/10
        11 => 'ET2511',  // Novembre  — Étienne       25/11
        12 => 'SE1312',  // Décembre  — Séraphin      13/12
    ];

    // ============================================================
    // ALGORITHME DE VÉRIFICATION
    // ============================================================

    private static function getMonthSecret(int $month): string
    {
        $salt = self::MONTHLY_SALTS[$month];
        $year = date('Y');
        return hash('sha256', self::MASTER_PHRASE . $salt . str_pad($month, 2, '0', STR_PAD_LEFT) . $year);
    }

    /**
     * Vérifie qu'une clé est valide pour le mois EN COURS
     * et extrait les métadonnées encodées (durée, org, expiration)
     */
    public static function verifyKeyAlgorithm(string $keyDisplay): array
    {
        $parts = explode('-', $keyDisplay);

        // Format : DMCE-YYYY-XXXXX-XXXXX-XXXXX-XXXXX-CCCC
        if (count($parts) !== 7 || $parts[0] !== 'DMCE') {
            return [
                'valid'  => false,
                'reason' => 'Format de clé invalide',
            ];
        }

        $part1    = $parts[2];
        $part2    = $parts[3];
        $part3    = $parts[4];
        $part4    = $parts[5];
        $checksum = $parts[6];

        // Tester tous les mois possibles (clé valide quel que soit le mois courant)
        // Seule l'expiration en DB contrôle la durée de vie
        $currentYear = (int) date('Y');
        for ($month = 1; $month <= 12; $month++) {
            $secret = self::getMonthSecret($month);
            $expectedChecksum = strtoupper(substr(
                hash_hmac('sha256', $part1 . $part2 . $part3 . $part4, $secret),
                0, 4
            ));
            if ($checksum === $expectedChecksum) {
                return [
                    'valid'  => true,
                    'reason' => 'Signature valide',
                    'month'  => $month,
                    'year'   => $currentYear,
                ];
            }
        }

        return [
            'valid'  => false,
            'reason' => 'Clé invalide — signature incorrecte',
        ];
    }

    // ============================================================
    // VALIDATION COMPLÈTE (algorithme + DB + expiration)
    // ============================================================

    public static function validate($forceRefresh = false): array
    {
        // Cache (1h) sauf si forceRefresh
        if (!$forceRefresh) {
            $cached = Cache::get(self::CACHE_KEY);
            if ($cached) {
                return $cached;
            }
        }

        $key = config('dmce.license_key');

        if (!$key) {
            $result = [
                'valid'   => false,
                'reason'  => 'Aucune clé de licence trouvée',
                'offline' => false,
            ];
            Cache::put(self::CACHE_KEY, $result, self::CACHE_TTL);
            return $result;
        }

        // 1. Vérification algorithmique mensuelle
        $algoCheck = self::verifyKeyAlgorithm($key);
        if (!$algoCheck['valid']) {
            $result = [
                'valid'   => false,
                'reason'  => $algoCheck['reason'],
                'offline' => false,
            ];
            Cache::put(self::CACHE_KEY, $result, self::CACHE_TTL);
            return $result;
        }

        // 2. Vérification en base de données
        $license = License::where('license_key_display', $key)
            ->whereNull('deleted_at')
            ->first();

        if (!$license) {
            $result = [
                'valid'   => false,
                'reason'  => 'Licence non trouvée en base de données',
                'offline' => false,
            ];
            Cache::put(self::CACHE_KEY, $result, self::CACHE_TTL);
            return $result;
        }

        // 3. Vérification statut
        if (!in_array($license->status, ['active'])) {
            $result = [
                'valid'   => false,
                'reason'  => 'Licence ' . $license->status,
                'offline' => false,
            ];
            Cache::put(self::CACHE_KEY, $result, self::CACHE_TTL);
            return $result;
        }

        // 4. Vérification expiration avec période de grâce
        if ($license->expires_at && now()->gt($license->expires_at)) {
            $graceDays     = (int) config('dmce.licence_grace_days', 7);
            $daysSinceExp  = (int) now()->diffInDays($license->expires_at);
            $graceLeft     = $graceDays - $daysSinceExp;

            if ($graceLeft > 0) {
                // Dans la période de grâce : valide mais avertissement fort
                $result = [
                    'valid'           => true,
                    'reason'          => 'Période de grâce',
                    'license'         => $license,
                    'days_remaining'  => -$daysSinceExp,
                    'grace_days_left' => $graceLeft,
                    'offline'         => false,
                ];
                Cache::put(self::CACHE_KEY, $result, 300); // cache court en grâce
                return $result;
            }

            // Hors période de grâce — bloquer et marquer expired
            $license->status = 'expired';
            $license->save();

            $result = [
                'valid'   => false,
                'reason'  => 'Licence expirée depuis le ' . $license->expires_at->format('d/m/Y'),
                'offline' => false,
            ];
            Cache::put(self::CACHE_KEY, $result, self::CACHE_TTL);
            return $result;
        }

        // 5. Vérification device (optionnelle — si device_id enregistré)
        if ($license->device_id) {
            $currentDevice = self::getDeviceId();
            if ($license->device_id !== $currentDevice) {
                $result = [
                    'valid'   => false,
                    'reason'  => 'Licence liée à un autre appareil',
                    'offline' => false,
                ];
                Cache::put(self::CACHE_KEY, $result, self::CACHE_TTL);
                return $result;
            }
        }

        // ✅ Tout est valide
        $daysRemaining = (int) now()->diffInDays($license->expires_at, false);

        $result = [
            'valid'          => true,
            'reason'         => 'Licence valide',
            'license'        => $license,
            'days_remaining' => $daysRemaining,
            'offline'        => false,
        ];

        Cache::put(self::CACHE_KEY, $result, self::CACHE_TTL);
        return $result;
    }

    // ============================================================
    // ACTIVATION (commande artisan)
    // ============================================================

    public static function activateLicense(string $keyDisplay): array
    {
        // 1. Vérification algorithmique
        $algoCheck = self::verifyKeyAlgorithm($keyDisplay);
        if (!$algoCheck['valid']) {
            return [
                'success' => false,
                'message' => $algoCheck['reason'],
            ];
        }

        $license = License::where('license_key_display', $keyDisplay)->first();

        if (!$license) {
            return [
                'success' => false,
                'message' => 'Clé introuvable en base de données',
            ];
        }

        if ($license->status === 'active') {
            return [
                'success' => false,
                'message' => 'Cette clé est déjà activée',
            ];
        }

        $deviceId   = self::getDeviceId();
        $deviceName = php_uname('n');
        $deviceIp   = $_SERVER['SERVER_ADDR'] ?? 'localhost';

        $result = $license->activate($deviceId, $deviceName, $deviceIp);

        if ($result['success']) {
            self::saveLicenseToEnv($keyDisplay);
            Cache::forget(self::CACHE_KEY);
        }

        return $result;
    }

    // ============================================================
    // UTILITAIRES
    // ============================================================

    public static function getDeviceId(): string
    {
        $hostname     = php_uname('n') ?? 'unknown';
        $macAddresses = self::getMacAddress();
        return hash('sha256', $hostname . '|' . $macAddresses);
    }

    private static function getMacAddress(): string
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $output = shell_exec('getmac') ?? '';
                preg_match('/([0-9A-F]{2}[:-]){5}([0-9A-F]{2})/', $output, $matches);
                return $matches[0] ?? 'UNKNOWN';
            } else {
                $output = shell_exec("ip link show | grep ether | awk '{print $2}' | head -1") ?? '';
                return trim($output) ?: 'UNKNOWN';
            }
        } catch (\Exception $e) {
            return 'UNKNOWN';
        }
    }

    public static function saveKeyToEnv(string $licenseKey): void
    {
        $envFile    = base_path('.env');
        $envContent = File::get($envFile);

        if (Str::contains($envContent, 'DMCE_LICENSE_KEY=')) {
            $envContent = preg_replace(
                '/DMCE_LICENSE_KEY=.*/',
                'DMCE_LICENSE_KEY=' . $licenseKey,
                $envContent
            );
        } else {
            $envContent .= "\nDMCE_LICENSE_KEY=" . $licenseKey;
        }

        File::put($envFile, $envContent);
        \Config::set('dmce.license_key', $licenseKey);
    }

    public static function isMysqlRunning(): bool
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getInfo(): array
    {
        $validation = self::validate();

        if (!$validation['valid']) {
            return [
                'status' => 'invalid',
                'reason' => $validation['reason'],
            ];
        }

        return $validation['license']->getInfo();
    }
}
