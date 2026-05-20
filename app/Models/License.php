<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class License extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'license_key',
        'license_key_display',
        'device_id',
        'device_name',
        'device_ip',
        'duration_days',
        'activated_at',
        'expires_at',
        'status',
        'features',
        'max_users',
        'max_impetrants',
        'max_demandes_per_day',
        'organization_name',
        'notes',
        'last_validated_at',
        'last_validated_ip',
        'validation_count',
    ];

    protected $casts = [
        'features' => 'array',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_validated_at' => 'datetime',
    ];

    protected $hidden = ['license_key'];

    // Relation
    public function validations()
    {
        return $this->hasMany(LicenseValidation::class);
    }

    /**
     * Générer UNE clé (sans l'activer)
     * Cette clé est en attente jusqu'à activation manuelle
     */
    public static function generateKey($durationDays = 30, $organizationName = null)
    {
        $year = date('Y');
        $random1 = strtoupper(Str::random(5));
        $random2 = strtoupper(Str::random(5));
        $random3 = strtoupper(Str::random(5));
        
        $keyDisplay = "DMCE-{$year}-{$random1}-{$random2}-{$random3}";
        $keyEncrypted = Crypt::encryptString($keyDisplay);
        
        // La clé est créée mais NON ACTIVÉE
        $license = self::create([
            'license_key' => $keyEncrypted,
            'license_key_display' => $keyDisplay,
            'duration_days' => $durationDays,
            'status' => 'pending',
            'organization_name' => $organizationName,
            'features' => [
                'users' => true,
                'demandes' => true,
                'watchlist' => true,
                'reports' => true,
                'api' => true,
                'impetrants' => true,
                'flux_migratoires' => true,
            ],
        ]);

        return $license;
    }

    /**
     * Activer une clé (première utilisation)
     * - Lie au device
     * - Calcule expiration
     * - Enregistre dans .env
     */
    public function activate($deviceId, $deviceName, $deviceIp)
    {
        if ($this->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'Cette clé a déjà été activée ou est invalide',
            ];
        }

        // Vérifier expiration
        if ($this->expires_at && $this->expires_at->isPast()) {
            return [
                'success' => false,
                'message' => 'Clé expirée',
            ];
        }

        // Activer
        $expiresAt = now()->addDays($this->duration_days);

        $this->update([
            'status' => 'active',
            'device_id' => $deviceId,
            'device_name' => $deviceName,
            'device_ip' => $deviceIp,
            'activated_at' => now(),
            'expires_at' => $expiresAt,
            'last_validated_at' => now(),
        ]);

        // Logger
        $this->validations()->create([
            'ip_address' => $deviceIp,
            'action' => 'activate',
            'success' => true,
            'details' => "Activée sur {$deviceName}",
        ]);

        return [
            'success' => true,
            'message' => 'Licence activée avec succès',
            'license' => $this,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Valider une licence à l'authentification
     */
    public static function validateKey($keyDisplay, $deviceId)
    {
        $license = self::where('license_key_display', $keyDisplay)->first();

        if (!$license) {
            return [
                'valid' => false,
                'reason' => 'Licence non trouvée',
            ];
        }

        // Vérifier le device (STRICT)
        if ($license->device_id !== $deviceId) {
            return [
                'valid' => false,
                'reason' => 'Cette licence est liée à un autre ordinateur (' . substr($license->device_id, 0, 16) . '...)',
                'license_id' => $license->id,
            ];
        }

        // Vérifier le statut
        if ($license->status !== 'active') {
            return [
                'valid' => false,
                'reason' => 'Licence inactive, révoquée ou déjà utilisée',
            ];
        }

        // Vérifier l'expiration
        if ($license->expires_at && $license->expires_at->isPast()) {
            $license->update(['status' => 'expired']);
            
            return [
                'valid' => false,
                'reason' => 'Licence expirée le ' . $license->expires_at->format('d/m/Y'),
                'expires_at' => $license->expires_at,
            ];
        }

        // ✅ Valide !
        $license->increment('validation_count');
        $license->update([
            'last_validated_at' => now(),
            'last_validated_ip' => request()->ip(),
        ]);

        // Logger
        $license->validations()->create([
            'ip_address' => request()->ip(),
            'action' => 'validate',
            'success' => true,
        ]);

        return [
            'valid' => true,
            'license' => $license,
            'days_remaining' => $license->expires_at->diffInDays(now()),
        ];
    }

    /**
     * Révoquer une licence
     */
    public function revoke($reason = null)
    {
        $this->update([
            'status' => 'revoked',
            'notes' => $reason,
        ]);

        $this->validations()->create([
            'ip_address' => request()->ip(),
            'action' => 'revoke',
            'success' => true,
            'details' => $reason,
        ]);

        return true;
    }

    /**
     * Prolonger une licence
     */
    public function extend($additionalDays = 30)
    {
        $newExpiresAt = $this->expires_at->addDays($additionalDays);

        $this->update([
            'expires_at' => $newExpiresAt,
        ]);

        return [
            'success' => true,
            'new_expires_at' => $newExpiresAt,
        ];
    }

    /**
     * Obtenir les infos
     */
    public function getInfo()
    {
        return [
            'key_display' => $this->license_key_display,
            'organization' => $this->organization_name,
            'device_name' => $this->device_name,
            'device_id' => substr($this->device_id, 0, 16) . '...',
            'status' => $this->status,
            'activated_at' => $this->activated_at,
            'expires_at' => $this->expires_at,
            'days_remaining' => $this->expires_at ? $this->expires_at->diffInDays(now()) : 0,
            'validation_count' => $this->validation_count,
            'features' => $this->features,
        ];
    }
}
