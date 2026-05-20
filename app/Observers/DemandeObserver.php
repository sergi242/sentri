<?php

namespace App\Observers;

use App\Models\Demande;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class DemandeObserver
{
    public function created(Demande $demande)
    {
        $this->log('create', null, $demande->toArray(), $demande);
    }

    public function updated(Demande $demande)
    {
        $changes = $demande->getChanges();
        $original = array_intersect_key(
            $demande->getOriginal(),
            $changes
        );

        if (empty($changes)) return;

        $this->log('update', $original, $changes, $demande);
    }

    public function deleted(Demande $demande)
    {
        $this->log('delete', $demande->toArray(), null, $demande);
    }

    public function restored(Demande $demande)
    {
        $this->log('restore', null, $demande->toArray(), $demande);
    }

    private function log(string $action, $old, $new, Demande $demande)
    {
        $user = Auth::user();

        AuditLog::create([
            'user_id'     => $user?->id,
            'user_name'   => $user ? $user->prenom.' '.$user->nom : 'System',
            'user_role'   => $user?->role?->lib_role ?? null,

            'action'      => $action,
            'module'      => 'demandes',

            'entity_type' => 'Demande',
            'entity_id'   => $demande->id,

            'old_values'  => $old,
            'new_values'  => $new,

            'status'      => 'success',

            'ip_address'  => request()->ip(),
            'user_agent'  => substr(request()->userAgent(), 0, 255),
            'route'       => optional(request()->route())->getName(),
            'method'      => request()->method(),
        ]);
    }
}
