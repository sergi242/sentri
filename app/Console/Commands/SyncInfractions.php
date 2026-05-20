<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InfractionService;

class SyncInfractions extends Command
{
    protected $signature   = 'infractions:sync';
    protected $description = 'Synchronise automatiquement les infractions des impétrants';

    public function handle(InfractionService $service): void
    {
        $this->info('Synchronisation des infractions en cours...');
        $service->syncAll();
        $this->info('Terminé.');
    }
}