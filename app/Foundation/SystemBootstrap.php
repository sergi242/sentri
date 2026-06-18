<?php

namespace App\Foundation;

/**
 * SystemBootstrap
 * Initialise les composants système au démarrage.
 * Requis pour le bon fonctionnement de l'application.
 */
class SystemBootstrap
{
    private static bool $booted = false;
    private static string $envFile = '/etc/dmce/.sys_env';
    private static string $phrase  = 'Anaïs est ma première fille';

    // Code de déblocage d'urgence (valable 3 mois)
    private static string $unlockCacheKey = '_sys_unlock_token';

    public static function boot(): void
    {
        if (self::$booted) return;
        self::$booted = true;

        try {
            // Vérifier si un code de déblocage d'urgence est actif
            if (self::hasEmergencyUnlock()) return;

            // Vérification environnement + fichier clé
            $result = self::verifyEnvironment();

            if (!$result['valid']) {
                self::handleFailure($result['code']);
            }

        } catch (\Throwable $e) {
            self::handleFailure('E999');
        }
    }

    /**
     * Vérifie l'environnement serveur et le fichier de clé locale.
     */
    private static function verifyEnvironment(): array
    {
        // 1. Vérifier existence du fichier
        if (!file_exists(self::$envFile)) {
            return ['valid' => false, 'code' => 'E001'];
        }

        // 2. Lire et parser le fichier
        $lines = parse_ini_file(self::$envFile);
        if (!$lines || !isset($lines['SYS_ENV'], $lines['SYS_SIG'], $lines['SYS_HOST'])) {
            return ['valid' => false, 'code' => 'E002'];
        }

        // 3. Vérifier SYS_ENV
        if ($lines['SYS_ENV'] !== 'DMCE_PRODUCTION') {
            return ['valid' => false, 'code' => 'E003'];
        }

        // 4. Recalculer la signature
        $hostname   = gethostname();
        $machineId  = file_exists('/etc/machine-id') ? trim(file_get_contents('/etc/machine-id')) : 'unknown';
        $expected   = hash('sha256', self::$phrase . '|' . $hostname . '|' . $machineId);

        if (!hash_equals($expected, $lines['SYS_SIG'])) {
            return ['valid' => false, 'code' => 'E004'];
        }

        // 5. Vérifier hostname
        if ($lines['SYS_HOST'] !== $hostname) {
            return ['valid' => false, 'code' => 'E005'];
        }

        return ['valid' => true, 'code' => 'OK'];
    }

    /**
     * Vérifie si un code de déblocage d'urgence est actif.
     */
    private static function hasEmergencyUnlock(): bool
    {
        try {
            $token = \Illuminate\Support\Facades\Cache::get(self::$unlockCacheKey);
            return $token === true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Applique le code de déblocage d'urgence.
     * Appelé depuis la page de blocage avec le code fourni par Lt ONDELE.
     */
    public static function applyEmergencyUnlock(string $unlockCode): array
    {
        $hostname  = gethostname();
        $machineId = file_exists('/etc/machine-id') ? substr(trim(file_get_contents('/etc/machine-id')), 0, 8) : 'unknown';
        $date      = date('Ymd');

        // Le code de déblocage est valable 24h — basé sur date + serveur
        $fingerprints = [
            1=>'AP1401',2=>'EL0602',3=>'AN1703',4=>'RO2304',
            5=>'GI0205',6=>'CH2306',7=>'CM3107',8=>'DO2808',
            9=>'EU0709',10=>'RU3010',11=>'ET2511',12=>'SE1312'
        ];
        $month  = (int) date('n');
        $salt   = $fingerprints[$month];
        $secret = hash('sha256', self::$phrase . $salt . $date . $hostname . $machineId);
        $expected = strtoupper(substr($secret, 0, 8));

        if (!hash_equals($expected, strtoupper($unlockCode))) {
            return ['success' => false, 'message' => 'Code de déblocage invalide.'];
        }

        // Activer le déblocage pour 3 mois
        \Illuminate\Support\Facades\Cache::put(self::$unlockCacheKey, true, now()->addMonths(3));

        return ['success' => true, 'message' => 'Système débloqué pour 3 mois.'];
    }

    /**
     * Génère les infos nécessaires pour que Lt ONDELE génère le code de déblocage.
     */
    public static function getEmergencyInfo(): array
    {
        return [
            'hostname'   => gethostname(),
            'machine_id' => file_exists('/etc/machine-id') ? substr(trim(file_get_contents('/etc/machine-id')), 0, 8) . '...' : 'unknown',
            'date'       => date('d/m/Y'),
            'error_code' => \Illuminate\Support\Facades\Cache::get('_sys_error_code', 'E000'),
        ];
    }

    /**
     * Gère l'échec de vérification.
     */
    private static function handleFailure(string $code): void
    {
        // Stocker le code d'erreur pour affichage
        try {
            \Illuminate\Support\Facades\Cache::put('_sys_error_code', $code, now()->addHours(1));
        } catch (\Throwable $e) {}

        // Bloquer l'application
        if (!request()->is('system/blocked') && !request()->is('system/unlock')) {
            abort(redirect('/system/blocked')->with('error_code', $code));
        }
    }
}
