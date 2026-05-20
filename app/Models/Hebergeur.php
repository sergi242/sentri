<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hebergeur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hebergeurs';

    protected $fillable = [
        'code_hebergeur',
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'telephone',
        'email',
        'quartiers_id',
        'avenue_rue',
        'numero_adresse',
        'type_piece',
        'numero_piece',
        'date_emission_piece',
        'date_expiration_piece',
        'profession',
        'photo',
        'created_by',
    ];

    protected $casts = [
        'date_naissance'       => 'date',
        'date_emission_piece'  => 'date',
        'date_expiration_piece'=> 'date',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function quartier()
    {
        return $this->belongsTo(Quartier::class, 'quartiers_id');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function certificats()
    {
        return $this->hasMany(CertificatHebergement::class, 'hebergeur_id')
                    ->where('hebergeur_type', 'Congolais');
    }

    // ── Accesseurs ─────────────────────────────────────────────────────────

    public function getNomPrenomAttribute(): string
    {
        return strtoupper($this->nom) . ' ' . ucfirst(strtolower($this->prenom));
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeRecherche($query, string $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'like', "%{$terme}%")
              ->orWhere('prenom', 'like', "%{$terme}%")
              ->orWhere('telephone', 'like', "%{$terme}%")
              ->orWhere('code_hebergeur', 'like', "%{$terme}%");
        });
    }

    // ── Génération du code hébergeur ───────────────────────────────────────

    public static function genererCode(): string
    {
        $date = now()->format('ymd'); // AAMMJJ

        // Compter les hébergeurs créés aujourd'hui (toutes tables confondues)
        $countCongolais  = static::whereDate('created_at', today())->count();
        $countEtranger   = Impetrant::whereDate('created_at', today())->where('est_hebergeur', 1)->count();
        $countSociete    = Employeur::whereDate('created_at', today())->where('est_hebergeur', 1)->count();
        $total           = $countCongolais + $countEtranger + $countSociete + 1;

        return 'HEB-' . $date . '-' . str_pad($total, 5, '0', STR_PAD_LEFT);
    }
}