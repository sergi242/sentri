<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infraction extends Model
{
    protected $fillable = [
        'impetrant_id', 'demande_id', 'user_id',
        'type', 'gravite', 'statut', 'motif',
        'date_infraction', 'auto_generee',
    ];

    protected $casts = [
        'date_infraction' => 'date',
        'auto_generee'    => 'boolean',
    ];

    public function impetrant() { return $this->belongsTo(Impetrant::class); }
    public function demande()   { return $this->belongsTo(Demande::class); }
    public function user()      { return $this->belongsTo(User::class); }

    // Labels lisibles
    public function typeLabel(): string
    {
        return match($this->type) {
            'expiration_sans_renouvellement' => 'Expiration sans renouvellement',
            'demande_expiree_sans_suite'      => 'Demande expirée sans suite',
            'contentieux'                    => 'Passage au contentieux',
            'manuelle'                       => 'Infraction manuelle',
            default                          => $this->type,
        };
    }

    public function graviteLabel(): string
    {
        return match($this->gravite) {
            'mineur' => 'Mineur',
            'moyen'  => 'Moyen',
            'grave'  => 'Grave',
            default  => $this->gravite,
        };
    }

    public function statutLabel(): string
    {
        return match($this->statut) {
            'en_cours' => 'En cours',
            'resolu'   => 'Résolu',
            'classe'   => 'Classé',
            default    => $this->statut,
        };
    }

    // Poids pour le score
    public function poids(): int
    {
        return match($this->gravite) {
            'grave'  => 30,
            'moyen'  => 15,
            'mineur' => 5,
            default  => 5,
        };
    }
    public function preuves()
{
    return $this->hasMany(InfractionPreuve::class);
}
}