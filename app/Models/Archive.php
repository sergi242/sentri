<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $fillable = [
        'impetrant_id', 'user_id', 'type_document', 'libelle',
        'numero_document', 'date_emission', 'date_expiration',
        'chemin_fichier', 'nom_original', 'notes',
    ];

    protected $casts = [
        'date_emission'   => 'date',
        'date_expiration' => 'date',
    ];

    public function impetrant() { return $this->belongsTo(Impetrant::class); }
    public function user()      { return $this->belongsTo(User::class); }

    public function typeLabel(): string
    {
        return match($this->type_document) {
            'passeport'             => 'Passeport',
            'carte_consulaire'      => 'Carte consulaire',
            'visa'                  => 'Visa',
            'carte_resident'        => 'Carte de résident',
            'attestation_employeur' => "Attestation d'employeur",
            'contrat_bail'          => 'Contrat de bail',
            'visa_entree'           => "Visa d'entrée",
            'piece_identite'        => "Pièce d'identité",
            'autre'                 => $this->libelle ?? 'Autre',
            default                 => $this->type_document,
        };
    }

    public function estExpire(): bool
    {
        return $this->date_expiration && $this->date_expiration->isPast();
    }
}