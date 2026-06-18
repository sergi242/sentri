<?php

namespace App\Console\Commands;

use App\Models\License;
use App\Services\LicenseService;
use Illuminate\Console\Command;

class LicenseGenerate extends Command
{
    protected $signature = 'license:register {key : Clé générée sur votre PC} {--days=30} {--org=DMCE}';
    protected $description = 'Enregistrer en DB une clé générée avec dmce_keygen.php';

    public function handle(): int
    {
        $key  = strtoupper(trim($this->argument('key')));
        $days = (int) $this->option('days');
        $org  = $this->option('org');

        $this->info("\n🔍 Vérification algorithmique...\n");

        // Vérifier l'algorithme
        $algoCheck = LicenseService::verifyKeyAlgorithm($key);

        if (!$algoCheck['valid']) {
            $this->error("❌ Clé rejetée : {$algoCheck['reason']}");
            return 1;
        }

        $this->line("✅ Signature valide pour " . date('F Y') . "\n");

        // Enregistrer en DB
        $license = License::registerKey($key, $days, $org);

        $this->info("📋 Clé enregistrée !\n");
        $this->table(['Propriété', 'Valeur'], [
            ['Clé',          $license->license_key_display],
            ['Organisation', $org],
            ['Durée',        $days . ' jours'],
            ['Statut',       strtoupper($license->status)],
        ]);

        $this->line("\nPour activer :");
        $this->line("  php artisan license:activate {$key}\n");

        return 0;
    }
}
