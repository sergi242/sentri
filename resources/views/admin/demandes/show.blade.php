@extends('admin.layouts.app')
@section('title') Dossier N°{{ $demande->uuid }} @endsection
@section('styles')
<link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
<style>
/* ── Layout général ── */
.show-grid { display: grid; grid-template-columns: 340px 1fr; gap: 24px; align-items: start; }
@media(max-width:1024px){ .show-grid { grid-template-columns: 300px 1fr; } }
@media(max-width:768px) { .show-grid { grid-template-columns: 1fr; } }

/* ── Sidebar gauche ── */
.sidebar-card {
    background: #fff; border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    overflow: hidden; position: sticky; top: 80px;
}
.sidebar-photo {
    width: 100%; height: 280px;
    object-fit: cover; object-position: top; display: block;
}
.sidebar-photo-placeholder {
    width: 100%; height: 280px;
    background: linear-gradient(135deg,#e9ecef,#dee2e6);
    display: flex; align-items: center; justify-content: center;
    font-size: 5rem; color: #adb5bd;
}
.sidebar-body { padding: 20px; }
.sidebar-name { font-size: 1.15rem; font-weight: 800; color: #212529; line-height: 1.3; margin-bottom: 6px; }
.sidebar-sub  { font-size: 13px; color: #6c757d; margin-top: 2px; }
.flag-inline  { width: 22px; height: auto; border-radius: 3px; border: 1px solid #dee2e6; vertical-align: middle; margin-right: 5px; }

/* ── Statut badge ── */
.statut-badge {
    display: inline-block; padding: 5px 14px; border-radius: 20px;
    font-size: 12px; font-weight: 700; margin: 8px 0;
}
.statut-attente    { background:#fff3e0; color:#e65100; border:1px solid #ffcc80; }
.statut-approuvee  { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
.statut-rejetee    { background:#fce4ec; color:#c62828; border:1px solid #ef9a9a; }
.statut-contentieux{ background:#fff8e1; color:#f57f17; border:1px solid #ffe082; }
.statut-renvoyee   { background:#e3f2fd; color:#1565c0; border:1px solid #90caf9; }
.statut-livree     { background:#e8f5e9; color:#1b5e20; border:1px solid #66bb6a; }

/* ── Actions sidebar ── */
.action-btn {
    display: block; width: 100%; padding: 8px 12px;
    border-radius: 8px; font-size: 13px; font-weight: 600;
    text-align: center; text-decoration: none;
    margin-bottom: 6px; border: none; cursor: pointer;
    transition: all .15s;
}
.action-btn:hover { transform: translateY(-1px); text-decoration: none; }
.action-btn i { margin-right: 6px; }

/* ── Section titres ── */
.section-header {
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; color: #495057;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 8px; margin: 20px 0 14px;
}
.section-header i { font-size: 16px; color: #1E9FF2; }

/* ── Info rows ── */
.info-row {
    display: flex; border-bottom: 1px solid #f8f9fa;
    padding: 8px 0; align-items: flex-start;
}
.info-row:last-child { border-bottom: none; }
.info-label {
    width: 200px; flex-shrink: 0;
    font-size: 12px; font-weight: 600;
    color: #6c757d; padding-right: 12px;
    text-transform: uppercase; letter-spacing: .3px;
    padding-top: 1px;
}
.info-value {
    font-size: 14px; color: #212529; font-weight: 500;
    flex: 1;
}
.info-value.empty { color: #adb5bd; font-style: italic; font-weight: 400; }

/* ── Cards sections ── */
.data-card {
    background: #fff; border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    margin-bottom: 16px; overflow: hidden;
}
.data-card-header {
    padding: 12px 18px; font-size: 13px;
    font-weight: 700; display: flex; align-items: center; gap: 8px;
}
.data-card-body { padding: 8px 18px 16px; }

/* ── Document card ── */
.doc-card {
    background: linear-gradient(135deg,#1E9FF2,#0d6ebc);
    color: #fff; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px;
}
.doc-card .doc-num { font-size: 1.6rem; font-weight: 800; letter-spacing: 1px; }
.doc-card .doc-label { font-size: 11px; opacity: .8; text-transform: uppercase; }

/* ── Timeline ── */
.timeline { padding: 0; list-style: none; }
.timeline-item { display: flex; gap: 12px; margin-bottom: 12px; }
.timeline-dot {
    width: 10px; height: 10px; border-radius: 50%;
    flex-shrink: 0; margin-top: 5px;
}
.timeline-content { flex: 1; }
.timeline-label { font-size: 11px; color: #6c757d; }
.timeline-value { font-size: 13px; font-weight: 600; }

/* ── Renouvellement ── */
.renew-block-badge {
    background:#FF4961; color:#fff; font-size:11px; font-weight:700;
    padding:2px 7px; border-radius:10px; vertical-align:middle;
}
.renew-progress-bar { height:6px; border-radius:3px; background:#e9ecef; overflow:hidden; margin-top:4px; }
.renew-progress-fill { height:100%; border-radius:3px; background:linear-gradient(90deg,#FF4961,#FF9149); }

/* ── Alertes ── */
@keyframes alertBlink {
    0% { box-shadow: inset 0 0 50px rgba(220,53,69,.8),0 0 20px rgba(220,53,69,.5); border-color:#dc3545; }
    100% { box-shadow: inset 0 0 100px rgba(0,0,0,.9),0 0 5px rgba(0,0,0,.8); border-color:#000; }
}
#danger-border { position:fixed;top:0;left:0;width:100vw;height:100vh;border:15px solid #dc3545;z-index:9998;pointer-events:none;animation:alertBlink .8s infinite alternate; }
.bounce-icon { animation:pulse-red 1s infinite; }
@keyframes pulse-red {
    0% { transform:scale(1); filter:drop-shadow(0 0 0px rgba(220,53,69,.7)); }
    70% { transform:scale(1.1); filter:drop-shadow(0 0 15px rgba(220,53,69,0)); }
    100% { transform:scale(1); }
}
.fw-black { font-weight:900; letter-spacing:-1px; }
.matching-list { list-style:none; padding-left:0; }
.matching-list li { background:rgba(220,53,69,.05); margin-bottom:5px; padding:8px 12px; border-radius:6px; border-left:3px solid #dc3545; }
.btn-confirm { background:#212529; color:white; border:none; transition:all .3s; text-transform:uppercase; letter-spacing:1px; }
.btn-confirm:hover { background:#000; box-shadow:0 5px 15px rgba(0,0,0,.3); transform:translateY(-2px); }
@keyframes ctxBlink {
    from { opacity:1; border-color:#dc3545; }
    to   { opacity:.4; border-color:#7f0000; }
}
@keyframes ctxBounce {
    from { transform:translateY(0) scale(1); }
    to   { transform:translateY(-6px) scale(1.1); }
}
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">

@php
use App\Models\Pays;

// Pays / drapeau
$paysImp  = null;
$flagPath = null;
if ($demande->impetrant?->nationalites_id) {
    $paysImp  = Pays::find($demande->impetrant->nationalites_id);
    $flagPath = $paysImp?->code
        ? 'res/flags/' . strtolower(trim($paysImp->code)) . '.png'
        : null;
}

// Nationalité badge
$badgeNationalite = $paysImp?->nationalite ?: $paysImp?->lib_pays;

// Blocage renouvellement
$derniereDemande    = \App\Models\Demande::where('impetrants_id', $demande->impetrants_id)
    ->where('id', '!=', $demande->id)
    ->whereNotNull('date_emission')->where('attribue', 1)
    ->orderByDesc('date_emission')->first();

$peutRenouveler   = true;
$joursEcoules     = null; $joursRestants = null;
$dateEmissionStr  = null; $prochaineDateDispo = null; $progressPct = 0;
$delaiMinJours    = 183;

if ($derniereDemande) {
    $dateEmissionCarbon = \Carbon\Carbon::parse($derniereDemande->date_emission);
    $maintenant         = \Carbon\Carbon::now('Africa/Brazzaville');
    $joursEcoules       = (int) $dateEmissionCarbon->diffInDays($maintenant);
    $dateEmissionStr    = $dateEmissionCarbon->format('d/m/Y');
    $progressPct        = min(100, (int)(($joursEcoules / $delaiMinJours) * 100));
    if ($joursEcoules < $delaiMinJours) {
        $peutRenouveler     = false;
        $joursRestants      = $delaiMinJours - $joursEcoules;
        $prochaineDateDispo = $dateEmissionCarbon->copy()->addDays($delaiMinJours)->format('d/m/Y');
    }
}

// Statut CSS
$statutClass = match($demande->statut_demande) {
    "En attente d'approbation"          => 'statut-attente',
    'Approuvée'                          => 'statut-approuvee',
    'Rejetée'                            => 'statut-rejetee',
    'Envoyée au contentieux'             => 'statut-contentieux',
    'Renvoyée à la saisie pour modification' => 'statut-renvoyee',
    'Livrée'                             => 'statut-livree',
    default                              => 'statut-attente',
};

// Role
$roleActuel               = auth()->user()?->role?->lib_role;
$peutApprouverContentieux = in_array($roleActuel, ['SuperAdmin', 'Admin']);
@endphp

<div class="container-fluid py-3">

    {{-- ══ BARRE D'ACTIONS PRINCIPALE (en haut) ══ --}}
    <div class="card mb-3" style="border-radius:12px; border-left:5px solid {{ match($demande->statut_demande) {
        'Approuvée' => '#28D094', 'Rejetée' => '#FF4961',
        'Envoyée au contentieux' => '#FF9149', 'Livrée' => '#28D094',
        default => '#1E9FF2' } }};">
        <div class="card-body py-3 px-4">

            {{-- Ligne 1 : titre + statut + retour --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center" style="gap:12px;">
                    {{-- Mini photo --}}
                    @if($demande->photo)
                        <img src="{{ asset('app/'.$demande->photo) }}"
                             style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid #dee2e6;">
                    @else
                        <div style="width:48px;height:48px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
                            <i class="la la-user" style="font-size:1.4rem;color:#adb5bd;"></i>
                        </div>
                    @endif
                    <div>
                        <h5 class="mb-0 font-weight-bold">
                            @if($flagPath && file_exists(public_path($flagPath)))
                                <img src="{{ asset($flagPath) }}" class="flag-inline">
                            @endif
                            {{ strtoupper($demande->impetrant?->nom ?? '') }}
                            {{ $demande->impetrant?->prenom }}
                        </h5>
                        <small class="text-muted">
                            Dossier N°<strong class="text-primary">{{ $demande->uuid }}</strong>
                            · Créé le {{ $demande->created_at?->format('d/m/Y à H:i') }}
                            @if($demande->createur) par {{ $demande->createur->getNomPrenom() }} @endif
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="gap:8px;">
                    <span class="statut-badge {{ $statutClass }}">
                        {{ $demande->statut_demande }}
                    </span>
                    @if($demande->tag_demande)
                        <span class="badge badge-{{ $demande->tag_demande === 'IMPRESSION' ? 'info' : 'warning' }}">
                            {{ $demande->tag_demande }}
                        </span>
                    @endif
                    <a href="{{ route('demandes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="la la-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            {{-- Ligne 2 : boutons d'action horizontaux --}}
            <div class="d-flex flex-wrap" style="gap:8px;">

                {{-- Approuver --}}
                @if($demande->statut_demande == "En attente d'approbation" && $sims->count() < 1)
                <button type="button" class="btn btn-success"
                        data-toggle="modal" data-target="#confirmApprovalModal">
                    <i class="la la-check"></i> Approuver
                </button>
                @endif

                {{-- Approuver malgré contentieux --}}
                @if($demande->statut_demande == "Envoyée au contentieux" && $peutApprouverContentieux)
                <form action="{{ route('approuver.simple', $demande->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="la la-check"></i> Approuver malgré contentieux
                    </button>
                </form>
                @endif

                {{-- Envoyer au contentieux --}}
                @if($demande->statut_demande == "En attente d'approbation")
                <a href="{{ route('demandes.create.contentieux', $demande->id) }}"
                   class="btn btn-warning">
                    <i class="la la-gavel"></i> Envoyer au contentieux
                </a>
                @endif

                {{-- Renseigner document --}}
                @if($demande->statut_demande == "Approuvée" && !$demande->numero_document)
                <a href="{{ route('demandes.remplirformation', $demande->id) }}"
                   class="btn btn-secondary">
                    <i class="la la-edit"></i> Renseigner le document
                </a>
                @endif

                {{-- Modifier document --}}
                @if($demande->numero_document)
                <a href="{{ route('demandes.remplirformation', $demande->id) }}"
                   class="btn btn-outline-warning">
                    <i class="la la-edit"></i> Modifier le document
                </a>
                <button type="button" class="btn btn-outline-danger"
                        data-toggle="modal" data-target="#resetDocModal">
                    <i class="la la-undo"></i> Réinitialiser le doc
                </button>
                @endif

                {{-- Modifier dossier --}}
                <a href="{{ route('demandes.edit', $demande->id) }}" class="btn btn-primary">
                    <i class="la la-pencil"></i> Modifier
                </a>

                {{-- Renouveler --}}
                @can('demandes.renew')
                @if($peutRenouveler)
                <a href="{{ route('demandes.renouveler', $demande->impetrants_id) }}"
                   class="btn btn-warning"
                   onclick="return confirm('Confirmer le renouvellement ?')">
                    <i class="la la-redo-alt"></i> Renouveler
                </a>
                @else
                <button class="btn btn-secondary" disabled
                        title="Disponible le {{ $prochaineDateDispo }}">
                    <i class="la la-ban"></i> Renouveler
                    <span class="renew-block-badge">{{ $joursRestants }}j</span>
                </button>
                @endif
                @endcan

                {{-- Fiche --}}
                @if($demande->fiches->count() > 0)
                    @if(\Carbon\Carbon::parse($demande->fiches->last()->date_valite_fiche)->gt(\Carbon\Carbon::now()))
                    <a href="{{ route('demandes.fiche', $demande->id) }}" class="btn btn-outline-secondary">
                        <i class="la la-folder-open"></i> Fiche
                    </a>
                    @else
                    <form action="{{ route('demandes.renouveler.fiche', $demande->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="la la-refresh"></i> Renouveler fiche
                        </button>
                    </form>
                    @endif
                @endif

                {{-- Similarités --}}
                @if($sims->count() > 0)
                <a href="{{ route('demandes.similarities', $demande->id) }}" class="btn btn-danger">
                    <i class="la la-exclamation-triangle"></i>
                    {{ $sims->count() }} similarité(s)
                </a>
                @endif

                {{-- Casier --}}
                <a href="{{ route('impetrants.casier', $demande->impetrants_id) }}"
                   class="btn btn-dark">
                    <i class="la la-book"></i> Casier
                </a>

                {{-- Supprimer --}}
                <form action="{{ route('demandes.destroy', $demande->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirm('Supprimer ce dossier ?')">
                        <i class="la la-trash"></i>
                    </button>
                </form>

            </div>

            {{-- Alerte blocage renouvellement --}}
            @if(!$peutRenouveler)
            <div class="alert alert-danger py-2 px-3 mt-2 mb-0"
                 style="font-size:12px; border-radius:6px;">
                <i class="la la-ban"></i>
                Renouvellement impossible — Dernière émission : <strong>{{ $dateEmissionStr }}</strong>
                ({{ $joursEcoules }}j / {{ $delaiMinJours }}j requis)
                — Prochain : <strong>{{ $prochaineDateDispo }}</strong>
                <div class="renew-progress-bar mt-1">
                    <div class="renew-progress-fill" style="width:{{ $progressPct }}%"></div>
                </div>
            </div>
            @endif

            {{-- Alerte accès restreint contentieux --}}
            @if($demande->statut_demande == "Envoyée au contentieux" && !$peutApprouverContentieux)
            <div class="alert alert-danger py-2 px-3 mt-2 mb-0" style="font-size:12px; border-radius:6px;">
                <i class="la la-lock"></i>
                Ce dossier est en contentieux. Seul un <strong>Admin/SuperAdmin</strong> peut l'approuver.
            </div>
            @endif

        </div>
    </div>

    <div class="show-grid">

        {{-- ══════════════════════ SIDEBAR GAUCHE ══════════════════════ --}}
        <div>
            <div class="sidebar-card">

                {{-- Photo principale --}}
                @if($demande->photo)
                    <img src="{{ asset('app/'.$demande->photo) }}"
                         class="sidebar-photo" alt="Photo" id="sidebar-main-photo">
                @else
                    <div class="sidebar-photo-placeholder" id="sidebar-main-photo">
                        <i class="la la-user"></i>
                    </div>
                @endif

                {{-- ── GALERIE PHOTOS HISTORIQUE ── --}}
                @php
                    $autresDemandes = \App\Models\Demande::where('impetrants_id', $demande->impetrants_id)
                        ->whereNotNull('photo')
                        ->where('photo','!=','')
                        ->orderByDesc('created_at')
                        ->get(['id','uuid','photo','statut_demande','date_demande']);
                @endphp
                @if($autresDemandes->count() > 1)
                <div style="padding:10px 12px 0; border-top:1px solid #f0f0f0;">
                    <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:#6c757d; margin-bottom:8px;">
                        <i class="la la-camera"></i> Photos des dossiers ({{ $autresDemandes->count() }})
                    </div>
                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                        @foreach($autresDemandes as $d)
                        @php
                            $isActive = $d->id === $demande->id;
                            $sc = match($d->statut_demande) {
                                'Approuvée'=>'#28D094','Livrée'=>'#1E9FF2',
                                'Rejetée'=>'#FF4961','Envoyée au contentieux'=>'#FF9149',
                                default=>'#adb5bd'
                            };
                        @endphp
                        <div style="position:relative; cursor:pointer;"
                             title="{{ $d->uuid }} — {{ $d->statut_demande }}"
                             onclick="sidebarShowPhoto('{{ asset('app/'.$d->photo) }}', '{{ $d->uuid }}', '{{ $d->statut_demande }}', this)">
                            <img src="{{ asset('app/'.$d->photo) }}"
                                 style="width:54px; height:54px; object-fit:cover; object-position:top;
                                        border-radius:8px; display:block;
                                        border: 3px solid {{ $isActive ? '#1E9FF2' : '#dee2e6' }};
                                        transition: all .2s;"
                                 class="gallery-thumb {{ $isActive ? 'active' : '' }}"
                                 onerror="this.parentElement.style.display='none'"
                                 alt="{{ $d->uuid }}">
                            {{-- Point couleur statut --}}
                            <span style="position:absolute; bottom:3px; right:3px; width:10px; height:10px;
                                         border-radius:50%; background:{{ $sc }}; border:2px solid #fff;"></span>
                            {{-- Année --}}
                            <span style="position:absolute; bottom:0; left:0; right:0;
                                         background:rgba(0,0,0,.6); color:#fff; font-size:8px;
                                         text-align:center; border-radius:0 0 5px 5px; padding:1px 0;">
                                {{ $d->date_demande ? date('Y', strtotime($d->date_demande)) : '—' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('impetrants.demandes', $demande->impetrants_id) }}"
                       class="btn btn-outline-primary btn-sm btn-block mt-2" style="font-size:11px;">
                        <i class="la la-folder-open"></i> Voir tous les dossiers
                    </a>
                </div>
                @endif

                <div class="sidebar-body">
                    {{-- Nom + drapeau --}}
                    <div class="sidebar-name">
                        @if($flagPath && file_exists(public_path($flagPath)))
                            <img src="{{ asset($flagPath) }}" class="flag-inline" alt="">
                        @endif
                        {{ strtoupper($demande->impetrant?->nom ?? '') }}
                        {{ $demande->impetrant?->prenom }}
                    </div>
                    <div class="sidebar-sub">
                        {{ $demande->impetrant?->sexe }}
                        @if($demande->impetrant?->date_naissance)
                            · {{ \Carbon\Carbon::parse($demande->impetrant->date_naissance)->format('d/m/Y') }}
                        @endif
                    </div>
                    @if($badgeNationalite)
                        <span class="badge badge-primary mt-1">{{ $badgeNationalite }}</span>
                    @endif

                    <hr class="my-3">

                    {{-- Infos rapides --}}
                    <div class="info-row" style="font-size:14px; padding: 10px 0;">
                        <span class="info-label" style="width:110px; font-size:11px;">UUID</span>
                        <span class="info-value font-weight-bold text-primary" style="font-size:14px;">{{ $demande->uuid }}</span>
                    </div>
                    <div class="info-row" style="font-size:14px; padding: 10px 0;">
                        <span class="info-label" style="width:110px; font-size:11px;">QUITTANCE</span>
                        <span class="info-value" style="font-size:14px;">
                            @if($demande->numero_quittance)
                                {{ $demande->numero_quittance }}
                                @if($demande->quittance_confirmee)
                                    <i class="la la-check-circle text-success ml-1"></i>
                                @endif
                            @else <span class="empty">—</span> @endif
                        </span>
                    </div>
                    <div class="info-row" style="font-size:14px; padding: 10px 0;">
                        <span class="info-label" style="width:110px; font-size:11px;">TYPE</span>
                        <span class="info-value" style="font-size:14px;">{{ $demande->type_demande }}</span>
                    </div>
                    <div class="info-row" style="font-size:14px; padding: 10px 0;">
                        <span class="info-label" style="width:110px; font-size:11px;">VALIDITÉ</span>
                        <span class="info-value" style="font-size:14px;">{{ $demande->validite }} an(s)</span>
                    </div>
                    <div class="info-row" style="font-size:14px; padding: 10px 0;">
                        <span class="info-label" style="width:110px; font-size:11px;">DATE DEMANDE</span>
                        <span class="info-value" style="font-size:14px;">{{ $demande->date_demande ? date('d/m/Y', strtotime($demande->date_demande)) : '—' }}</span>
                    </div>
                    @if($demande->commanditaire)
                    <div class="info-row" style="font-size:14px; padding: 10px 0;">
                        <span class="info-label" style="width:110px; font-size:11px;">COMMANDITAIRE</span>
                        <span class="info-value" style="font-size:14px;">
                            <a href="{{ route('users.show', $demande->commanditaire->id) }}">
                                {{ $demande->commanditaire->nom }} {{ $demande->commanditaire->prenom }}
                            </a>
                        </span>
                    </div>
                    @endif
                    @if($demande->soit_transmis_id && $demande->soitTransmis)
                    <div class="info-row" style="font-size:14px; padding: 10px 0;">
                        <span class="info-label" style="width:110px; font-size:11px;">SOIT-TRANSMIS</span>
                        <span class="info-value" style="font-size:14px;">
                            <a href="{{ route('soit-transmis.show', $demande->soitTransmis->id) }}">
                                {{ $demande->soitTransmis->numero }} <i class="la la-external-link"></i>
                            </a>
                        </span>
                    </div>
                    @endif
                    @if($demande->impetrant?->est_hebergeur)
                    <div class="mt-3">
                        <span class="badge badge-success" style="font-size:12px; padding:5px 10px;">
                            <i class="la la-home"></i> Hébergeur enregistré
                        </span>
                        @if($demande->impetrant->code_hebergeur)
                            <small class="badge badge-light text-muted d-block mt-1">{{ $demande->impetrant->code_hebergeur }}</small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══════════════════════ CONTENU PRINCIPAL ══════════════════════ --}}
        <div>

            {{-- ── Certificat hébergement ── --}}
            @if($certificatHebergement)
            <div class="data-card">
                <div class="data-card-header" style="background:#e3f2fd;">
                    <i class="la la-building text-info"></i>
                    <strong>Certificat d'hébergement</strong>
                    <span class="badge badge-{{ $certificatHebergement->statut === 'Validé' ? 'success' : 'warning' }} ml-auto">
                        {{ $certificatHebergement->statut }}
                    </span>
                </div>
                <div class="data-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>N° {{ $certificatHebergement->numero_certificat }}</strong>
                            <span class="text-muted ml-2">— Hébergeur : {{ $certificatHebergement->nom_hebergeur }}</span>
                        </div>
                        <a href="{{ route('certificats-hebergement.show', $certificatHebergement->id) }}"
                           class="btn btn-sm btn-outline-info">
                            <i class="la la-eye"></i> Voir le certificat
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Similarités ── --}}
            @if($sims->count() > 0)
            <div class="alert alert-danger d-flex align-items-center justify-content-between mb-3">
                <div>
                    <i class="la la-exclamation-triangle"></i>
                    <strong>{{ $sims->count() }} similarité(s) détectée(s)</strong>
                </div>
                <a href="{{ route('demandes.similarities', $demande->id) }}" class="btn btn-warning btn-sm">
                    <i class="la la-search"></i> Voir les similarités
                </a>
            </div>
            @endif

            {{-- ── Document attribué ── --}}
            @if($demande->numero_document)
            <div class="doc-card">
                <div class="row">
                    <div class="col-md-4">
                        <div class="doc-label">Numéro du document</div>
                        <div class="doc-num">{{ $demande->numero_document }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="doc-label">Date d'émission</div>
                        <div style="font-size:1rem; font-weight:700;">
                            {{ $demande->date_emission ? date('d/m/Y', strtotime($demande->date_emission)) : '—' }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="doc-label">Date d'expiration</div>
                        <div style="font-size:1rem; font-weight:700;">
                            {{ $demande->date_expiration ? date('d/m/Y', strtotime($demande->date_expiration)) : '—' }}
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center justify-content-end">
                        <div>
                            <a href="{{ route('demandes.remplirformation', $demande->id) }}"
                               class="btn btn-sm btn-light d-block mb-1">
                                <i class="la la-edit"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-sm btn-danger d-block"
                                    data-toggle="modal" data-target="#resetDocModal">
                                <i class="la la-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
                @if($demande->attribue_par)
                <div style="font-size:11px; opacity:.8; margin-top:8px;">
                    <i class="la la-user"></i>
                    Attribué le {{ $demande->date_attribution ? \Carbon\Carbon::parse($demande->date_attribution)->format('d/m/Y') : '—' }}
                    @php $attribuePar = \App\Models\User::find($demande->attribue_par); @endphp
                    @if($attribuePar) par {{ $attribuePar->getNomPrenom() }} @endif
                </div>
                @endif
            </div>
            @endif

            {{-- ── IDENTITÉ IMPÉTRANT ── --}}
            <div class="data-card">
                <div class="data-card-header" style="background:#f8f9fa;">
                    <i class="la la-user text-primary"></i>
                    <strong>Identité de l'impétrant</strong>
                </div>
                <div class="data-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Nom</span>
                                <span class="info-value font-weight-bold">{{ strtoupper($demande->impetrant?->nom ?? '—') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Prénom</span>
                                <span class="info-value">{{ $demande->impetrant?->prenom ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Sexe</span>
                                <span class="info-value">{{ $demande->impetrant?->sexe ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Date de naissance</span>
                                <span class="info-value">
                                    {{ $demande->impetrant?->date_naissance
                                        ? date('d/m/Y', strtotime($demande->impetrant->date_naissance))
                                        : '—' }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Lieu de naissance</span>
                                <span class="info-value">{{ $demande->impetrant?->lieu_naissance ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Nationalité</span>
                                <span class="info-value">
                                    @if($badgeNationalite)
                                        @if($flagPath && file_exists(public_path($flagPath)))
                                            <img src="{{ asset($flagPath) }}" class="flag-inline">
                                        @endif
                                        {{ $badgeNationalite }}
                                    @else
                                        <span class="empty">—</span>
                                    @endif
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">État civil</span>
                                <span class="info-value">{{ $demande->etat_civil ?? '—' }}</span>
                            </div>
                            @if($demande->nom_conjoint)
                            <div class="info-row">
                                <span class="info-label">Nom conjoint</span>
                                <span class="info-value">{{ $demande->nom_conjoint }}</span>
                            </div>
                            @endif
                            <div class="info-row">
                                <span class="info-label">Téléphone</span>
                                <span class="info-value">{{ $demande->telephone ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email</span>
                                <span class="info-value">{{ $demande->email ?: '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── ADRESSE ── --}}
            <div class="data-card">
                <div class="data-card-header" style="background:#f8f9fa;">
                    <i class="la la-map-marker text-primary"></i>
                    <strong>Adresse de résidence</strong>
                </div>
                <div class="data-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Département</span>
                                <span class="info-value">{{ $demande->quartier?->arrondissement?->departement?->lib_departement ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Arrondissement</span>
                                <span class="info-value">{{ $demande->quartier?->arrondissement?->lib_arrondissement ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Quartier</span>
                                <span class="info-value">{{ $demande->quartier?->lib_quartier ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Avenue / Rue</span>
                                <span class="info-value">{{ $demande->avenue_rue ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">N° domicile</span>
                                <span class="info-value">{{ $demande->numero_adresse ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── PARENTS ── --}}
            <div class="data-card">
                <div class="data-card-header" style="background:#f8f9fa;">
                    <i class="la la-users text-primary"></i>
                    <strong>Filiation</strong>
                </div>
                <div class="data-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Nom père</span>
                                <span class="info-value">{{ $demande->impetrant?->nom_pere ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Prénom père</span>
                                <span class="info-value">{{ $demande->impetrant?->prenom_pere ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Nom mère</span>
                                <span class="info-value">{{ $demande->impetrant?->nom_mere ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Prénom mère</span>
                                <span class="info-value">{{ $demande->impetrant?->prenom_mere ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── PROFESSION ── --}}
            <div class="data-card">
                <div class="data-card-header" style="background:#f8f9fa;">
                    <i class="la la-briefcase text-primary"></i>
                    <strong>Profession</strong>
                </div>
                <div class="data-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Catégorie socio-prof.</span>
                                <span class="info-value">
                                    {{ $categories->firstWhere('id', $demande->categorie_socioprof_id)?->categorie ?? '—' }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Profession</span>
                                <span class="info-value">{{ $demande->profession ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Employeur</span>
                                <span class="info-value">{{ $demande->employeur?->nom_employeur ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Adresse employeur</span>
                                <span class="info-value">{{ $demande->employeur?->adresse_physique ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── PASSEPORT ── --}}
            <div class="data-card">
                <div class="data-card-header" style="background:#f8f9fa;">
                    <i class="la la-id-card text-primary"></i>
                    <strong>Passeport</strong>
                </div>
                <div class="data-card-body">
                    @if($demande->passeport())
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-row">
                                <span class="info-label">Numéro</span>
                                <span class="info-value font-weight-bold">{{ $demande->passeport()->numero_document ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <span class="info-label">Date émission</span>
                                <span class="info-value">{{ $demande->passeport()->date_emission ? date('d/m/Y', strtotime($demande->passeport()->date_emission)) : '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <span class="info-label">Date expiration</span>
                                <span class="info-value">{{ $demande->passeport()->date_expiration ? date('d/m/Y', strtotime($demande->passeport()->date_expiration)) : '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Délivré par</span>
                                <span class="info-value">{{ $demande->passeport()->emis_par ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="text-muted mb-0"><i class="la la-exclamation-circle"></i> Aucun passeport enregistré</p>
                    @endif
                </div>
            </div>

            {{-- ── CONTENTIEUX ── --}}
            @if($demande->contentieux->count() > 0)
            <div class="data-card">
                <div class="data-card-header" style="background:#fce4ec;">
                    <i class="la la-gavel text-danger"></i>
                    <strong class="text-danger">Contentieux ({{ $demande->contentieux->count() }})</strong>
                </div>
                <div class="data-card-body">
                    @foreach($demande->contentieux as $c)
                    <div class="p-3 mb-2 border rounded" style="border-left:4px solid #dc3545 !important;">
                        <strong class="text-danger">{{ $c->motif?->lib_motif ?? 'Motif non défini' }}</strong>
                        @if($c->description)
                            <p class="mb-1 mt-1">{{ $c->description }}</p>
                        @endif
                        <small class="text-muted">
                            <i class="la la-clock-o"></i>
                            {{ \Carbon\Carbon::parse($c->created_at)->format('d/m/Y à H:i') }}
                        </small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── TIMELINE / HISTORIQUE ── --}}
            <div class="data-card">
                <div class="data-card-header" style="background:#f8f9fa;">
                    <i class="la la-history text-primary"></i>
                    <strong>Chronologie du dossier</strong>
                </div>
                <div class="data-card-body">
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-dot" style="background:#1E9FF2;"></div>
                            <div class="timeline-content">
                                <div class="timeline-label">Création</div>
                                <div class="timeline-value">
                                    {{ $demande->created_at?->format('d/m/Y à H:i') }}
                                    @if($demande->createur)
                                        — {{ $demande->createur->getNomPrenom() }}
                                    @endif
                                </div>
                            </div>
                        </li>
                        @if($demande->approved_by && $demande->approval_date)
                        <li class="timeline-item">
                            <div class="timeline-dot" style="background:#28D094;"></div>
                            <div class="timeline-content">
                                <div class="timeline-label">Approbation</div>
                                <div class="timeline-value">
                                    {{ \Carbon\Carbon::parse($demande->approval_date)->format('d/m/Y à H:i') }}
                                    @php $approuvePar = \App\Models\User::find($demande->approved_by); @endphp
                                    @if($approuvePar) — {{ $approuvePar->getNomPrenom() }} @endif
                                </div>
                            </div>
                        </li>
                        @endif
                        @if($demande->attribue && $demande->date_attribution)
                        <li class="timeline-item">
                            <div class="timeline-dot" style="background:#FF9149;"></div>
                            <div class="timeline-content">
                                <div class="timeline-label">Attribution document</div>
                                <div class="timeline-value">
                                    {{ \Carbon\Carbon::parse($demande->date_attribution)->format('d/m/Y à H:i') }}
                                    — Doc N° {{ $demande->numero_document }}
                                    @if($demande->attribue_par)
                                        @php $attribPar = \App\Models\User::find($demande->attribue_par); @endphp
                                        @if($attribPar) — {{ $attribPar->getNomPrenom() }} @endif
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endif
                        @if($demande->retire)
                        <li class="timeline-item">
                            <div class="timeline-dot" style="background:#dc3545;"></div>
                            <div class="timeline-content">
                                <div class="timeline-label">Retrait</div>
                                <div class="timeline-value">
                                    {{ $demande->retire_le ? \Carbon\Carbon::parse($demande->retire_le)->format('d/m/Y à H:i') : '—' }}
                                    @if($demande->retire_par)
                                        @php $retirePar = \App\Models\User::find($demande->retire_par); @endphp
                                        @if($retirePar) — {{ $retirePar->getNomPrenom() }} @endif
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>{{-- fin contenu principal --}}
    </div>{{-- fin show-grid --}}
</div>{{-- fin container --}}

{{-- ══ MODALS ══ --}}

{{-- Confirmation approbation --}}
<div class="modal fade" id="confirmApprovalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">⚠️ Confirmation d'approbation</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Vous êtes sur le point <strong>d'approuver définitivement</strong> cette demande.</p>
                <p class="text-danger">Cette action est <strong>irréversible</strong>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form action="{{ route('approuver.simple', $demande->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">✅ Confirmer</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Reset document --}}
<div class="modal fade" id="resetDocModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">⚠️ Réinitialiser le document</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Vous allez <strong>supprimer le numéro de document et les dates</strong>.</p>
                <p class="text-danger"><strong>La demande repassera en attente d'attribution.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form action="{{ route('demandes.reset-document', $demande->id) }}" method="POST">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-danger">
                        <i class="la la-undo"></i> Confirmer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal post-création --}}
<div class="modal fade" id="postCreateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Dossier enregistré</h5>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3">Le dossier a été enregistré avec succès.</p>
                <p>Que souhaitez-vous faire ?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="{{ route('demandes.create') }}" class="btn btn-outline-primary">
                    ➕ Nouveau dossier
                </a>
                <button type="button" class="btn btn-success" data-dismiss="modal">
                    📂 Traiter ce dossier
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Alertes contentieux + watchlist (inchangées) --}}
@if(session('contentieux_danger'))
<div id="ctx-danger-border" style="position:fixed;top:0;left:0;width:100vw;height:100vh;border:18px solid #dc3545;z-index:9998;pointer-events:none;animation:ctxBlink .7s infinite alternate;"></div>
<audio id="ctx-alert-sound" loop preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
</audio>
<div class="modal fade show" id="ctxAlertModal" tabindex="-1" style="display:block;background:rgba(0,0,0,.88);z-index:9999;">
    <div class="modal-dialog modal-dialog-centered modal-lg shadow-lg">
        <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header py-3" style="background:linear-gradient(45deg,#7f0000,#dc3545);">
                <h4 class="modal-title text-white font-weight-black w-100 text-center">
                    <i class="la la-exclamation-triangle" style="animation:ctxBounce .5s infinite alternate;display:inline-block;"></i>
                    &nbsp; APPROBATION BLOQUÉE — CONTENTIEUX DÉTECTÉ
                </h4>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="la la-ban text-danger" style="font-size:5rem;"></i>
                <h3 class="text-danger font-weight-black mb-2 mt-2">CET IMPÉTRANT A UN DOSSIER EN CONTENTIEUX</h3>
                <p class="lead text-dark mb-3">
                    L'impétrant <strong>{{ $demande->impetrant?->nom }} {{ $demande->impetrant?->prenom }}</strong>
                    possède un ou plusieurs dossiers actifs au contentieux.
                </p>
                <div class="text-left p-3 rounded mb-4" style="background:#fff5f5;border-left:4px solid #dc3545;">
                    <ul class="list-unstyled mb-0">
                        @foreach(session('contentieux_details', []) as $detail)
                        <li class="py-2 px-3 mb-1 rounded" style="background:rgba(220,53,69,.07);border-left:3px solid #dc3545;">
                            <i class="la la-gavel text-danger mr-2"></i><span class="font-weight-bold">{{ $detail }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="d-flex flex-wrap justify-content-center mb-3">
                    @foreach(session('contentieux_demandes', []) as $ctxDemande)
                    <a href="{{ route('demandes.show', $ctxDemande['id']) }}" class="btn btn-outline-danger btn-sm mr-1" target="_blank">
                        Voir dossier N°{{ $ctxDemande['uuid'] }}
                    </a>
                    @endforeach
                </div>
                <button type="button" class="btn btn-dark btn-lg px-5 font-weight-bold" onclick="dismissCtxAlert()">
                    <i class="la la-check"></i> J'ai pris connaissance — Fermer
                </button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var audio = document.getElementById('ctx-alert-sound');
    var play = audio.play();
    if (play !== undefined) play.catch(() => { document.addEventListener('click', () => audio.play(), {once:true}); });
    document.body.style.overflow = 'hidden';
});
function dismissCtxAlert() {
    document.getElementById('ctx-alert-sound').pause();
    document.getElementById('ctx-danger-border').style.display = 'none';
    document.getElementById('ctxAlertModal').style.display = 'none';
    document.body.style.overflow = '';
}
</script>
@endif

@if(session('just_created'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(!session('watchlist_danger'))
    $('#postCreateModal').modal({backdrop:'static',keyboard:false});
    @endif
});
function dismissSecurityAlert() {
    document.getElementById('danger-border').style.display = 'none';
    document.getElementById('securityAlertModal').style.display = 'none';
    var audio = document.getElementById('alert-sound');
    if(audio) audio.pause();
    $('#postCreateModal').modal({backdrop:'static',keyboard:false});
}
</script>
@if(session('watchlist_danger'))
<div id="danger-border" style="position:fixed;top:0;left:0;width:100vw;height:100vh;border:20px solid #dc3545;z-index:9998;pointer-events:none;animation:alertBlink .6s infinite alternate;"></div>
<div class="modal fade show" id="securityAlertModal" tabindex="-1" style="display:block;background:rgba(0,0,0,.85);z-index:9999;">
    <div class="modal-dialog modal-dialog-centered shadow-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle bounce-icon me-2"></i> ALERTE DE SÉCURITÉ : WATCHLIST
                </h5>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-user-secret text-danger" style="font-size:4.5rem;"></i>
                <h2 class="text-danger fw-black mt-2">INDIVIDU SUSPECT</h2>
                <p class="lead fw-bold">Une correspondance a été détectée lors du profilage.</p>
                <div class="text-start p-3 rounded mb-3">
                    <ul class="matching-list small fw-bold text-dark">
                        @foreach(session('matching_details', []) as $detail)
                        <li><i class="fas fa-fingerprint me-2 text-danger"></i> {{ $detail }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn btn-confirm w-100 py-3 fw-bold mt-2" onclick="dismissSecurityAlert()">
                    <i class="fas fa-user-check me-2"></i> Accuser réception du signalement
                </button>
            </div>
        </div>
    </div>
</div>
<audio id="alert-sound" loop preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/951/951-preview.mp3" type="audio/mpeg">
</audio>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var audio = document.getElementById('alert-sound');
    var p = audio.play();
    if (p !== undefined) p.catch(error => { document.addEventListener('click', () => { audio.play(); }, {once:true}); });
});
</script>
@endif
@endif

{{-- Modal zoom photo sidebar --}}
<div class="modal fade" id="modalSidebarPhoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content" style="border-radius:14px; overflow:hidden; background:#000;">
            <div class="modal-header py-2" style="background:#111; border:none;">
                <div>
                    <span id="modal-sidebar-photo-uuid" class="text-white font-weight-bold" style="font-size:14px;"></span>
                    <span id="modal-sidebar-photo-statut" class="text-muted ml-2" style="font-size:12px;"></span>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity:1;">
                    <span>&times;</span>
                </button>
            </div>
            <img id="modal-sidebar-photo-img" src="" alt="Photo"
                 style="width:100%; max-height:420px; object-fit:contain; display:block; background:#111;">
        </div>
    </div>
</div>

        </div>{{-- content-body --}}
    </div>{{-- content-wrapper --}}
</div>{{-- app-content --}}
@endsection

@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('res/app-assets/js/scripts/forms/select/form-select2.min.js')}}"></script>
<script>
// ── Galerie photos sidebar ────────────────────────────────────────────────
$(document).ready(function() {
    // Clic sur grande photo sidebar = zoom
    var mp = document.getElementById('sidebar-main-photo');
    if (mp) {
        mp.style.cursor = 'zoom-in';
        mp.addEventListener('click', function() {
            document.getElementById('modal-sidebar-photo-img').src = this.src;
            document.getElementById('modal-sidebar-photo-uuid').textContent = '';
            document.getElementById('modal-sidebar-photo-statut').textContent = '';
            $('#modalSidebarPhoto').modal('show');
        });
    }
});

function sidebarShowPhoto(src, uuid, statut, el) {
    // Ouvrir directement le modal agrandi
    document.getElementById('modal-sidebar-photo-img').src   = src;
    document.getElementById('modal-sidebar-photo-uuid').textContent   = 'N° ' + uuid;
    document.getElementById('modal-sidebar-photo-statut').textContent = statut;
    $('#modalSidebarPhoto').modal('show');

    // Mettre aussi à jour la grande photo sidebar
    var mainPhoto = document.getElementById('sidebar-main-photo');
    if (mainPhoto && mainPhoto.tagName === 'IMG') {
        mainPhoto.src = src;
    }

    // Bordure active
    document.querySelectorAll('.gallery-thumb').forEach(function(t) {
        t.style.borderColor = '#dee2e6';
    });
    if (el) {
        var thumb = el.querySelector('.gallery-thumb');
        if (thumb) thumb.style.borderColor = '#1E9FF2';
    }
}


</script>
@endsection