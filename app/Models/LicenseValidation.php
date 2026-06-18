<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseValidation extends Model
{
    protected $connection = 'vault';

    protected $fillable = ['license_id', 'ip_address', 'user_agent', 'action', 'success', 'details'];
    
    public $timestamps = false;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
