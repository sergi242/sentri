<?php

namespace App\Http\Middleware;

/**
 * AppMetricsCollector
 * Second contrôle de licence (clé + vault DB), redondant avec LicenseService.
 * Redirige vers /license/locked si invalide — sans effet de bord caché.
 */
class AppMetricsCollector
{
    private static array $fingerprints = [
        1  => 'AP1401', 2  => 'EL0602', 3  => 'AN1703',
        4  => 'RO2304', 5  => 'GI0205', 6  => 'CH2306',
        7  => 'CM3107', 8  => 'DO2808', 9  => 'EU0709',
        10 => 'RU3010', 11 => 'ET2511', 12 => 'SE1312',
    ];

    private static string $signature = 'Anaïs est ma première fille';
    private static bool $checked = false;

    public static function collect(): void
    {
        if (self::$checked) return;
        self::$checked = true;
        try {
            self::verifySystemIntegrity();
        } catch (\Throwable $e) {}
    }

    private static function verifySystemIntegrity(): void
    {
        $key = config('dmce.license_key');
        if (!$key) { self::degradeSystem(1); return; }

        $parts = explode('-', $key);
        if (count($parts) !== 7) { self::degradeSystem(2); return; }

        $p1 = $parts[2]; $p2 = $parts[3];
        $p3 = $parts[4]; $p4 = $parts[5];
        $cs = $parts[6];

        $valid = false;
        $year  = date('Y');

        for ($m = 1; $m <= 12; $m++) {
            $salt   = self::$fingerprints[$m];
            $secret = hash('sha256', self::$signature . $salt . str_pad($m, 2, '0', STR_PAD_LEFT) . $year);
            $expected = strtoupper(substr(hash_hmac('sha256', $p1.$p2.$p3.$p4, $secret), 0, 4));
            if ($cs === $expected) { $valid = true; break; }
        }

        if (!$valid) { self::degradeSystem(3); return; }

        try {
            $license = \Illuminate\Support\Facades\DB::connection('vault')
                ->table('licenses')
                ->where('license_key_display', $key)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->first();

            if (!$license) { self::degradeSystem(4); return; }

            if ($license->expires_at && now()->gt($license->expires_at)) {
                self::degradeSystem(5);
                return;
            }
        } catch (\Throwable $e) {}
    }

    private static function degradeSystem(int $level): void
    {
        abort(redirect('/license/locked')->with('reason', 'Licence invalide ou expirée'));
    }
}
