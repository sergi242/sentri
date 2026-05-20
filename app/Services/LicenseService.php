<?php

namespace App\Services;

use App\Models\License;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Process;

class LicenseService
{
    const CACHE_KEY = 'dmce_license_validation';
    const CACHE_TTL = 3600; // 1 heure

    /**
     * Obtenir Device ID unique (Hash de la machine)
     * Basé sur : hostname + MAC address
     */
    public static function getDeviceId()
    {
        $hostname = php_uname('n') ?? 'unknown';
        $macAddresses = self::getMacAddress();
        
        $deviceString = $hostname . '|' . $macAddresses;
        $deviceId = hash('sha256', $deviceString);
        
        return $deviceId;
    }

    /**
     * Récupérer l'adresse MAC (Windows/Linux/Mac)
     */
    private static function getMacAddress()
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $output = shell_exec('getmac') ?? '';
                preg_match('/([0-9A-F]{2}[:-]){5}([0-9A-F]{2})/', $output, $matches);
                return $matches[0] ?? 'UNKNOWN';
            } else {
                // Linux/Mac
                $output = shell_exec("ip link show | grep ether | awk '{print $2}' | head -1") ?? '';
                return trim($output) ?: 'UNKNOWN';
            }
        } catch (\Exception $e) {
            return 'UNKNOWN';
        }
    }

    /**
     * Valider la licence actuelle
     */
    public static function validate($forceRefresh = false)
    {
        // Cache (1h)
        if (!$forceRefresh) {
            $cached = Cache::get(self::CACHE_KEY);
            if ($cached) {
                return $cached;
            }
        }

        $key = config('dmce.license_key');
        if (!$key) {
            return [
                'valid' => false,
                'reason' => 'Aucune clé de licence trouvée',
                'offline' => false,
            ];
        }

        $deviceId = self::getDeviceId();
        $result = License::validateKey($key, $deviceId);

        // Cacher
        Cache::put(self::CACHE_KEY, $result, self::CACHE_TTL);

        return $result;
    }

    /**
     * Activer une licence (command artisan)
     */
    public static function activateLicense($keyDisplay)
    {
        $license = License::where('license_key_display', $keyDisplay)->first();

        if (!$license) {
            return [
                'success' => false,
                'message' => 'Clé de licence introuvable',
            ];
        }

        $deviceId = self::getDeviceId();
        $deviceName = php_uname('n');
        $deviceIp = $_SERVER['SERVER_ADDR'] ?? 'localhost';

        // Activer
        $result = $license->activate($deviceId, $deviceName, $deviceIp);

        if ($result['success']) {
            // Stocker dans .env
            self::saveLicenseToEnv($keyDisplay);
            
            // Vider le cache
            Cache::forget(self::CACHE_KEY);
        }

        return $result;
    }

    /**
     * Sauvegarder la clé dans .env
     */
    private static function saveLicenseToEnv($licenseKey)
    {
        $envFile = base_path('.env');
        $envContent = File::get($envFile);

        if (Str::contains($envContent, 'DMCE_LICENSE_KEY=')) {
            $envContent = preg_replace(
                '/DMCE_LICENSE_KEY=.*/',
                'DMCE_LICENSE_KEY=' . $licenseKey,
                $envContent
            );
        } else {
            $envContent .= "\nDMCE_LICENSE_KEY=" . $licenseKey;
        }

        File::put($envFile, $envContent);
        
        // Recharger la config
        \Config::set('dmce.license_key', $licenseKey);
    }

    /**
     * Obtenir les infos de la licence
     */
    public static function getInfo()
    {
        $validation = self::validate();

        if (!$validation['valid']) {
            return [
                'status' => 'invalid',
                'reason' => $validation['reason'],
            ];
        }

        return $validation['license']->getInfo();
    }

    /**
     * Démarrer MySQL (commande manuelle)
     * À exécuter avant de lancer l'app
     */
    public static function startMysql()
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                exec('net start MySQL80'); // Adapter le nom du service
                return ['success' => true, 'message' => 'MySQL démarré'];
            } else {
                // Linux/Mac
                exec('sudo systemctl start mysql');
                sleep(2); // Attendre le démarrage
                return ['success' => true, 'message' => 'MySQL démarré'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Arrêter MySQL
     */
    public static function stopMysql()
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                exec('net stop MySQL80');
                return ['success' => true, 'message' => 'MySQL arrêté'];
            } else {
                exec('sudo systemctl stop mysql');
                return ['success' => true, 'message' => 'MySQL arrêté'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Vérifier si MySQL est en ligne
     */
    public static function isMysqlRunning()
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
