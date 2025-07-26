<?php

namespace App\Models;

use App\Models\Pays;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Demande extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the impetrant that owns the Demande
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function impetrant(): BelongsTo
    {
        return $this->belongsTo(Impetrant::class, 'impetrants_id', 'id');
    }

    /**
     * Get all of the passeports for the Demande
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(DocumentDemande::class, 'demandes_id', 'id');
    }

    public function passeport(){
        return $this->documents->where("type_document","Passeport")->first();
    }

    public function carteconsulaire(){
        return $this->documents->where("type_document","Carte consulaire")->first();
    }

    /**
     * Get the quartier that owns the Demande
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quartier(): BelongsTo
    {
        return $this->belongsTo(Quartier::class, 'quartiers_id', 'id');
    }

    /**
     * The pieces that belong to the Demande
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pieces(): BelongsToMany
    {
        return $this->belongsToMany(Justificatif::class, 'demandes_pieces', 'demandes_id', 'pieces_id');
    }

    /**
     * Get the categorieProfessionnelle that owns the Demande
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categorieProfessionnelle(): BelongsTo
    {
        return $this->belongsTo(CategorieSocioProfessionnelle::class, 'categorie_socioprof_id', 'id');
    }

    /**
     * Get the employeur that owns the Demande
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employeur(): BelongsTo
    {
        return $this->belongsTo(Employeur::class,"employeur_id", "id");
    }


    public function soitTransmis()
    {
        return $this->belongsTo(SoitTransmis::class);
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contentieux (){
        return $this->hasMany(Contentieux::class, 'demandes_id', 'id');
    }

    public function fiches()
    {
        return $this->hasMany(FicheDemande::class, 'demande_id', 'id');
    }
}
