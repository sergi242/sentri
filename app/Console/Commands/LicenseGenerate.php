<?php

namespace App\Console\Commands;

use App\Models\License;
use Illuminate\Console\Command;

class LicenseGenerate extends Command
{
    protected $signature = 'license:generate {--days=30} {--org=""}';
    protected $description = 'Générer une nouvelle clé de licence (non activée)';

    public function handle()
    {
        $days = $this->option('days');
        $org = $this->option('org') ?: 'ONDELE SYSTEMS';

        $license = License::generateKey($days, $org);

        $this->info("\n✅ LICENCE GÉNÉRÉE\n");
        $this->line("┌─ Clé de licence");
        $this->line("│  {$license->license_key_display}");
        $this->line("│");
        $this->line("├─ Organisation: {$org}");
        $this->line("├─ Validité: {$days} jours");
        $this->line("├─ Statut: " . strtoupper($license->status));
        $this->line("└─ À activer avec: php artisan license:activate\n");

        return 0;
    }
}
