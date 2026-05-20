@extends('admin.layouts.app')

@section('title', 'Fiche impétrant — ' . strtoupper($impetrant->nom) . ' ' . $impetrant->prenom)

@section('styles')
<style>
:root {
    --blue:   #1E9FF2;
    --green:  #28D094;
    --orange: #FF9149;
    --red:    #FF4961;
    --dark:   #2c3e50;
    --muted:  #8898aa;
    --border: #e4e8ef;
    --radius: 10px;
}

/* ── Labels / valeurs ────────────────────────────────────── */
.lbl { font-size:10px; text-transform:uppercase; color:var(--muted); font-weight:700; letter-spacing:.6px; margin-bottom:2px; }
.val { font-size:13.5px; font-weight:500; color:var(--dark); }

/* ── Photo box ───────────────────────────────────────────── */
.photo-box {
    width:120px; height:150px; border-radius:10px; overflow:hidden;
    border:3px solid var(--border); background:#f8f9fa;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.photo-box img { width:100%; height:100%; object-fit:cover; }
.photo-box .no-photo { text-align:center; color:#ced4da; }

/* ── Badges source ───────────────────────────────────────── */
.badge-direct  { background:#e8f4fd; color:#1E9FF2; border:1px solid #b8dff9; }
.badge-demande { background:#e6f9f1; color:#28D094; border:1px solid #b4eeda; }

/* ── Cards ───────────────────────────────────────────────── */
.info-card {
    background:#fff; border-radius:var(--radius); border:1px solid var(--border);
    box-shadow:0 2px 8px rgba(0,0,0,.04); margin-bottom:16px; overflow:hidden;
}
.info-card .card-head {
    padding:10px 16px; background:#f8fafc;
    border-bottom:1px solid var(--border);
    font-size:10px; font-weight:800; text-transform:uppercase;
    letter-spacing:.07em; color:var(--blue);
    display:flex; align-items:center; gap:6px;
}
.info-card .card-body-inner { padding:14px 16px; }

/* ── Tableau demandes ────────────────────────────────────── */
.dem-row { cursor:pointer; transition:background .15s; }
.dem-row:hover { background:#f5f9ff; }
.dem-row td { vertical-align:middle; font-size:12.5px; padding:10px 14px; border-bottom:1px solid #f5f7fa; }
.dem-row:last-child td { border-bottom:none; }

/* ── Tableau documents ───────────────────────────────────── */
.doc-row td { vertical-align:middle; font-size:12px; padding:9px 14px; border-bottom:1px solid #f5f7fa; }
.doc-row:last-child td { border-bottom:none; }
.doc-num { font-weight:700; color:var(--blue); }
.exp-ok   { color:var(--green); font-weight:600; }
.exp-warn { color:var(--orange); font-weight:600; }
.exp-bad  { color:var(--red); font-weight:700; }

/* ── Stat mini ───────────────────────────────────────────── */
.mini-stat {
    background:#f7f9fc; border-radius:9px; padding:12px 14px;
    border:1px solid var(--border); text-align:center;
}
.mini-stat .num { font-size:1.5rem; font-weight:800; line-height:1; }
.mini-stat .desc { font-size:10px; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-top:3px; }

/* ── Bouton action principal ─────────────────────────────── */
.btn-action-main {
    background:linear-gradient(135deg,var(--blue),#0d7fd4);
    color:#fff; border:none; border-radius:8px;
    padding:9px 18px; font-weight:700; font-size:13px;
    display:inline-flex; align-items:center; gap:7px;
    transition:opacity .2s, box-shadow .2s; text-decoration:none;
}
.btn-action-main:hover {
    opacity:.92; box-shadow:0 4px 14px rgba(30,159,242,.4);
    color:#fff; text-decoration:none;
}
</style>
@endsection

@section('content')
<div class="container-fluid">

    {{-- ── Breadcrumb ─────────────────────────────────────────── --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent px-0 py-1 mb-0" style="font-size:12px;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('impetrants.index') }}">Impétrants</a></li>
            <li class="breadcrumb-item active">{{ strtoupper($impetrant->nom) }} {{ $impetrant->prenom }}</li>
        </ol>
    </nav>

    {{-- ══════════════════════════════════════════════════════════
         HEADER FICHE
    ══════════════════════════════════════════════════════════ --}}
    <div class="info-card mb-4">
        <div class="card-body-inner">
            <div class="d-flex align-items-start flex-wrap" style="gap:20px;">

                {{-- Photo --}}
                <div class="photo-box">
                    @if($impetrant->photo && file_exists(public_path('app/'.$impetrant->photo)))
                        <img src="{{ asset('app/'.$impetrant->photo) }}" alt="{{ $impetrant->nom_complet }}">
                    @else
                        <div class="no-photo">
                            <i class="la la-user" style="font-size:42px;"></i>
                            <div style="font-size:10px;margin-top:4px;">Aucune photo</div>
                        </div>
                    @endif
                </div>

                {{-- Identité --}}
                <div class="flex-grow-1">

                    {{-- Nom + badges --}}
                    <div class="d-flex align-items-center flex-wrap mb-3" style="gap:8px;">
                        <h4 class="mb-0 font-weight-bold" style="color:var(--dark);">
                            {{ $impetrant->nom_complet }}
                        </h4>
                        @if($impetrant->nationalite)
                            @php $code = strtolower($impetrant->nationalite->code ?? ''); @endphp
                            @if(file_exists(public_path("res/flags/{$code}.png")))
                                <img src="{{ asset("res/flags/{$code}.png") }}" height="17"
                                     title="{{ $impetrant->nationalite->libelle }}"
                                     style="border-radius:2px;">
                            @endif
                            <span class="badge badge-light" style="font-size:11px;">
                                {{ $impetrant->nationalite->libelle }}
                            </span>
                        @endif
                        <span class="badge badge-{{ $impetrant->source === 'direct' ? 'direct' : 'demande' }}"
                              style="font-size:10px;">
                            {{ $impetrant->source === 'direct' ? 'Enregistrement direct' : 'Via demande' }}
                        </span>
                        <span class="badge badge-secondary" style="font-size:10px;">#{{ $impetrant->id }}</span>
                    </div>

                    {{-- État civil --}}
                    <div class="row mb-2">
                        <div class="col-6 col-md-2 mb-2">
                            <div class="lbl">Sexe</div>
                            <div class="val">{{ $impetrant->sexe }}</div>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="lbl">Date de naissance</div>
                            <div class="val">
                                {{ $impetrant->date_naissance?->format('d/m/Y') ?? '—' }}
                                @if($impetrant->age)
                                    <small class="text-muted">({{ $impetrant->age }} ans)</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="lbl">Lieu de naissance</div>
                            <div class="val">{{ $impetrant->lieu_naissance ?? '—' }}</div>
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <div class="lbl">Téléphone</div>
                            <div class="val">{{ $impetrant->telephone ?? '—' }}</div>
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <div class="lbl">Profession</div>
                            <div class="val">{{ $impetrant->profession ?? '—' }}</div>
                        </div>
                    </div>

                    {{-- Filiation --}}
                    <div class="row mb-2">
                        <div class="col-6 col-md-3 mb-1">
                            <div class="lbl">Nom du père</div>
                            <div class="val">{{ $impetrant->nom_pere ?: '—' }}</div>
                        </div>
                        <div class="col-6 col-md-3 mb-1">
                            <div class="lbl">Prénom du père</div>
                            <div class="val">{{ $impetrant->prenom_pere ?: '—' }}</div>
                        </div>
                        <div class="col-6 col-md-3 mb-1">
                            <div class="lbl">Nom de la mère</div>
                            <div class="val">{{ $impetrant->nom_mere ?: '—' }}</div>
                        </div>
                        <div class="col-6 col-md-3 mb-1">
                            <div class="lbl">Prénom de la mère</div>
                            <div class="val">{{ $impetrant->prenom_mere ?: '—' }}</div>
                        </div>
                    </div>

                    {{-- Enregistrement --}}
                    <div class="d-flex flex-wrap" style="gap:20px;">
                        <div>
                            <div class="lbl">Enregistré par</div>
                            <div class="val">{{ $impetrant->createdBy?->nom ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="lbl">Date enregistrement</div>
                            <div class="val">
                                {{ $impetrant->created_at?->addHour()->format('d/m/Y H:i') ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="d-flex flex-column" style="gap:7px; min-width:165px;">
                    @can('demandes.create')
                    <a href="{{ route('demandes.create', ['impetrant_id' => $impetrant->id]) }}"
                       class="btn-action-main">
                        <i class="la la-file-alt"></i> Créer une demande
                    </a>
                    @endcan
                    @can('impetrants.edit')
                    <a href="{{ route('impetrants.edit', $impetrant->id) }}"
                       class="btn btn-outline-primary btn-sm" style="border-radius:7px;">
                        <i class="la la-edit mr-1"></i> Modifier
                    </a>
                    @endcan
                    <a href="{{ route('impetrants.casier', $impetrant->id) }}"
                       class="btn btn-outline-secondary btn-sm" style="border-radius:7px;">
                        <i class="la la-history mr-1"></i> Casier judiciaire
                    </a>
                    <a href="{{ route('impetrants.index') }}"
                       class="btn btn-light btn-sm" style="border-radius:7px;">
                        <i class="la la-arrow-left mr-1"></i> Retour liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MINI STATS
    ══════════════════════════════════════════════════════════ --}}
    <div class="row mb-4">
        @php
            $nbDemandes  = $impetrant->demandes->count();
            $nbDocs      = $impetrant->documents->count();
            $nbApprouves = $impetrant->demandes->where('statut','Approuvée')->count();
            $nbExpDocs   = $impetrant->documents->filter(fn($d) =>
                $d->date_expiration && \Carbon\Carbon::parse($d->date_expiration)->isPast()
            )->count();
        @endphp
        <div class="col-6 col-md-3 mb-3">
            <div class="mini-stat">
                <div class="num text-primary">{{ $nbDemandes }}</div>
                <div class="desc">Demande(s)</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="mini-stat">
                <div class="num" style="color:var(--green);">{{ $nbDocs }}</div>
                <div class="desc">Document(s)</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="mini-stat">
                <div class="num" style="color:var(--orange);">{{ $nbApprouves }}</div>
                <div class="desc">Approuvée(s)</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="mini-stat">
                <div class="num" style="color:var(--red);">{{ $nbExpDocs }}</div>
                <div class="desc">Doc. expiré(s)</div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- ── Colonne gauche ──────────────────────────────────── --}}
        <div class="col-md-4">

            {{-- Adresse --}}
            <div class="info-card">
                <div class="card-head">
                    <i class="la la-map-marker"></i> Adresse au Congo
                </div>
                <div class="card-body-inner">
                    <div class="mb-2"><div class="lbl">Adresse</div><div class="val">{{ $impetrant->adresse ?? '—' }}</div></div>
                    <div class="mb-2"><div class="lbl">Département</div><div class="val">{{ $impetrant->departement?->libelle ?? '—' }}</div></div>
                    <div class="mb-2"><div class="lbl">Arrondissement</div><div class="val">{{ $impetrant->arrondissement?->libelle ?? '—' }}</div></div>
                    <div><div class="lbl">Quartier</div><div class="val">{{ $impetrant->quartier?->libelle ?? '—' }}</div></div>
                </div>
            </div>

            {{-- Photo principale --}}
            <div class="info-card">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <span><i class="la la-camera"></i> Photo d'identité</span>
                    <span class="badge badge-primary" style="font-size:9px;">Photo principale</span>
                </div>
                <div class="card-body-inner text-center">
                    @if($impetrant->photo && file_exists(public_path('app/'.$impetrant->photo)))
                        <img src="{{ asset('app/'.$impetrant->photo) }}"
                             style="width:90px;height:110px;object-fit:cover;
                                    border-radius:8px;border:2px solid var(--blue);">
                    @else
                        <div style="width:90px;height:110px;border-radius:8px;
                                    background:#f0f0f0;display:inline-flex;
                                    align-items:center;justify-content:center;
                                    border:2px dashed var(--border);">
                            <i class="la la-user" style="font-size:2.5rem;color:#ced4da;"></i>
                        </div>
                    @endif
                    <p class="text-muted mt-2 mb-0" style="font-size:11px;">
                        <i class="la la-info-circle"></i>
                        Apparaît par défaut sur toutes les demandes.
                    </p>
                </div>
            </div>

            {{-- Documents --}}
            <div class="info-card">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <span><i class="la la-id-card"></i> Documents enregistrés</span>
                    <button type="button" class="btn btn-sm btn-primary"
                            id="btn-ouvrir-modal-doc"
                            style="border-radius:6px; font-size:11px; padding:3px 10px;">
                        <i class="la la-plus mr-1"></i> Ajouter
                    </button>
                </div>
                <div class="card-body-inner p-0">
                    @if($impetrant->documents->isEmpty())
                        <div class="text-center py-3 text-muted" style="font-size:12px;">
                            <i class="la la-id-card" style="font-size:1.8rem;"></i>
                            <p class="mt-1 mb-0">Aucun document enregistré.</p>
                        </div>
                    @else
                        <table class="table table-sm mb-0">
                            <thead style="background:#f8fafc;">
                                <tr>
                                    <th style="font-size:10px;padding:8px 12px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);">Type</th>
                                    <th style="font-size:10px;padding:8px 12px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);">Numéro</th>
                                    <th style="font-size:10px;padding:8px 12px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);">Expiration</th>
                                    <th style="font-size:10px;padding:8px 12px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);">Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($impetrant->documents->sortByDesc('created_at') as $doc)
                                @php
                                    $exp = $doc->date_expiration
                                        ? \Carbon\Carbon::parse($doc->date_expiration) : null;
                                    $expClass = !$exp ? '' : ($exp->isPast() ? 'exp-bad'
                                        : ($exp->diffInDays(now()) < 90 ? 'exp-warn' : 'exp-ok'));
                                @endphp
                                <tr class="doc-row">
                                    <td>{{ $doc->type_document ?? '—' }}</td>
                                    <td><span class="doc-num">{{ $doc->numero_document }}</span></td>
                                    <td>
                                        @if($exp)
                                            <span class="{{ $expClass }}">
                                                {{ $exp->format('d/m/Y') }}
                                                @if($exp->isPast())
                                                    <i class="la la-exclamation-triangle"></i>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $doc->source === 'lecteur' ? 'badge-success' : 'badge-secondary' }}"
                                              style="font-size:9px;">
                                            {{ ucfirst($doc->source ?? 'manuel') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Colonne droite : demandes ────────────────────────── --}}
        <div class="col-md-8">
            <div class="info-card">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <span>
                        <i class="la la-file-alt"></i> Toutes les demandes
                        <span class="badge badge-primary ml-1" style="font-size:10px;">
                            {{ $impetrant->demandes->count() }}
                        </span>
                    </span>
                    @can('demandes.create')
                    <a href="{{ route('demandes.create', ['impetrant_id' => $impetrant->id]) }}"
                       class="btn btn-sm btn-success"
                       style="border-radius:6px; font-size:11px; padding:3px 10px;">
                        <i class="la la-plus mr-1"></i> Nouvelle demande
                    </a>
                    @endcan
                </div>
                <div class="card-body-inner p-0">
                    @if($impetrant->demandes->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="la la-folder-open" style="font-size:3rem;"></i>
                            <p class="mt-2 mb-3" style="font-size:13px;">
                                Aucune demande enregistrée pour cet impétrant.
                            </p>
                            @can('demandes.create')
                            <a href="{{ route('demandes.create', ['impetrant_id' => $impetrant->id]) }}"
                               class="btn btn-sm btn-success" style="border-radius:8px;">
                                <i class="la la-plus mr-1"></i> Créer la première demande
                            </a>
                            @endcan
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead style="background:#f8fafc;">
                                    <tr>
                                        <th style="font-size:10px;padding:10px 14px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);width:46px;">Photo</th>
                                        <th style="font-size:10px;padding:10px 14px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);">Date</th>
                                        <th style="font-size:10px;padding:10px 14px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);">Type</th>
                                        <th style="font-size:10px;padding:10px 14px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);">Référence</th>
                                        <th style="font-size:10px;padding:10px 14px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);">N° Document</th>
                                        <th style="font-size:10px;padding:10px 14px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);">Statut</th>
                                        <th style="font-size:10px;padding:10px 14px;color:var(--muted);font-weight:800;border-bottom:1px solid var(--border);width:50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($impetrant->demandes->sortByDesc('created_at') as $d)
                                    @php
                                        $statColors = [
                                            "En attente d'approbation"               => 'warning',
                                            'Approuvée'                              => 'success',
                                            'Rejetée'                                => 'danger',
                                            'Envoyée au contentieux'                 => 'danger',
                                            'Renvoyée à la saisie pour modification' => 'info',
                                            'Livrée'                                 => 'primary',
                                        ];
                                        $color = $statColors[$d->statut] ?? 'secondary';
                                    @endphp
                                    <tr class="dem-row"
                                        onclick="window.location='{{ route('demandes.show', $d->id) }}'">
                                        <td>
                                            @if($d->photo && file_exists(public_path('app/'.$d->photo)))
                                                <img src="{{ asset('app/'.$d->photo) }}"
                                                     style="width:34px;height:42px;object-fit:cover;
                                                            border-radius:5px;border:1px solid var(--border);">
                                            @else
                                                <div style="width:34px;height:42px;border-radius:5px;
                                                            background:#f0f0f0;display:flex;
                                                            align-items:center;justify-content:center;
                                                            border:1px solid var(--border);">
                                                    <i class="la la-user" style="font-size:.9rem;color:#ced4da;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td style="color:var(--muted);">
                                            {{ $d->created_at?->addHour()->format('d/m/Y') ?? '—' }}
                                        </td>
                                        <td>{{ $d->typeTitre?->libelle ?? '—' }}</td>
                                        <td style="font-weight:600;">{{ $d->reference ?? '—' }}</td>
                                        <td style="font-weight:600; color:var(--dark);">
                                            {{ $d->numero_document ?? '—' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $color }}" style="font-size:10px;">
                                                {{ $d->statut }}
                                            </span>
                                        </td>
                                        <td onclick="event.stopPropagation()">
                                            <a href="{{ route('demandes.show', $d->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               style="width:28px;height:28px;padding:0;border-radius:6px;
                                                      display:inline-flex;align-items:center;justify-content:center;"
                                               title="Voir">
                                                <i class="la la-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     MODAL — Ajouter un document
══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalAjouterDoc" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:linear-gradient(135deg,#1E9FF2,#0d7fd4);">
                <h5 class="modal-title text-white" style="font-size:14px;">
                    <i class="la la-id-card mr-2"></i>
                    Ajouter un document — {{ strtoupper($impetrant->nom) }} {{ $impetrant->prenom }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {{-- Lecteur --}}
                <div style="border:1.5px solid rgba(30,159,242,.2);border-radius:8px;
                            background:#f5fbff;padding:10px 14px;margin-bottom:14px;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                        <div class="d-flex align-items-center" style="gap:8px;">
                            <span id="doc-led"
                                  style="width:9px;height:9px;border-radius:50%;
                                         background:#ced4da;display:inline-block;"></span>
                            <span id="doc-reader-status" style="font-size:12px;color:var(--muted);">
                                Vérification du lecteur…
                            </span>
                        </div>
                        <div class="d-flex" style="gap:6px;">
                            <button type="button" id="btn-doc-lire" class="btn btn-sm btn-primary"
                                    style="border-radius:7px;">
                                <i class="la la-id-card mr-1"></i> Lire passeport
                            </button>
                            <button type="button" id="btn-doc-restart"
                                    class="btn btn-sm btn-outline-warning" style="border-radius:7px;">
                                <i class="la la-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div id="doc-photo-preview" class="text-center mt-2" style="display:none;">
                        <img id="doc-photo-img" src=""
                             style="height:90px;width:72px;object-fit:cover;
                                    border-radius:6px;border:2px solid var(--green);">
                        <div style="font-size:10px;color:var(--green);margin-top:3px;">
                            <i class="la la-check-circle"></i> Photo biométrique lue
                        </div>
                    </div>
                </div>

                {{-- Formulaire --}}
                <form id="frmAjouterDoc">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label style="font-size:11px;font-weight:700;color:var(--dark);">
                                Type de document <span class="text-danger">*</span>
                            </label>
                            <select id="doc_type" class="form-control form-control-sm" required
                                    style="border-radius:7px;">
                                <option value="">— Sélectionner —</option>
                                <option value="Passeport">Passeport</option>
                                <option value="Carte d'identité">Carte d'identité</option>
                                <option value="Titre de voyage">Titre de voyage</option>
                                <option value="Laissez-passer">Laissez-passer</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label style="font-size:11px;font-weight:700;color:var(--dark);">
                                Numéro de document <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="doc_numero" class="form-control form-control-sm"
                                   placeholder="Ex: AB123456" required
                                   style="border-radius:7px;text-transform:uppercase;font-weight:700;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label style="font-size:11px;font-weight:700;color:var(--dark);">Date de délivrance</label>
                            <input type="date" id="doc_delivrance" class="form-control form-control-sm"
                                   style="border-radius:7px;">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label style="font-size:11px;font-weight:700;color:var(--dark);">Date d'expiration</label>
                            <input type="date" id="doc_expiration" class="form-control form-control-sm"
                                   style="border-radius:7px;">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label style="font-size:11px;font-weight:700;color:var(--dark);">Pays de délivrance</label>
                            <select id="doc_pays" class="form-control form-control-sm"
                                    style="border-radius:7px;">
                                <option value="">— Sélectionner —</option>
                                @foreach(\App\Models\Pays::orderBy('lib_pays')->get() as $p)
                                    <option value="{{ $p->id }}">{{ $p->lib_pays ?? $p->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="doc_mrz">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"
                        style="border-radius:7px;">
                    Annuler
                </button>
                <button type="button" id="btn-doc-sauvegarder"
                        class="btn btn-primary btn-sm" style="border-radius:7px;">
                    <i class="la la-save mr-1"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    var READER_URL   = 'http://127.0.0.1:8085';
    var IMPETRANT_ID = {{ $impetrant->id }};
    var CSRF         = '{{ csrf_token() }}';

    // ── Ouvrir modal (anti-bouton enfoncé) ────────────────────
    $('#btn-ouvrir-modal-doc').on('click', function() {
        if (document.activeElement) document.activeElement.blur();
        _resetFormDoc();
        $('#modalAjouterDoc').modal('show');
    });

    $('#modalAjouterDoc').on('shown.bs.modal', function() { _docCheckLecteur(); });

    // ── Statut lecteur ────────────────────────────────────────
    function _docSetLed(etat, msg) {
        var colors = { ok:'#28D094', err:'#FF4961', busy:'#FF9149' };
        document.getElementById('doc-led').style.background = colors[etat] || '#ced4da';
        document.getElementById('doc-reader-status').innerHTML = msg;
    }

    function _docCheckLecteur() {
        $.ajax({
            url: READER_URL + '/status', method:'GET', timeout:3000,
            success: function() {
                _docSetLed('ok', '<i class="la la-check-circle text-success mr-1"></i> Lecteur prêt');
            },
            error: function() {
                _docSetLed('err', '<i class="la la-times-circle text-danger mr-1"></i> Lecteur non disponible');
            }
        });
    }

    // ── Lire ──────────────────────────────────────────────────
    $('#btn-doc-lire').on('click', function() {
        $(this).prop('disabled', true);
        _docSetLed('busy', '<i class="la la-spinner la-spin mr-1"></i> Lecture en cours…');
        $('#doc-photo-preview').hide();
        $.ajax({
            url: READER_URL + '/read', method:'GET', timeout:120000,
            success: function(data) {
                $('#btn-doc-lire').prop('disabled', false);
                if (data.status === 'success') { _docRemplir(data); }
                else {
                    _docSetLed('err', '<i class="la la-exclamation-triangle text-warning mr-1"></i> ' +
                        (data.message || 'Erreur lecture'));
                    toastr.warning(data.message || 'Erreur lecture passeport');
                }
            },
            error: function() {
                $('#btn-doc-lire').prop('disabled', false);
                _docSetLed('err', '<i class="la la-times-circle text-danger mr-1"></i> Service non disponible');
                toastr.error('Service lecteur non disponible (port 8085)');
            }
        });
    });

    // ── Restart ───────────────────────────────────────────────
    $('#btn-doc-restart').on('click', function() {
        $(this).prop('disabled', true);
        _docSetLed('busy', '<i class="la la-refresh la-spin mr-1"></i> Réinitialisation…');
        $.ajax({
            url: READER_URL + '/restart', method:'GET', timeout:10000,
            success: function() {
                setTimeout(function() {
                    _docSetLed('ok', '<i class="la la-check-circle text-success mr-1"></i> Réinitialisé !');
                    $('#btn-doc-restart, #btn-doc-lire').prop('disabled', false);
                }, 3000);
            },
            error: function() {
                _docSetLed('err', '<i class="la la-times-circle text-danger mr-1"></i> Erreur');
                $('#btn-doc-restart').prop('disabled', false);
            }
        });
    });

    // ── Remplir depuis lecteur ────────────────────────────────
    function _docRemplir(data) {
        if (data.num_doc) {
            $('#doc_numero').val(data.num_doc.toUpperCase());
            $('#doc_type').val((data.type_doc && data.type_doc.toUpperCase() === 'P')
                ? 'Passeport' : 'Titre de voyage');
        }
        if (data.expiration)    $('#doc_expiration').val(data.expiration);
        if (data.date_emission) $('#doc_delivrance').val(data.date_emission);
        if (data.mrz)           $('#doc_mrz').val(data.mrz);
        if (data.nationalite) {
            $.get('/api/passport/pays', function(pays) {
                var opt = pays[data.nationalite.toUpperCase()];
                if (opt) $('#doc_pays').val(opt.id);
            });
        }
        if (data.photo_base64 && data.photo_base64.length > 100) {
            $('#doc-photo-img').attr('src', 'data:image/jpeg;base64,' + data.photo_base64);
            $('#doc-photo-preview').show();
        }
        _docSetLed('ok', '<i class="la la-check-circle text-success mr-1"></i> <strong>Lu !</strong> '
            + (data.nom || ''));
        toastr.success('Document lu — formulaire pré-rempli', 'Lecteur');
    }

    // ── Sauvegarder ───────────────────────────────────────────
    $('#btn-doc-sauvegarder').on('click', function() {
        var type   = $('#doc_type').val();
        var numero = $('#doc_numero').val().trim().toUpperCase();
        if (!type || !numero) {
            toastr.warning('Le type et le numéro sont obligatoires.');
            return;
        }
        var $btn = $(this);
        $btn.html('<i class="la la-spinner la-spin mr-1"></i> Enregistrement…').prop('disabled', true);
        $.ajax({
            url: '/api/impetrants/' + IMPETRANT_ID + '/store-document',
            method: 'POST',
            data: {
                _token: CSRF, type_document: type, numero_document: numero,
                date_delivrance: $('#doc_delivrance').val() || '',
                date_expiration: $('#doc_expiration').val() || '',
                pays_delivrance_id: $('#doc_pays').val() || '',
                mrz: $('#doc_mrz').val() || '', source: 'manuel'
            },
            success: function(r) {
                if (r.saved || r.id) {
                    toastr.success('Document enregistré.', 'DMCE');
                    $('#modalAjouterDoc').modal('hide');
                    setTimeout(function() { location.reload(); }, 700);
                } else if (r.exists) {
                    toastr.warning('Ce numéro est déjà enregistré pour cet impétrant.');
                } else {
                    toastr.error(r.message || 'Erreur enregistrement.');
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Erreur serveur.');
            },
            complete: function() {
                $btn.html('<i class="la la-save mr-1"></i> Enregistrer').prop('disabled', false);
            }
        });
    });

    function _resetFormDoc() {
        $('#doc_type, #doc_delivrance, #doc_expiration, #doc_pays, #doc_mrz').val('');
        $('#doc_numero').val('');
        $('#doc-photo-preview').hide();
        _docSetLed('', 'Vérification du lecteur…');
    }

    $('#modalAjouterDoc').on('hidden.bs.modal', function() { _resetFormDoc(); });
});
</script>
@endpush