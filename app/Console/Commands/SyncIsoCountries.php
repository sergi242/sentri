<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\ISO3166\ISO3166;

class SyncIsoCountries extends Command
{
    protected $signature = 'pays:sync-iso';
    protected $description = 'Synchronise la table pays avec les pays ISO 3166-1 uniquement';

  public function handle()
{
    $iso = new \League\ISO3166\ISO3166();

    $updated = 0;
    $notFound = 0;

    foreach ($iso->all() as $country) {

        $affected = \DB::table('pays')
            ->where('lib_pays', $country['name'])
            ->update([
                'code' => $country['alpha2'],
                'updated_at' => now(),
            ]);

        if ($affected) {
            $updated++;
        } else {
            $notFound++;
        }
    }

    $this->info("✔ Codes ISO mis à jour : $updated");
    $this->warn("⚠ Pays ISO non trouvés en base : $notFound");
}


}
