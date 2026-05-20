<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImpetrantDocument extends Model
{
    use HasFactory;

    protected $table = 'impetrant_documents';

    protected $fillable = [
        'impetrants_id',
        'type_document',
        'numero_document',
        'date_delivrance',
        'date_expiration',
        'pays_delivrance_id',
        'mrz',
        'source',
        'created_by',
    ];

    protected $casts = [
        'date_delivrance' => 'date',
        'date_expiration' => 'date',
    ];

    // ─── Relations ────────────────────────────────────────────────

    public function impetrant()
    {
        return $this->belongsTo(Impetrant::class, 'impetrants_id');
    }

    public function paysDelivrance()
    {
        return $this->belongsTo(Pays::class, 'pays_delivrance_id');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeParNumero($query, string $numero)
    {
        return $query->where('numero_document', strtoupper(trim($numero)));
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    // ─── Accesseurs ───────────────────────────────────────────────

    public function getEstExpireAttribute(): bool
    {
        if (!$this->date_expiration) return false;
        return $this->date_expiration->isPast();
    }

    public function getStatutExpirationAttribute(): string
    {
        if (!$this->date_expiration) return 'Inconnue';
        if ($this->date_expiration->isPast())              return 'Expiré';
        if ($this->date_expiration->diffInDays() <= 90)    return 'Bientôt expiré';
        return 'Valide';
    }
}
