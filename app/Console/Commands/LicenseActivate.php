<?php

namespace App\Console\Commands;

use App\Services\LicenseService;
use Illuminate\Console\Command;

class LicenseActivate extends Command
{
    protected $signature = 'license:activate {key? : Clé de licence}';
    protected $description = 'Activer une clé de licence (démarrer MySQL et l\'app)';

    public function handle()
    {
        $key = $this->argument('key');

        if (!$key) {
            $key = $this->ask('Entrez votre clé de licence (DMCE-YYYY-XXXXX-...)');
        }

        $this->info("🔐 Activation en cours...\n");

        // 1. Activer la licence
        $result = LicenseService::activateLicense($key);

        if (!$result['success']) {
            $this->error("❌ Erreur: {$result['message']}");
            return 1;
        }

        $this->line("✅ Licence activée !\n");
        $this->table(['Propriété', 'Valeur'], [
            ['Clé', $key],
            ['Ordinateur', php_uname('n')],
            ['Device ID', substr(LicenseService::getDeviceId(), 0, 16) . '...'],
            ['Expire le', $result['license']->expires_at->format('d/m/Y à H:i')],
            ['Jours', $result['license']->duration_days],
        ]);

        // 2. Démarrer MySQL
        $this->info("\n🚀 Démarrage de MySQL...");
        $mysqlResult = LicenseService::startMysql();
        
        if ($mysqlResult['success']) {
            $this->info("✅ {$mysqlResult['message']}\n");
        } else {
            $this->warn("⚠️ MySQL: {$mysqlResult['message']}");
            $this->warn("→ Démarrez MySQL manuellement si besoin\n");
        }

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("🎉 SYSTÈME PRÊT !");
        $this->info("Lancez : php artisan serve");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n");

        return 0;
    }
}
