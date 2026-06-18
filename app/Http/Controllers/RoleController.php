<?php

namespace App\Http\Controllers;

use App\Models\Fonctionnalite;
use App\Models\Module;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * ID du rôle SuperAdmin — intouchable par tout le monde.
     */
    const SUPERADMIN_ROLE_ID = 1;

    /**
     * Modules réservés aux SuperAdmin uniquement.
     * Aucun autre rôle ne peut recevoir ces permissions.
     *
     * 5  = Configuration
     * 9  = Gestion des Rôles et Permissions
     * 12 = Monitoring et Audit
     */
    const SUPERADMIN_ONLY_MODULES = [5, 9, 12];

    // ─────────────────────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────────────────────

    /** L'utilisateur connecté est-il SuperAdmin ? */
    private function isSuperAdmin(): bool
    {
        $user = Auth::user();
        return $user && $user->role && $user->role->lib_role === 'SuperAdmin';
    }

    /** Un rôle donné est-il le rôle SuperAdmin protégé ? */
    private function isSuperAdminRole(Role $role): bool
    {
        return $role->id === self::SUPERADMIN_ROLE_ID;
    }

    // ─────────────────────────────────────────────────────────────
    //  CRUD
    // ─────────────────────────────────────────────────────────────

    public function index()
    {
        $roles = Role::all();
        return view('admin.role.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lib_role' => 'required|string|unique:roles',
        ]);

        try {
            $role = new Role;
            $role->lib_role = $request->lib_role;
            $role->save();
            toastr()->success('Rôle ajouté avec succès');
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function create()
    {
        //
    }

    public function edit($id)
    {
        $role = Role::find($id);

        if ($role === null) {
            toastr()->error('Impossible de traiter cette requête');
            return back();
        }

        // ── Protection : le rôle SuperAdmin est en lecture seule ──
        if ($this->isSuperAdminRole($role)) {
            toastr()->warning('Le rôle SuperAdmin ne peut pas être modifié.');
            return redirect()->route('role.index');
        }

        $modules              = Module::orderBy('id')->get();
        $fonctionnalitesByModule = Fonctionnalite::with('module')
            ->orderBy('modules_id')
            ->orderBy('lib_fonctionnalite')
            ->get()
            ->groupBy('modules_id');

        $isSuperAdmin            = $this->isSuperAdmin();
        $superAdminOnlyModules   = self::SUPERADMIN_ONLY_MODULES;

        return view('admin.role.edit', compact(
            'role',
            'modules',
            'fonctionnalitesByModule',
            'isSuperAdmin',
            'superAdminOnlyModules'
        ));
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if ($role === null) {
            toastr()->error('Impossible de traiter cette requête');
            return back();
        }

        // ── Protection : le rôle SuperAdmin est intouchable ──
        if ($this->isSuperAdminRole($role)) {
            toastr()->error('Le rôle SuperAdmin ne peut pas être modifié.');
            return redirect()->route('role.index');
        }

        $request->validate([
            'lib_role' => 'required|string',
        ]);

        try {
            $role->lib_role = $request->lib_role;
            $role->save();

            $fonctionnalites = $request->fonctionnalites ?? [];

            // ── Filtrage : un non-SuperAdmin ne peut pas attribuer
            //    les permissions des modules réservés ──────────────
            if (!$this->isSuperAdmin()) {

                // Récupérer les IDs des fonctionnalités des modules protégés
                // déjà assignées à ce rôle — on les préserve telles quelles
                $protectedFonctionnaliteIds = Fonctionnalite::whereIn(
                    'modules_id',
                    self::SUPERADMIN_ONLY_MODULES
                )->pluck('id')->toArray();

                $currentProtected = $role->fonctionnalites()
                    ->whereIn('fonctionnalites_id', $protectedFonctionnaliteIds)
                    ->pluck('fonctionnalites_id')
                    ->toArray();

                // Garder uniquement les perms hors modules protégés
                // soumises par le formulaire + les perms protégées existantes
                $fonctionnalites = array_merge(
                    array_diff($fonctionnalites, $protectedFonctionnaliteIds),
                    $currentProtected
                );
            }

            $role->fonctionnalites()->sync($fonctionnalites);

            toastr()->success('Rôle modifié avec succès');
            return back();

        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if ($role === null) {
            toastr()->error('Impossible de traiter cette requête');
            return back();
        }

        // ── Protection : le rôle SuperAdmin ne peut pas être supprimé ──
        if ($this->isSuperAdminRole($role)) {
            toastr()->error('Le rôle SuperAdmin ne peut pas être supprimé.');
            return back();
        }

        $role->delete();
        toastr()->success('Rôle supprimé avec succès');
        return back();
    }
}