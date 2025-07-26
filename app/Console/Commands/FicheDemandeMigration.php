<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Demande;
use App\Models\FicheDemande;
use Illuminate\Console\Command;

class FicheDemandeMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmce:fiche-demande-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $demandes = Demande::all();
        $count = 0;
        foreach ($demandes as $demande) {
            $ficheDemande = FicheDemande::where('demande_id', $demande->id)->first();
            if (!$ficheDemande) {
                $ficheDemande = new FicheDemande();
                $ficheDemande->demande_id = $demande->id;
                $ficheDemande->date_emission_fiche = $demande->date_demande;
                $ficheDemande->date_valite_fiche = $demande->date_validiter_fiche ?? Carbon::parse($demande->date_demande)->addMonths(3);
                $ficheDemande->save();
                $this->info('Fiche demande migrée avec succès pour la demande ' . $demande->id);
                $count++;
            }
        }
        $this->info('Total fiche demande migrées avec succès: ' . $count);
    }
}
