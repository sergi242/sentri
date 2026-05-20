<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Impetrant extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    // ─── Utilitaires ──────────────────────────────────────────────

    public function nomcomplet(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }

    public static function buildUniqueString(array $data): string
    {
        return strtoupper(trim($data['nom']           ?? ''))
             . strtoupper(trim($data['prenom']        ?? ''))
             . strtoupper(trim($data['sexe']          ?? ''))
             . trim($data['date_naissance']            ?? '')
             . (string)($data['nationalites_id']      ?? '');
    }

    // ─── Relations demandes ───────────────────────────────────────

    public function demandes(): HasMany
    {
        return $this->hasMany(Demande::class, 'impetrants_id', 'id');
    }

    public function renouvellements(): bool
    {
        return $this->demandes()->count() > 1;
    }

    // ─── Relations référentiel ────────────────────────────────────

    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'nationalites_id', 'id');
    }

    public function nationalites(): HasMany
    {
        return $this->hasMany(ImpetrantNationalite::class, 'impetrant_id', 'id');
    }

    // ─── Relations casier / infractions / archives ────────────────

    public function casierNotes(): HasMany
    {
        return $this->hasMany(\App\Models\CasierNote::class);
    }

    public function infractions(): HasMany
    {
        return $this->hasMany(\App\Models\Infraction::class);
    }

    public function archives(): HasMany
    {
        return $this->hasMany(\App\Models\Archive::class)->orderByDesc('created_at');
    }

    // ─── Relations enregistrement direct ──────────────────────────

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // ─── Relations documents ──────────────────────────────────────

    public function documents(): HasMany
    {
        return $this->hasMany(ImpetrantDocument::class, 'impetrants_id')
                    ->orderByDesc('created_at');
    }

    public function dernierDocument(): HasOne
    {
        return $this->hasOne(ImpetrantDocument::class, 'impetrants_id')
                    ->latestOfMany();
    }
}
