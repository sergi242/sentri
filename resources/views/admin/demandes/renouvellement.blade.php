@extends('admin.layouts.app')
@section('title') Renouvellement — {{ $impetrant->nomcomplet() }} @endsection
@section('styles')
<link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
<style>
/* ── Layout 2 colonnes ── */
.renew-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 20px;
    align-items: start;
}
@media(max-width:900px) { .renew-grid { grid-template-columns: 1fr; } }

/* ── Sidebar impétrant ── */
.imp-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    overflow: hidden;
    position: sticky;
    top: 80px;
}
.imp-photo {
    width: 100%; height: 240px;
    object-fit: cover; object-position: top; display: block;
}
.imp-photo-placeholder {
    width: 100%; height: 240px;
    background: linear-gradient(135deg,#e9ecef,#dee2e6);
    display: flex; align-items: center; justify-content:center;
    font-size: 4rem; color: #adb5bd;
}
.imp-body { padding: 16px; }
.imp-name { font-size: 1.05rem; font-weight: 800; color:#212529; line-height:1.3; }
.imp-sub  { font-size: 12px; color:#6c757d; margin-top:3px; }
.flag-inline { width:20px; height:auto; border-radius:3px; border:1px solid #dee2e6; vertical-align:middle; margin-right:5px; }

/* ── Historique dossiers sidebar ── */
.hist-item {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 0; border-bottom: 1px solid #f0f0f0;
    font-size: 12px;
}
.hist-item:last-child { border-bottom: none; }
.hist-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
}

/* ── Section titres ── */
.section-title {
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .4px; color: #495057;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 8px; margin: 0 0 16px;
    display: flex; align-items: center; gap: 8px;
}
.section-title i { color: #1E9FF2; font-size:15px; }

/* ── Cards sections formulaire ── */
.form-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    margin-bottom: 16px;
    overflow: hidden;
}
.form-card-header {
    padding: 12px 20px;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; gap: 8px;
    border-bottom: 1px solid #f0f0f0;
    background: #fafafa;
}
.form-card-body { padding: 16px 20px; }

/* ── Champs disabled stylisés ── */
.form-control[disabled], .form-control:disabled {
    background: #f8f9fa;
    color: #495057;
    border-color: #e9ecef;
    cursor: not-allowed;
}

/* ── Bandeau dérogation ── */
.derogation-banner {
    background: linear-gradient(135deg, #fff3cd, #ffeeba);
    border: 2px solid #FF9149;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 16px;
}

/* ── Bouton submit ── */
.btn-submit-renew {
    background: linear-gradient(135deg,#1E9FF2,#0d6ebc);
    color: #fff; border: none;
    padding: 12px 32px; border-radius: 10px;
    font-size: 15px; font-weight: 700;
    transition: all .2s;
}
.btn-submit-renew:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30,159,242,.35);
    color: #fff;
}
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">

@php
    $derniereDemande = $impetrant->demandes->last();
    $paysImp = $impetrant->pays;
    $flagPath = $paysImp?->code
        ? 'res/flags/'.strtolower(trim($paysImp->code)).'.png'
        : null;
@endphp

<div class="container-fluid py-3">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 font-weight-bold">
                <i class="la la-redo-alt text-warning"></i>
                Renouvellement de titre de séjour
            </h4>
            <small class="text-muted">
                {{ $impetrant->nomcomplet() }}
                @if($paysImp)
                    · {{ $paysImp->lib_pays }}
                @endif
            </small>
        </div>
        <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
            <i class="la la-arrow-left"></i> Retour
        </a>
    </div>

    {{-- ── Bandeau dérogation ── --}}
    @if(session('derogation_requise') || isset($derogation_requise))
    <div class="derogation-banner">
        <h5 class="text-warning mb-2">
            <i class="la la-exclamation-triangle"></i>
            Dérogation administrateur requise
        </h5>
        <p class="mb-2">
            Délai de 6 mois <strong>non atteint</strong> :
            <strong>{{ $jours_ecoules ?? 0 }} j</strong> écoulés sur 183 requis.<br>
            Dernière demande : <strong>{{ $date_derniere ?? '—' }}</strong> —
            Prochain renouvellement normal le :
            <strong class="text-danger">{{ $prochain_dispo ?? '—' }}</strong>
        </p>
            <p class="mb-2 text-warning font-weight-bold">
            En tant qu'administrateur, vous pouvez forcer ce renouvellement.
            Un motif sera journalisé (optionnel).
        </p>
        <div class="form-group mb-0">
            <label class="font-weight-bold">
                Motif de dérogation
            </label>
            <textarea name="motif_derogation" form="formRenouvellement"
                      class="form-control" rows="2"
                      placeholder="Motif justifiant ce renouvellement anticipé (optionnel)..."></textarea>
        </div>
    </div>
    <input type="hidden" name="derogation" form="formRenouvellement" value="1">
    @endif

    <form class="form" id="formRenouvellement"
          method="POST"
          action="{{ route('demandes.renewstore', $impetrant->id) }}"
          enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="force_quittance" id="force_quittance" value="0">

        <div class="renew-grid">

            {{-- ══ SIDEBAR IMPÉTRANT ══ --}}
            <div>
                <div class="imp-card">

                    {{-- Photo actuelle --}}
                    @php
                        $photoActuelle = $derniereDemande?->photo;
                    @endphp
                    @if($photoActuelle)
                        <img src="{{ asset('app/'.$photoActuelle) }}"
                             class="imp-photo" alt="Photo actuelle">
                    @else
                        <div class="imp-photo-placeholder">
                            <i class="la la-user"></i>
                        </div>
                    @endif

                    <div class="imp-body">
                        <div class="imp-name">
                            @if($flagPath && file_exists(public_path($flagPath)))
                                <img src="{{ asset($flagPath) }}" class="flag-inline">
                            @endif
                            {{ strtoupper($impetrant->nom) }} {{ $impetrant->prenom }}
                        </div>
                        <div class="imp-sub">
                            {{ $impetrant->sexe }}
                            @if($impetrant->date_naissance)
                                · {{ date('d/m/Y', strtotime($impetrant->date_naissance)) }}
                            @endif
                        </div>
                        @if($paysImp)
                            <span class="badge badge-primary mt-1" style="font-size:11px;">
                                {{ $paysImp->lib_pays }}
                            </span>
                        @endif

                        <hr class="my-3">

                        {{-- Historique dossiers --}}
                        <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:#6c757d; margin-bottom:8px;">
                            <i class="la la-history"></i> Historique ({{ $impetrant->demandes->count() }})
                        </div>
                        @forelse($impetrant->demandes->sortByDesc('created_at')->take(5) as $dem)
                        @php
                            $dc = match($dem->statut_demande) {
                                'Approuvée','Livrée' => '#28D094',
                                'Rejetée','Envoyée au contentieux' => '#FF4961',
                                default => '#FF9149'
                            };
                        @endphp
                        <div class="hist-item">
                            <div class="hist-dot" style="background:{{ $dc }};"></div>
                            <div style="flex:1; min-width:0;">
                                <div style="font-weight:600; color:#212529;">
                                    {{ $dem->uuid }}
                                    @if($dem->numero_document)
                                        <small class="text-muted">→ {{ $dem->numero_document }}</small>
                                    @endif
                                </div>
                                <div style="color:#6c757d; font-size:11px;">
                                    {{ $dem->date_demande ? date('d/m/Y', strtotime($dem->date_demande)) : '—' }}
                                </div>
                            </div>
                            <a href="{{ route('demandes.show', $dem->id) }}"
                               class="btn btn-xs btn-outline-secondary"
                               style="padding:2px 6px; font-size:10px;"
                               title="Voir">
                                <i class="la la-eye"></i>
                            </a>
                        </div>
                        @empty
                        <p class="text-muted" style="font-size:12px;">Aucun dossier</p>
                        @endforelse

                        @if($impetrant->demandes->count() > 5)
                        <a href="{{ route('impetrants.demandes', $impetrant->id) }}"
                           class="btn btn-outline-primary btn-sm btn-block mt-2" style="font-size:11px;">
                            <i class="la la-folder-open"></i> Voir tous les dossiers
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ══ FORMULAIRE ══ --}}
            <div>

                {{-- ── Identité (lecture seule) ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="la la-user text-primary"></i>
                        <strong>Identité de l'impétrant</strong>
                        <span class="badge badge-light text-muted ml-auto" style="font-size:11px;">
                            <i class="la la-lock"></i> Non modifiable
                        </span>
                    </div>
                    <div class="form-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Nom</label>
                                    <input type="text" class="form-control" value="{{ $impetrant->nom }}" name="nom" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Prénom</label>
                                    <input type="text" class="form-control" value="{{ $impetrant->prenom }}" name="prenom" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Sexe</label>
                                    <select class="form-control" name="sexe" id="sexe" disabled>
                                        <option value="Masculin" {{ $impetrant->sexe === 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                        <option value="Féminin"  {{ $impetrant->sexe === 'Féminin'  ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Date de naissance</label>
                                    <input type="date" class="form-control" value="{{ $impetrant->date_naissance }}" name="date_naissance" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Lieu de naissance</label>
                                    <input type="text" class="form-control" value="{{ $impetrant->lieu_naissance }}" name="lieu_naissance" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;" class="text-muted">Nationalité</label>
                                    <select class="form-control" name="nationalites_id" disabled>
                                        @foreach($pays as $p)
                                            <option value="{{ $p->id }}" {{ $p->id == $impetrant->nationalites_id ? 'selected' : '' }}>
                                                {{ $p->lib_pays }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;" class="text-muted">État civil</label>
                                    <select class="select2-theme form-control" id="etat_civil" name="etat_civil">
                                        @foreach($etatsCivils as $ec)
                                            <option value="{{ $ec }}" {{ $ec == $derniereDemande?->etat_civil ? 'selected' : '' }}>
                                                {{ $ec }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- Conjoint --}}
                        <div class="row nc mt-3" style="display:none;">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;" class="text-muted">Nom du conjoint</label>
                                    <input type="text" class="form-control" id="nom_conjoint"
                                           value="{{ $derniereDemande?->nom_conjoint }}"
                                           name="nom_conjoint" placeholder="Nom du conjoint">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Photo ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="la la-camera text-primary"></i>
                        <strong>Nouvelle photo</strong>
                        <span class="text-danger ml-1">*</span>
                    </div>
                    <div class="form-card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <input type="file" id="photo" class="form-control @error('photo') is-invalid @enderror"
                                           name="photo" accept="image/*" required
                                           onchange="previewNewPhoto(this)">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Photo récente de l'impétrant</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div style="font-size:11px; color:#6c757d; margin-bottom:4px;">Photo actuelle</div>
                                @if($photoActuelle)
                                    <img src="{{ asset('app/'.$photoActuelle) }}"
                                         style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;">
                                @else
                                    <div style="width:80px;height:80px;border-radius:8px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                                        <i class="la la-user text-muted" style="font-size:2rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-3 text-center" id="new-photo-preview" style="display:none;">
                                <div style="font-size:11px; color:#6c757d; margin-bottom:4px;">Nouvelle photo</div>
                                <img id="new-photo-img" src=""
                                     style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #28D094;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Contact ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="la la-map-marker text-primary"></i>
                        <strong>Adresse &amp; Contact</strong>
                    </div>
                    <div class="form-card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Département *</label>
                                    <select class="select2-theme form-control" id="departements_id" name="departements_id">
                                        <option value="">Sélectionner</option>
                                        @foreach($departements as $d)
                                            <option value="{{ $d->id }}"
                                                {{ $derniereDemande?->quartier?->arrondissement?->departement?->id == $d->id ? 'selected' : '' }}>
                                                {{ $d->lib_departement }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Arrondissement *</label>
                                    <select id="arrondissements_id" class="form-control" name="arrondissements_id" required>
                                        <option value="{{ $derniereDemande?->quartier?->arrondissement?->id }}">
                                            {{ $derniereDemande?->quartier?->arrondissement?->lib_arrondissement }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Quartier *</label>
                                    <select id="quartiers_id" class="form-control" name="quartiers_id" required>
                                        <option value="{{ $derniereDemande?->quartier?->id }}">
                                            {{ $derniereDemande?->quartier?->lib_quartier }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Avenue / Rue *</label>
                                    <input type="text" class="form-control" name="avenue_rue" id="avenue_rue"
                                           value="{{ $derniereDemande?->avenue_rue }}" placeholder="Avenue / rue" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">N° domicile *</label>
                                    <input type="text" class="form-control" name="numero_adresse"
                                           value="{{ $derniereDemande?->numero_adresse }}" placeholder="Numéro" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;" class="text-muted">Téléphone *</label>
                                    <input type="text" class="form-control" name="telephone"
                                           value="{{ $derniereDemande?->telephone }}" placeholder="+242..." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;" class="text-muted">Email</label>
                                    <input type="email" class="form-control" name="email"
                                           value="{{ $derniereDemande?->email }}" placeholder="Email">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Parents (lecture seule) ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="la la-users text-primary"></i>
                        <strong>Filiation</strong>
                        <span class="badge badge-light text-muted ml-auto" style="font-size:11px;">
                            <i class="la la-lock"></i> Non modifiable
                        </span>
                    </div>
                    <div class="form-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Nom père</label>
                                    <input type="text" class="form-control" value="{{ $impetrant->nom_pere }}" name="nom_pere" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Prénom père</label>
                                    <input type="text" class="form-control" value="{{ $impetrant->prenom_pere }}" name="prenom_pere" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;" class="text-muted">Nom mère</label>
                                    <input type="text" class="form-control" value="{{ $impetrant->nom_mere }}" name="nom_mere" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;" class="text-muted">Prénom mère</label>
                                    <input type="text" class="form-control" value="{{ $impetrant->prenom_mere }}" name="prenom_mere" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Profession ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="la la-briefcase text-primary"></i>
                        <strong>Profession</strong>
                    </div>
                    <div class="form-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Catégorie socio-prof. *</label>
                                    <select class="form-control" id="categorie_socioprofessionnelle_id" name="categorie_socioprofessionnelle_id" required>
                                        @foreach($categories as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $derniereDemande?->categorie_socioprof_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->categorie }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Profession</label>
                                    <input type="text" class="form-control" name="profession"
                                           value="{{ $derniereDemande?->profession }}" placeholder="Profession">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;" class="text-muted">Employeur *</label>
                                    <select class="select2-theme form-control" id="employeur_id" name="employeur_id">
                                        <option value="">Sélectionner</option>
                                        @foreach($employeurs as $emp)
                                            <option value="{{ $emp->id }}"
                                                {{ $derniereDemande?->employeur_id == $emp->id ? 'selected' : '' }}>
                                                {{ $emp->nom_employeur }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Passeport ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="la la-id-card text-primary"></i>
                        <strong>Passeport</strong>
                    </div>
                    <div class="form-card-body">
                        @include('admin.demandes.partials._bloc_passeport', [
                            'colLabel'          => 'col-md-4',
                            'colField'          => 'col-md-8',
                            'valeurNumero'      => $derniereDemande?->passeport()?->numero_document ?? '',
                            'valeurEmission'    => $derniereDemande?->passeport()?->date_emission ?? '',
                            'valeurExpiration'  => $derniereDemande?->passeport()?->date_expiration ?? '',
                            'valeurDelivrePar'  => $derniereDemande?->passeport()?->emis_par ?? 'République du Congo',
                        ])
                    </div>
                </div>

                {{-- ── Demande ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="la la-file-text text-primary"></i>
                        <strong>Information sur la demande</strong>
                    </div>
                    <div class="form-card-body">
                        <div class="row mb-3">
    <div class="col-md-3">
        <div class="form-group">
            <label style="font-size:12px;" class="text-muted">N° fiche demande *</label>
            <input type="text" class="form-control" name="uuid"
                   value="{{ old('uuid') }}"
                   placeholder="N° fiche" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label style="font-size:12px;" class="text-muted">Type demande *</label>
            <select class="form-control" name="type_demande">
                <option value="Carte de résident temporaire" selected>Carte de résident temporaire</option>
                <option value="Visa">Visa</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label style="font-size:12px;" class="text-muted">Validité *</label>
            <select class="form-control" name="validite">
                @foreach($validites as $v)
                    <option value="{{ $v }}" {{ $v == $derniereDemande?->validite ? 'selected' : '' }}>
                        {{ $v }} an(s)
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label style="font-size:12px;" class="text-muted">Date de la demande *</label>
            <input type="date" class="form-control" name="date_demande"
                   value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
        </div>
    </div>
</div>

                        {{-- Quittance --}}
                        @include('admin.demandes.partials._bloc_quittance', [
                            'colLabel' => 'col-md-4',
                            'colField' => 'col-md-8',
                        ])

                        {{-- Tag + commanditaire --}}
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Opération *</label>
                                    <select class="form-control" name="tag_demande" id="tag_demande">
                                        <option value="IMPRESSION">IMPRESSION</option>
                                        <option value="REPRISE">REPRISE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Agent commanditaire</label>
                                    <select class="select2-theme form-control" name="commanditaire_id">
                                        <option value="">— Aucun —</option>
                                        @foreach($usersActifs ?? [] as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->nom }} {{ $user->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Reprise : numéro + date --}}
                        <div class="row attributed" style="display:none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Numéro attribué</label>
                                    <input type="text" class="form-control" name="numero_document" placeholder="Numéro du document">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px;" class="text-muted">Date d'émission</label>
                                    <input type="date" class="form-control" name="date_attribution">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Submit ── --}}
                <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="la la-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn-submit-renew">
                        <i class="la la-save"></i> Enregistrer le renouvellement
                    </button>
                </div>

            </div>{{-- fin formulaire --}}
        </div>{{-- fin renew-grid --}}
    </form>

</div>{{-- fin container --}}

{{-- Modal quittance déjà utilisée --}}
<div class="modal fade" id="quittanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">⚠️ Quittance déjà utilisée</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="modalPhoto" class="img-fluid rounded border" style="max-height:160px;">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-sm">
                            <tr><th>Nom</th><td id="modalNom"></td></tr>
                            <tr><th>Prénom</th><td id="modalPrenom"></td></tr>
                            <tr><th>Date naissance</th><td id="modalNaissance"></td></tr>
                            <tr><th>Nationalité</th><td id="modalNationalite"></td></tr>
                            <tr><th>N° quittance</th><td id="modalQuittance"></td></tr>
                            <tr><th>Date</th><td id="modalDate"></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="confirmQuittance">Confirmer</button>
            </div>
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
$(function() {

    // Départements → arrondissements → quartiers
    $('#departements_id').on('change', function() {
        var id = $(this).val();
        if (!id) return;
        var route = "{{ route('departements.arrondissements','id') }}".replace('id', id);
        $.get(route, function(data) {
            var out = "<option value=''>Sélectionner</option>";
            data.forEach(function(a) { out += "<option value='" + a.id + "'>" + a.lib_arrondissement + "</option>"; });
            $('#arrondissements_id').html(out);
            $('#quartiers_id').html("<option value=''>Sélectionner</option>");
        });
    });

    $('#arrondissements_id').on('change', function() {
        var id = $(this).val();
        if (!id) return;
        var route = "{{ route('arrondissements.quartiers','id') }}".replace('id', id);
        $.get(route, function(data) {
            var out = "<option value=''>Sélectionner</option>";
            data.forEach(function(q) { out += "<option value='" + q.id + "'>" + q.lib_quartier + "</option>"; });
            $('#quartiers_id').html(out);
        });
    });

    // État civil → conjoint
    $('.nc').hide();
    $('#etat_civil').on('change', function() {
        if ($(this).val() === 'Marié(e)' && $('#sexe').val() === 'Féminin') {
            $('.nc').fadeIn(400);
        } else {
            $('.nc').fadeOut(400);
            $('#nom_conjoint').val('');
        }
    });
    // Init conjoint
    if ($('#etat_civil').val() === 'Marié(e)' && $('#sexe').val() === 'Féminin') {
        $('.nc').show();
    }

    // Tag REPRISE → champs numéro/date
    $('.attributed').hide();
    $('#tag_demande').on('change', function() {
        if ($(this).val() === 'REPRISE') {
            $('.attributed').slideDown(300);
        } else {
            $('.attributed').slideUp(300);
        }
    });

    // Calcul expiration passeport
    $('#date_emission_passeport').on('change', function() {
        var d = new Date($(this).val());
        if (isNaN(d)) return;
        d.setFullYear(d.getFullYear() + 5);
        var dd = String(d.getDate()-1).padStart(2,'0');
        var mm = String(d.getMonth()+1).padStart(2,'0');
        $('#date_expiration_passeport').val(d.getFullYear()+'-'+mm+'-'+dd);
    });
});

// Prévisualisation nouvelle photo
function previewNewPhoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('new-photo-img').src = e.target.result;
            document.getElementById('new-photo-preview').style.display = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Quittance déjà utilisée ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {

    window.quittanceDetectee  = false;
    window.quittanceConfirmee = false;

    var input      = document.getElementById('numero_quittance');
    var form       = document.getElementById('formRenouvellement');
    var btnConfirm = document.getElementById('confirmQuittance');
    var forceInput = document.getElementById('force_quittance');

    if (!input || !form) return;

    input.addEventListener('blur', function() {
        if (!this.value || this.value === 'GRATIS') return;
        if (document.getElementById('hidden_sans_quittance')?.value === '1') return;

        window.quittanceDetectee  = false;
        window.quittanceConfirmee = false;
        forceInput.value = 0;

        fetch("{{ route('demandes.checkQuittance') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ numero_quittance: this.value })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.warning) {
                document.getElementById('quittanceValidMsg')?.classList.remove('d-none');
                return;
            }
            window.quittanceDetectee = true;
            document.getElementById('modalNom').innerText        = data.demande.nom;
            document.getElementById('modalPrenom').innerText     = data.demande.prenom;
            document.getElementById('modalNaissance').innerText  = data.demande.date_naissance;
            document.getElementById('modalNationalite').innerText= data.demande.nationalite;
            document.getElementById('modalQuittance').innerText  = data.demande.numero_quittance;
            document.getElementById('modalDate').innerText       = data.demande.date;
            document.getElementById('modalPhoto').src            = data.demande.photo;
            $('#quittanceModal').modal('show');
        });
    });

    if (btnConfirm) {
        btnConfirm.addEventListener('click', function() {
            window.quittanceConfirmee = true;
            forceInput.value = 1;
            input.setAttribute('readonly', true);
            input.classList.add('bg-light');
            document.getElementById('quittanceLockedMsg')?.classList.remove('d-none');
            $('#quittanceModal').modal('hide');
        });
    }

    form.addEventListener('submit', function(e) {
        if (window.quittanceDetectee && !window.quittanceConfirmee) {
            e.preventDefault();
            if (typeof toastr !== 'undefined')
                toastr.warning("Veuillez confirmer la quittance avant l'enregistrement.");
            return;
        }
        // ── Anti-double-submit ─────────────────────────────────────────
        var btn = form.querySelector('button[type="submit"]');
        if (btn) {
            if (btn.dataset.submitted === '1') {
                e.preventDefault();
                return;
            }
            btn.dataset.submitted = '1';
            btn.disabled = true;
            btn.innerHTML = '<i class="la la-spinner la-spin"></i> Enregistrement...';
        }
    });
});

// ── Toggle quittance (bypass si GRATIS) ────────────────────────────────
function toggleQuittance(mode) {
    var bloc       = document.getElementById('bloc_quittance');
    var gratis     = document.getElementById('bloc_gratis');
    var hidden     = document.getElementById('hidden_sans_quittance');
    var input      = document.getElementById('numero_quittance');
    var forceInput = document.getElementById('force_quittance');

    if (mode === 'sans') {
        if (bloc)   { bloc.style.opacity = '0.5'; bloc.style.pointerEvents = 'none'; }
        if (gratis) gratis.style.display = '';
        if (hidden) hidden.value = '1';
        if (input)  { input.removeAttribute('required'); input.value = 'GRATIS'; }
        if (forceInput) forceInput.value = '1';
        window.quittanceDetectee  = false;
        window.quittanceConfirmee = true;
        document.getElementById('quittanceValidMsg')?.classList.add('d-none');
        document.getElementById('quittanceLockedMsg')?.classList.add('d-none');
    } else {
        if (bloc)   { bloc.style.opacity = '1'; bloc.style.pointerEvents = 'auto'; }
        if (gratis) gratis.style.display = 'none';
        if (hidden) hidden.value = '0';
        if (input && input.value === 'GRATIS') input.value = '';
        if (forceInput) forceInput.value = '0';
        window.quittanceDetectee  = false;
        window.quittanceConfirmee = false;
    }
}
</script>
@endsection