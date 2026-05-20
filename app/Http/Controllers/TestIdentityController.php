<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Impetrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TechnoDev\src\Facades\TechnoDev;

class TestIdentityController extends Controller
{
    public function index()
    {
        $demandes = Demande::with('impetrant')
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        return view('admin.tests.identity', compact('demandes'));
    }

    public function duplicate(Request $request, Demande $demande)
{
    DB::beginTransaction();

    try {
        $originalImp = $demande->impetrant;

        /* ============================
         * 1. DUPLICATION IMPÉTRANT
         * ============================ */
        $cloneImp = $originalImp->replicate();

        // Modifications volontaires (tests)
        if ($request->boolean('change_prenom')) {
            $cloneImp->prenom = $cloneImp->prenom . ' TEST';
        }

        if ($request->boolean('change_dob')) {
            $cloneImp->date_naissance = now()->subYears(1)->format('Y-m-d');
        }

        if ($request->boolean('change_sexe')) {
            $cloneImp->sexe = $cloneImp->sexe === 'Masculin' ? 'Féminin' : 'Masculin';
        }

   

$cloneImp->unique_string = TechnoDev::impetrantUniqueString($cloneImp);
$cloneImp->save();


        /* ============================
         * 2. DUPLICATION DEMANDE
         * ============================ */
        $cloneDemande = $demande->replicate();
        $cloneDemande->impetrants_id = $cloneImp->id;
        $cloneDemande->uuid = \Str::uuid();
        $cloneDemande->statut_demande = 'En attente d\'approbation';
        $cloneDemande->created_at = now();
        $cloneDemande->save();

        /* ============================
         * 3. DUPLICATION PASSEPORT
         * ============================ */
        foreach ($demande->documents as $doc) {
            $newDoc = $doc->replicate();

            if ($doc->type_document === 'Passeport' && $request->boolean('change_passport')) {
                $newDoc->numero_document = 'PTEST' . rand(100000, 999999);
            }

            $newDoc->demandes_id = $cloneDemande->id;
            $newDoc->save();
        }

        DB::commit();

        return back()->with('success', 'Duplication complète effectuée');

    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
}

}
