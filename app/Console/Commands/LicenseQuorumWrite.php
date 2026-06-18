<?php

namespace App\Console\Commands;

use App\Services\LicenseDateQuorumService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LicenseQuorumWrite extends Command
{
    protected $signature = 'license:quorum-write {current : Date courante d\'expiration YYYY-MM-DD} {next : Date d\'expiration suivante YYYY-MM-DD}';

    protected $description = 'Ecrit (ou renouvelle) les 10 fichiers chiffres de quorum de licence';

    public function handle(LicenseDateQuorumService $service): int
    {
        try {
            $current = Carbon::parse($this->argument('current'))->startOfDay();
            $next    = Carbon::parse($this->argument('next'))->startOfDay();
        } catch (\Throwable $e) {
            $this->error('Date invalide. Format attendu: YYYY-MM-DD');
            return self::FAILURE;
        }

        if ($next->lte($current)) {
            $this->warn('La date "next" n\'est pas posterieure a "current" - poursuite quand meme.');
        }

        if (empty((string) config('license_quorum.secret'))) {
            $this->error('LICENSE_QUORUM_SECRET n\'est pas defini dans .env. Abandon.');
            return self::FAILURE;
        }

        $service->write($current, $next);

        $this->info('10 fichiers de quorum ecrits/renouveles.');
        $this->line("  current_expiry : {$current->toDateString()}");
        $this->line("  next_expiry    : {$next->toDateString()}");
        $this->newLine();
        $this->warn('Verifier les permissions si besoin:');
        $this->line('  sudo chown -R www-data:www-data storage/app/.cache_meta');

        return self::SUCCESS;
    }
}
