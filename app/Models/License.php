<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class License extends Model
{
    use SoftDeletes;

    protected $connection = 'vault';

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
        'grace_used',
        'grace_used_at',
    ];

    protected $casts = [
        'features'         => 'array',
        'activated_at'     => 'datetime',
        'expires_at'       => 'datetime',
        'last_validated_at'=> 'datetime',
        'grace_used_at'    => 'datetime',
        'grace_used'       => 'boolean',
    ];

    protected $hidden = ['license_key'];

    // ============================================================
    // EVENEMENTS MODELE
    // ============================================================

    /**
     * A chaque fois que expires_at change (activation, prolongation,
     * periode de grace - peu importe le chemin), on resynchronise
     * automatiquement les 10 fichiers de quorum de licence avec la
     * nouvelle date. Echec silencieux (logge seulement) pour ne
     * jamais bloquer le flux d'activation/grace.
     */
    protected static function booted(): void
    {
        static::saved(function (self $license) {
            if (!$license->wasChanged('expires_at') || !$license->expires_at) {
                return;
            }

            try {
                $current  = $license->expires_at->copy();
                $duration = $license->duration_days ?: 30;
                $next     = $current->copy()->addDays($duration);

                app(\App\Services\LicenseDateQuorumService::class)->write($current, $next);

                \Illuminate\Support\Facades\Log::info('[QuorumLicence] Quorum resynchronise automatiquement', [
                    'current_expiry' => $current->toDateString(),
                    'next_expiry'    => $next->toDateString(),
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('[QuorumLicence] Echec de resynchronisation automatique', [
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    // ============================================================
    // RELATION
    // ============================================================

    public function validations()
    {
        return $this->hasMany(LicenseValidation::class);
    }

    // ============================================================
    // ENREGISTRER UNE CLÉ (générée sur le PC du développeur)
    // La génération réelle se fait via dmce_keygen.php en local.
    // Cette méthode insère simplement la clé en DB en attente.
    // ============================================================

    public static function registerKey(string $keyDisplay, int $durationDays = 30, ?string $organizationName = null): self
    {
        // Vérifier que la clé n'existe pas déjà
        $existing = self::where('license_key_display', $keyDisplay)->first();
        if ($existing) {
            return $existing;
        }

        $keyEncrypted = Crypt::encryptString($keyDisplay);

        return self::create([
            'license_key'          => $keyEncrypted,
            'license_key_display'  => $keyDisplay,
            'duration_days'        => $durationDays,
            'status'               => 'pending',
            'organization_name'    => $organizationName,
            'grace_used'           => false,
            'features'             => [
                'users'           => true,
                'demandes'        => true,
                'watchlist'       => true,
                'reports'         => true,
                'api'             => true,
                'impetrants'      => true,
                'flux_migratoires'=> true,
            ],
        ]);
    }

    // ============================================================
    // ACTIVER UNE CLÉ (première utilisation sur le serveur)
    // ============================================================

    public function activate(string $deviceId, string $deviceName, string $deviceIp): array
    {
        if ($this->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'Cette clé a déjà été activée ou est invalide',
            ];
        }

        $expiresAt = now()->addDays($this->duration_days);

        $this->update([
            'status'            => 'active',
            'device_id'         => $deviceId,
            'device_name'       => $deviceName,
            'device_ip'         => $deviceIp,
            'activated_at'      => now(),
            'expires_at'        => $expiresAt,
            'last_validated_at' => now(),
        ]);

        $this->validations()->create([
            'ip_address' => $deviceIp,
            'action'     => 'activate',
            'success'    => true,
            'details'    => "Activée sur {$deviceName}",
        ]);

        return [
            'success'    => true,
            'message'    => 'Licence activée avec succès',
            'license'    => $this,
            'expires_at' => $expiresAt,
        ];
    }

    // ============================================================
    // RÉVOQUER
    // ============================================================

    public function revoke(?string $reason = null): bool
    {
        $this->update([
            'status' => 'revoked',
            'notes'  => $reason,
        ]);

        $this->validations()->create([
            'ip_address' => request()->ip() ?? 'cli',
            'action'     => 'revoke',
            'success'    => true,
            'details'    => $reason,
        ]);

        return true;
    }

    // ============================================================
    // PROLONGER
    // ============================================================

    public function extend(int $additionalDays = 30): array
    {
        $newExpiresAt = $this->expires_at->addDays($additionalDays);

        $this->update([
            'expires_at' => $newExpiresAt,
            'status'     => 'active',
        ]);

        return [
            'success'        => true,
            'new_expires_at' => $newExpiresAt,
        ];
    }

    // ============================================================
    // INFOS
    // ============================================================

    public function getInfo(): array
    {
        return [
            'key_display'      => $this->license_key_display,
            'organization'     => $this->organization_name,
            'device_name'      => $this->device_name,
            'device_id'        => $this->device_id ? substr($this->device_id, 0, 16) . '...' : 'N/A',
            'status'           => $this->status,
            'activated_at'     => $this->activated_at?->format('d/m/Y H:i'),
            'expires_at'       => $this->expires_at?->format('d/m/Y H:i'),
            'days_remaining'   => $this->expires_at ? max(0, (int) now()->diffInDays($this->expires_at, false)) : 0,
            'validation_count' => $this->validation_count,
            'grace_used'       => $this->grace_used,
            'features'         => $this->features,
        ];
    }
}
