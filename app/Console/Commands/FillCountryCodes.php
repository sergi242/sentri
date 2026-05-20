<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pays;

class FillCountryCodes extends Command
{
    protected $signature = 'pays:fill-codes';
    protected $description = 'Remplit les codes ISO alpha-2 pour tous les pays';

    public function handle()
    {
        $countries = [
            'Afghanistan' => 'AF',
            'Afrique du Sud' => 'ZA',
            'Albanie' => 'AL',
            'Algérie' => 'DZ',
            'Allemagne' => 'DE',
            'Angola' => 'AO',
            'Arabie saoudite' => 'SA',
            'Argentine' => 'AR',
            'Australie' => 'AU',
            'Autriche' => 'AT',
            'Belgique' => 'BE',
            'Bénin' => 'BJ',
            'Brésil' => 'BR',
            'Burkina Faso' => 'BF',
            'Burundi' => 'BI',
            'Cameroun' => 'CM',
            'Canada' => 'CA',
            'Cap-Vert' => 'CV',
            'Centrafrique' => 'CF',
            'Chine' => 'CN',
            'Comores' => 'KM',
            'Congo' => 'CG',
            'Congo (RDC)' => 'CD',
            'Côte d\'Ivoire' => 'CI',
            'Égypte' => 'EG',
            'Espagne' => 'ES',
            'États-Unis' => 'US',
            'France' => 'FR',
            'Gabon' => 'GA',
            'Ghana' => 'GH',
            'Guinée' => 'GN',
            'Guinée équatoriale' => 'GQ',
            'Inde' => 'IN',
            'Italie' => 'IT',
            'Japon' => 'JP',
            'Kenya' => 'KE',
            'Liban' => 'LB',
            'Libye' => 'LY',
            'Mali' => 'ML',
            'Maroc' => 'MA',
            'Mauritanie' => 'MR',
            'Niger' => 'NE',
            'Nigeria' => 'NG',
            'Ouganda' => 'UG',
            'Royaume-Uni' => 'GB',
            'Rwanda' => 'RW',
            'Sénégal' => 'SN',
            'Sierra Leone' => 'SL',
            'Tchad' => 'TD',
            'Togo' => 'TG',
            'Tunisie' => 'TN',
            'Zambie' => 'ZM',
            'Zimbabwe' => 'ZW',
        ];

        $updated = 0;

        foreach ($countries as $name => $code) {
            $count = Pays::where('lib_pays', 'LIKE', $name)
                ->whereNull('code')
                ->update(['code' => $code]);

            $updated += $count;
        }

        $this->info("Codes pays mis à jour : $updated");
        return Command::SUCCESS;
    }
}
