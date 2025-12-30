<?php

namespace App\Console\Commands;

use App\Models\Impetrant;
use Illuminate\Console\Command;
use App\Models\ImpetrantNationalite;

class ImpetrantNationaliteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmce:impetrant-nationalite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ajout des nationalités des impétrants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $impetrants = Impetrant::all();
        $count = 0;
        foreach ($impetrants as $impetrant) {
            $in = ImpetrantNationalite::where("impetrant_id", $impetrant->id)->where("pays_id", $impetrant->nationalites_id)->first();
            if (!$in) {
                $ina = new ImpetrantNationalite();
                $ina->impetrant_id = $impetrant->id;
                $ina->pays_id = $impetrant->nationalites_id;
                $ina->save();
                $this->info("Nationalité ajoutée pour l'impétrant " . $impetrant->id);
                $count++;
            }
        }
        $this->info("Nationalités ajoutées pour " . $count . " impétrants");
    }
}
