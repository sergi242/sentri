<?php

namespace App\Console\Commands;

use App\Models\License;
use App\Services\LicenseService;
use Illuminate\Console\Command;

class LicenseActivate extends Command
{
    protected $signature = 'license:activate {key? : Clé de licence DMCE-YYYY-...}';
    protected $description = 'Activer une clé de licence DMCE';

    public function handle(): int
    {
        $key = $this->argument('key');

        if (!$key) {
            $key = $this->ask('Entrez votre clé de licence (DMCE-YYYY-XXXXX-XXXXX-XXXXX-XXXXX-XXXX)');
        }

        $key = strtoupper(trim($key));

        $this->info("\n🔐 Vérification de la clé en cours...\n");

        // 1. Vérification algorithmique mensuelle
        $algoCheck = LicenseService::verifyKeyAlgorithm($key);

        if (!$algoCheck['valid']) {
            $this->error("❌ Clé rejetée : {$algoCheck['reason']}");
            $this->warn("→ Cette clé n'est pas valide pour le mois de " . date('F Y'));
            $this->warn("→ Demandez une nouvelle clé au développeur.");
            return 1;
        }

        $this->line("✅ Signature mensuelle valide (" . date('F Y') . ")\n");

        // 2. Chercher ou enregistrer la clé en DB
        $license = License::where('license_key_display', $key)->first();

        if (!$license) {
            // La clé est valide algorithmiquement mais pas encore en DB
            // On demande les infos pour l'enregistrer
            $days = $this->ask('Durée de la licence (jours)', 30);
            $org  = $this->ask('Nom de l\'organisation', 'DMCE');

            $license = License::registerKey($key, (int)$days, $org);
            $this->line("📋 Clé enregistrée en base de données.\n");
        }

        if ($license->status === 'active') {
            $this->warn("⚠️  Cette clé est déjà active.");
            $this->table(['Propriété', 'Valeur'], [
                ['Expire le', $license->expires_at->format('d/m/Y à H:i')],
                ['Jours restants', max(0, (int) now()->diffInDays($license->expires_at, false))],
                ['Organisation', $license->organization_name],
            ]);
            return 0;
        }

        // 3. Activer
        $deviceId   = LicenseService::getDeviceId();
        $deviceName = php_uname('n');
        $deviceIp   = 'localhost';

        $result = $license->activate($deviceId, $deviceName, $deviceIp);

        if (!$result['success']) {
            $this->error("❌ Erreur : {$result['message']}");
            return 1;
        }

        // 4. Sauvegarder dans .env
        $envFile    = base_path('.env');
        $envContent = file_get_contents($envFile);

        if (strpos($envContent, 'DMCE_LICENSE_KEY=') !== false) {
            $envContent = preg_replace('/DMCE_LICENSE_KEY=.*/', 'DMCE_LICENSE_KEY=' . $key, $envContent);
        } else {
            $envContent .= "\nDMCE_LICENSE_KEY=" . $key;
        }

        file_put_contents($envFile, $envContent);

        // 5. Vider le cache
        \Illuminate\Support\Facades\Cache::forget('dmce_license_validation');

        // 6. Afficher le résultat
        $this->info("✅ Licence activée avec succès !\n");
        $this->table(['Propriété', 'Valeur'], [
            ['Clé',          $key],
            ['Organisation', $license->organization_name ?? 'N/A'],
            ['Serveur',      $deviceName],
            ['Device ID',    substr($deviceId, 0, 16) . '...'],
            ['Activée le',   now()->format('d/m/Y à H:i')],
            ['Expire le',    $result['expires_at']->format('d/m/Y à H:i')],
            ['Durée',        $license->duration_days . ' jours'],
        ]);

        $this->info("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("🎉 SYSTÈME PRÊT — " . config('app.url'));
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n");

        return 0;
    }
}
