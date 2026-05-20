<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Demande;
use App\Models\Impetrant;

class DemandeMergeController extends Controller
{
    public function merge(Request $request)
    {
        $request->validate([
            'base_id'    => 'required|integer|exists:demandes,id',
            'similar_id' => 'required|integer|exists:demandes,id',
            'merge'      => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            /* ===============================
             * 1️⃣ CHARGEMENT DES DEMANDES
             * =============================== */
            $base    = Demande::with('impetrant')->findOrFail($request->base_id);
            $similar = Demande::with('impetrant')->findOrFail($request->similar_id);

            if ($base->id === $similar->id) {
                throw new \Exception("Impossible de fusionner une demande avec elle-même.");
            }

            $baseImpetrant    = $base->impetrant;
            $similarImpetrant = $similar->impetrant;

            if (!$baseImpetrant || !$similarImpetrant) {
                throw new \Exception("Impétrant introuvable.");
            }

            $data = $request->merge;

            /* ===============================
             * 2️⃣ FUSION DES DONNÉES IMPÉTRANT
             * =============================== */
            $impetrantFields = [
                'nom',
                'prenom',
                'sexe',
                'date_naissance',
                'lieu_naissance',
                'nom_pere',
                'prenom_pere',
                'nom_mere',
                'prenom_mere',
            ];

            foreach ($impetrantFields as $field) {
                if (!empty($data[$field])) {
                    $baseImpetrant->$field = $data[$field];
                }
            }

            $baseImpetrant->save();

            /* ===============================
             * 3️⃣ RATTACHEMENT DES DEMANDES
             * ===============================
             * 👉 AUCUNE DEMANDE SUPPRIMÉE
             * 👉 ON CHANGE UNIQUEMENT L’IMPÉTRANT
             */

            Demande::where('impetrants_id', $similarImpetrant->id)
                ->update([
                    'impetrants_id' => $baseImpetrant->id
                ]);

            /* ===============================
             * 4️⃣ SUPPRESSION DE L’IMPÉTRANT DOUBLON
             * =============================== */
            $similarImpetrant->delete();
        });

        return redirect()
            ->route('demandes.index')
            ->with('success', 'Fusion des impétrants effectuée. Toutes les demandes ont été conservées.');
    }
}
