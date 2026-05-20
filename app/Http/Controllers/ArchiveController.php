<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Impetrant;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    // Liste des impétrants avec recherche
public function index(Request $request)
{
    $search           = $request->get('search');
    $pays_id          = $request->get('pays_id');
    $avec_docs        = $request->get('avec_docs');
    $numero_document  = $request->get('numero_document'); // ← ajouter

   $impetrants = Impetrant::withCount('archives')
    ->with([
        'pays',
        'demandes' => fn($q) => $q->orderBy('created_at', 'desc') // ← sans whereNotNull ni limit
    ])
    ->when($search, fn($q) =>
        $q->where('nom', 'like', "%$search%")
          ->orWhere('prenom', 'like', "%$search%")
    )
    ->when($pays_id, fn($q) => $q->where('nationalites_id', $pays_id))
    ->when($avec_docs === '1', fn($q) => $q->has('archives'))
    ->when($avec_docs === '0', fn($q) => $q->doesntHave('archives'))
    ->when($numero_document, fn($q) =>
        $q->whereHas('archives', fn($a) =>
            $a->where('numero_document', 'like', "%$numero_document%")
        )
    )
    ->orderBy('nom')
    ->paginate(10);
    $pays = \App\Models\Pays::orderBy('lib_pays')->get();

    return view('admin.archives.index', compact('impetrants', 'pays', 'search'));
}

    // Fiche archivage d'un impétrant
    public function show($id)
    {
        $impetrant = Impetrant::with([
            'archives.user',
            'demandes' => fn($q) => $q->latest()->limit(1)
        ])->findOrFail($id);

        $archives = $impetrant->archives;

        $typesDisponibles = [
            'passeport'             => 'Passeport',
            'carte_consulaire'      => 'Carte consulaire',
            'visa'                  => 'Visa',
            'carte_resident'        => 'Carte de résident',
            'attestation_employeur' => "Attestation d'employeur",
            'contrat_bail'          => 'Contrat de bail',
            'visa_entree'           => "Visa d'entrée",
            'piece_identite'        => "Pièce d'identité",
            'autre'                 => 'Autre',
        ];

        return view('admin.archives.show', compact('impetrant', 'archives', 'typesDisponibles'));
    }

    // Enregistrer un document
    public function store(Request $request, $id)
    {
        $request->validate([
            'type_document'   => 'required|string',
            'fichier'         => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:8192',
            'numero_document' => 'nullable|string|max:100',
            'date_emission'   => 'nullable|date',
            'date_expiration' => 'nullable|date',
            'libelle'         => 'nullable|string|max:200',
            'notes'           => 'nullable|string|max:1000',
        ]);

        $file   = $request->file('fichier');
        $chemin = $file->store('archives/'.$id, 'public');

        Archive::create([
            'impetrant_id'    => $id,
            'user_id'         => auth()->id(),
            'type_document'   => $request->type_document,
            'libelle'         => $request->libelle,
            'numero_document' => $request->numero_document,
            'date_emission'   => $request->date_emission,
            'date_expiration' => $request->date_expiration,
            'chemin_fichier'  => $chemin,
            'nom_original'    => $file->getClientOriginalName(),
            'notes'           => $request->notes,
        ]);

        toastr()->success('Document archivé avec succès');
        return back();
    }

    // Supprimer un document
    public function destroy($id)
    {
        $archive = Archive::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($archive->chemin_fichier);
        $archive->delete();

        toastr()->success('Document supprimé');
        return back();
    }

    // Imprimer / voir le document
    public function print($id)
    {
        $archive   = Archive::with('impetrant')->findOrFail($id);
        $impetrant = $archive->impetrant;

        return view('admin.archives.print', compact('archive', 'impetrant'));
    }
}