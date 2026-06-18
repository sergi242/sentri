<?php

namespace App\Console\Commands;

use App\Services\LicenseDateQuorumService;
use Illuminate\Console\Command;

class LicenseQuorumStatus extends Command
{
    protected $signature = 'license:quorum-status';

    protected $description = 'Affiche le diagnostic du quorum de fichiers de licence';

    public function handle(LicenseDateQuorumService $service): int
    {
        $status = $service->status();

        $this->table(['Indicateur', 'Valeur'], [
            ['Fichiers configures',          $status['total_files']],
            ['Fichiers presents',            $status['files_present']],
            ['Fichiers dechiffres OK',        $status['files_decrypted']],
            ['Jour majoritaire (mtime)',     $status['mtime_majority_day'] ?? '(indetermine)'],
            ['Fichiers a jour (mtime)',       $status['mtime_agreement'] . ' / ' . $status['files_present']],
            ['Fichiers mtime suspect',        $status['mtime_outliers']],
            ['Paires distinctes (contenu)',  $status['distinct_pairs']],
            ['Accord majoritaire (contenu)', $status['agreement_count'] . ' / ' . $status['threshold'] . ' requis'],
            ['Consensus atteint',            $status['consensus'] ? 'OUI' : 'NON'],
            ['Suspicion de falsification',   $status['tamper_suspected'] ? 'OUI' : 'NON'],
            ['Date expiration courante',     $status['current_expiry']?->toDateString() ?? '(indetermine)'],
            ['Date expiration suivante',     $status['next_expiry']?->toDateString() ?? '(indetermine)'],
        ]);

        if ($status['mtime_outliers'] > 0) {
            $this->warn("{$status['mtime_outliers']} fichier(s) ont ete modifies a une date differente des autres.");
        }

        if ($status['consensus']) {
            $valid = now()->lte($status['current_expiry']);
            if ($valid) {
                $this->info('Licence VALIDE selon le quorum.');
            } else {
                $this->error('Licence EXPIREE selon le quorum.');
            }
        } else {
            $this->error('Quorum non atteint - statut indetermine (fallback necessaire).');
        }

        return self::SUCCESS;
    }
}
