<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
    'user_id',
    'user_name',
    'user_role',
    'action',
    'action_label',
    'module',
    'entity_type',
    'entity_id',
    'old_values',
    'new_values',
    'status',
    'ip_address',
    'country',
    'city',
    'user_agent',
    'route',
    'method',
    'url',
];

    protected $casts = [
    'old_values' => 'array',
    'new_values' => 'array',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Libellé lisible de l'action
     */
    public function getActionLabelAttribute($value): string
    {
        if ($value) return $value;

        return match($this->method) {
            'POST'   => 'Création',
            'PUT', 'PATCH' => 'Modification',
            'DELETE' => 'Suppression',
            'GET'    => 'Consultation',
            default  => $this->method ?? 'Action',
        };
    }

    /**
     * Couleur badge selon l'action
     */
    public function getActionColorAttribute(): string
    {
        return match($this->method) {
            'POST'   => '#22c55e',
            'PUT', 'PATCH' => '#f59e0b',
            'DELETE' => '#ef4444',
            'GET'    => '#3b82f6',
            default  => '#6b7280',
        };
    }

    /**
     * Icône selon le module
     */
    public function getModuleIconAttribute(): string
    {
        return match($this->module) {
            'demandes'     => 'la-file-alt',
            'impetrants'   => 'la-user',
            'flux'         => 'la-exchange-alt',
            'frontieres'   => 'la-map-marker',
            'watchlist'    => 'la-exclamation-triangle',
            'archivage', 'archives' => 'la-archive',
            'soit-transmis' => 'la-paper-plane',
            'reporting'    => 'la-chart-bar',
            'users'        => 'la-users',
            'roles'        => 'la-shield-alt',
            'employeurs'   => 'la-building',
            default        => 'la-circle',
        };
    }

    /**
     * Libellé module lisible
     */
    public function getModuleLabelAttribute(): string
    {
        return match($this->module) {
            'demandes'      => 'Demandes',
            'impetrants'    => 'Impétrants',
            'flux'          => 'Flux Migratoire',
            'frontieres'    => 'Frontières',
            'watchlist'     => 'Watchlist',
            'archives'      => 'Archivage',
            'soit-transmis' => 'Soit-Transmis',
            'reporting'     => 'Reporting',
            'users'         => 'Utilisateurs',
            'roles'         => 'Rôles',
            'employeurs'    => 'Employeurs',
            'authenticate'  => 'Authentification',
            default         => ucfirst($this->module ?? 'Système'),
        };
    }
}