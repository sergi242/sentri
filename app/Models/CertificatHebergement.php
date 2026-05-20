<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificatHebergement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'certificats_hebergement';

    protected $fillable = [
        'numero_certificat',
        'hebergeur_type',
        'hebergeur_id',
        'heberge_impetrant_id',
        'demande_id',
        'date_arrivee_prevue',
        'date_depart_prevue',
        'duree_sejour_jours',
        'motif_sejour',
        'type_relation',
        'precision_relation',
        'piece_identite_hebergeur',
        'piece_identite_heberge',
        'justificatif_domicile',
        'autres_documents',
        'statut',
        'date_emission',
        'date_expiration',
        'valide_par',
        'valide_le',
        'motif_rejet',
        'created_by',
    ];

    protected $casts = [
        'date_arrivee_prevue' => 'date',
        'date_depart_prevue'  => 'date',
        'date_emission'       => 'date',
        'date_expiration'     => 'date',
        'valide_le'           => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    /**
     * Retourne l'hébergeur selon son type
     * Congolais → Hebergeur | Etranger → Impetrant | Societe → Employeur
     */
    public function hebergeur()
    {
        return match($this->hebergeur_type) {
            'Congolais' => $this->belongsTo(Hebergeur::class,  'hebergeur_id'),
            'Etranger'  => $this->belongsTo(Impetrant::class,  'hebergeur_id'),
            'Societe'   => $this->belongsTo(Employeur::class,  'hebergeur_id'),
            default     => null,
        };
    }

    public function hebergeurCongolais()
    {
        return $this->belongsTo(Hebergeur::class, 'hebergeur_id');
    }

    public function hebergeurEtranger()
    {
        return $this->belongsTo(Impetrant::class, 'hebergeur_id');
    }

    public function hebergeurSociete()
    {
        return $this->belongsTo(Employeur::class, 'hebergeur_id');
    }

    public function heberge()
    {
        return $this->belongsTo(Impetrant::class, 'heberge_impetrant_id');
    }

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'demande_id');
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Accesseurs ─────────────────────────────────────────────────────────

    /**
     * Nom affiché de l'hébergeur selon son type
     */
    public function getNomHebergeurAttribute(): string
    {
        return match($this->hebergeur_type) {
            'Congolais' => $this->hebergeurCongolais
                ? strtoupper($this->hebergeurCongolais->nom) . ' ' . $this->hebergeurCongolais->prenom
                : '—',
            'Etranger'  => $this->hebergeurEtranger
                ? strtoupper($this->hebergeurEtranger->nom) . ' ' . $this->hebergeurEtranger->prenom
                : '—',
            'Societe'   => $this->hebergeurSociete?->nom_employeur ?? '—',
            default     => '—',
        };
    }

    /**
     * Code hébergeur selon le type
     */
    public function getCodeHebergeurAttribute(): string
    {
        return match($this->hebergeur_type) {
            'Congolais' => $this->hebergeurCongolais?->code_hebergeur ?? '—',
            'Etranger'  => $this->hebergeurEtranger?->code_hebergeur  ?? '—',
            'Societe'   => $this->hebergeurSociete?->code_hebergeur   ?? '—',
            default     => '—',
        };
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'En attente');
    }

    public function scopeValides($query)
    {
        return $query->where('statut', 'Validé');
    }

    public function scopeRejetes($query)
    {
        return $query->where('statut', 'Rejeté');
    }

    public function scopeExpires($query)
    {
        return $query->where('statut', 'Expiré');
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'Validé')
                     ->where('date_expiration', '>=', now());
    }

    // ── Génération du numéro de certificat ─────────────────────────────────

    public static function genererNumeroCertificat(): string
    {
        $date = now()->format('ymd'); // AAMMJJ
        $count = static::whereDate('created_at', today())->count() + 1;
        return 'CERT-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}