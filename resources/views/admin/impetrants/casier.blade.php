@extends('admin.layouts.app')
@section('title') Casier – {{ $impetrant->nom }} {{ $impetrant->prenom }} @endsection

@section('content')
<style>
    :root {
        --primary: #4834d4;
        --danger:  #eb4d4b;
        --warning: #f0932b;
        --success: #27ae60;
        --bg:      #f4f7fa;
    }
    .casier-wrapper { padding: 1.5rem; background: var(--bg); border-radius: 15px; }
    .stat-card {
        background: white; border-radius: 14px; padding: 20px;
        border: 1px solid #edf2f9; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        text-align: center; height: 100%;
    }
    .stat-card .stat-number { font-size: 2.2rem; font-weight: 800; line-height: 1; }
    .stat-card .stat-label  { font-size: 11px; text-transform: uppercase; color: #a0aec0; font-weight: 700; margin-top: 4px; }
    .risk-bar-wrap { background: #eee; border-radius: 50px; height: 10px; overflow: hidden; }
    .risk-bar      { height: 100%; border-radius: 50px; transition: width 1s ease; }
    .note-card { border-radius: 10px; padding: 12px 15px; margin-bottom: 10px; border-left: 4px solid; }
    .note-card.info    { background: #eff6ff; border-color: #3b82f6; }
    .note-card.warning { background: #fffbeb; border-color: #f59e0b; }
    .note-card.danger  { background: #fef2f2; border-color: #ef4444; }
    .avatar-casier {
        width: 80px; height: 80px; border-radius: 50%;
        object-fit: cover; border: 3px solid var(--primary);
    }
    .bloc-card {
        background: white; border-radius: 14px; overflow: visible;
        border: 1px solid #edf2f9; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 16px;
    }
    .bloc-card-header {
        padding: 14px 20px; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .infraction-item { padding: 16px 20px; border-bottom: 1px solid #f8fafc; overflow: visible; }
    .infraction-item:last-child { border-bottom: none; }
    .type-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .tag-pill {
        border-radius: 20px; padding: 2px 10px;
        font-size: 10px; font-weight: 700; display: inline-block;
    }
    .stat-type-box {
        border-radius: 10px; padding: 10px 16px;
        text-align: center; min-width: 80px;
    }
    @keyframes pulse {
        0%,100% { opacity:1; transform:scale(1); }
        50%      { opacity:.6; transform:scale(1.3); }
    }
</style>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <div class="casier-wrapper">

                {{-- ══════════════════════════════════════ --}}
                {{-- HEADER IMPÉTRANT                       --}}
                {{-- ══════════════════════════════════════ --}}
                <div class="bloc-card mb-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap p-3" style="gap:15px;">
                        <div class="d-flex align-items-center" style="gap:15px;">
                            @php
                                $photo = $impetrant->demandes->first()?->photo;
                                $totalPhotos = $demandes->whereNotNull('photo')->where('photo','!=','')->count();
                            @endphp
                            <div style="position:relative;">
                                @if($photo)
                                    <img src="{{ asset('app/'.$photo) }}" class="avatar-casier shadow-sm">
                                @else
                                    <div class="avatar-casier shadow-sm d-flex align-items-center justify-content-center"
                                         style="background:#e2e8f0;">
                                        <i class="feather icon-user" style="font-size:2rem;color:#94a3b8;"></i>
                                    </div>
                                @endif
                                @if($totalPhotos > 0)
                                <button onclick="document.getElementById('modal-photos').style.display='flex'"
                                        style="position:absolute;bottom:-8px;right:-8px;width:26px;height:26px;border-radius:50%;background:#4834d4;border:2px solid white;color:white;font-size:11px;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                                        title="Voir toutes les photos">
                                    <i class="feather icon-image" style="font-size:11px;"></i>
                                </button>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-weight-bold mb-0 text-uppercase">
                                    {{ $impetrant->nom }} {{ $impetrant->prenom }}
                                </h4>
                                <small class="text-muted">
                                    Né(e) le {{ \Carbon\Carbon::parse($impetrant->date_naissance)->format('d/m/Y') }}
                                    @if($impetrant->lieu_naissance) — {{ $impetrant->lieu_naissance }} @endif
                                </small><br>
                                <small class="text-muted">{{ $impetrant->pays?->lib_pays ?? '—' }}</small>
                                @if($totalPhotos > 0)
                                <br>
                                <button onclick="document.getElementById('modal-photos').style.display='flex'"
                                        style="background:none;border:none;padding:0;color:#4834d4;font-size:11px;cursor:pointer;margin-top:2px;">
                                    <i class="feather icon-image" style="font-size:11px;"></i>
                                    {{ $totalPhotos }} photo(s) disponible(s)
                                </button>
                                @endif
                            </div>
                        </div>
                        <div style="min-width:200px;">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="font-weight-bold text-muted">Score de risque</small>
                                <small class="font-weight-bold text-{{ $niveauRisque['color'] }}">
                                    {{ $niveauRisque['label'] }} — {{ $scoreRisque }}/100
                                </small>
                            </div>
                            <div class="risk-bar-wrap">
                                <div class="risk-bar bg-{{ $niveauRisque['color'] }}" style="width:{{ $scoreRisque }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 mb-1">
                                <small class="font-weight-bold text-muted">Score d'indiscipline</small>
                                <small class="font-weight-bold" style="color:{{ $scoreIndiscipline >= 70 ? '#dc2626' : ($scoreIndiscipline >= 40 ? '#d97706' : '#16a34a') }}">
                                    {{ $scoreIndiscipline }}/100
                                </small>
                            </div>
                            <div class="risk-bar-wrap">
                                <div class="risk-bar" style="width:{{ $scoreIndiscipline }}%; background:{{ $scoreIndiscipline >= 70 ? '#dc2626' : ($scoreIndiscipline >= 40 ? '#d97706' : '#16a34a') }};"></div>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn btn-link btn-sm text-muted mt-2 p-0">
                                <i class="feather icon-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════════ --}}
                {{-- STATS CARDS                            --}}
                {{-- ══════════════════════════════════════ --}}
                <div class="row mb-3">
                    @php
                        $stats = [
                            ['val' => $demandes->count(),            'label' => 'Demandes totales',           'color' => 'primary'],
                            ['val' => $totalContentieux,             'label' => 'Fois au contentieux',        'color' => 'danger'],
                            ['val' => $demandesExpirees,             'label' => 'Docs expirés sans renouv.',  'color' => 'warning'],
                            ['val' => $fichesExpirees,               'label' => 'Fiches expirées sans suite', 'color' => 'warning'],
                            ['val' => $watchlistMatches->count(),    'label' => 'Watchlist',                  'color' => $watchlistMatches->count() > 0 ? 'danger' : 'success'],
                            ['val' => $documentsExpires->count(),    'label' => 'Documents expirés',          'color' => $documentsExpires->count() > 0 ? 'warning' : 'success'],
                            ['val' => $totalInfractions,             'label' => 'Total infractions',          'color' => $totalInfractions > 0 ? 'danger' : 'success'],
                        ];
                    @endphp
                    @foreach($stats as $s)
                    <div class="col-6 col-md mb-2">
                        <div class="stat-card">
                            <div class="stat-number text-{{ $s['color'] }}">
                                {{ $s['val'] }}
                                @if($s['label'] === 'Watchlist' && $s['val'] > 0)
                                    <span style="width:8px;height:8px;background:#eb4d4b;border-radius:50%;display:inline-block;animation:pulse 1.5s infinite;"></span>
                                @endif
                            </div>
                            <div class="stat-label">{{ $s['label'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- ══════════════════════════════════════ --}}
                {{-- CONTENU PRINCIPAL                      --}}
                {{-- ══════════════════════════════════════ --}}
                <div class="row">

                    {{-- COLONNE GAUCHE (8) --}}
                    <div class="col-md-8">

                        {{-- Demandes au contentieux --}}
                        @if($demandesEnContentieux->count() > 0)
                        <div class="bloc-card">
                            <div class="bloc-card-header">
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-danger mr-2">!</span>
                                    <strong class="text-danger">Demandes actuellement au contentieux</strong>
                                </div>
                            </div>
                            <table class="table table-sm mb-0">
                                <thead class="thead-light">
                                    <tr><th>#</th><th>Type</th><th>Date</th><th>Statut</th><th></th></tr>
                                </thead>
                                <tbody>
                                    @foreach($demandesEnContentieux as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->type_demande }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->date_demande)->format('d/m/Y') }}</td>
                                        <td><span class="badge badge-danger">Au contentieux</span></td>
                                        <td>
                                            <a href="{{ route('demandes.show', $d->id) }}" class="btn btn-sm btn-outline-primary py-0">
                                                <i class="feather icon-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        {{-- Historique demandes --}}
                        <div class="bloc-card">
                            <div class="bloc-card-header">
                                <strong><i class="feather icon-folder mr-1"></i> Historique complet des demandes</strong>
                                <span class="badge badge-secondary" style="border-radius:20px;">{{ $demandes->count() }}</span>
                            </div>
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-light">
                                    <tr><th>#</th><th>Type</th><th>Date</th><th>Statut</th><th>Expiration</th><th></th></tr>
                                </thead>
                                <tbody>
                                    @forelse($demandes->sortByDesc('created_at') as $d)
                                    @php
                                        $badgeColor = match($d->statut_demande) {
                                            'Approuvée'                => 'success',
                                            'Envoyée au contentieux'   => 'danger',
                                            "En attente d'approbation" => 'warning',
                                            default                    => 'secondary',
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td><small>{{ $d->type_demande }}</small></td>
                                        <td><small>{{ \Carbon\Carbon::parse($d->date_demande)->format('d/m/Y') }}</small></td>
                                        <td>
                                            <span class="badge badge-{{ $badgeColor }}" style="font-size:9px;">
                                                {{ $d->statut_demande }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($d->date_expiration)
                                                @php $exp = \Carbon\Carbon::parse($d->date_expiration); @endphp
                                                <small class="{{ $exp->isPast() ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                                    {{ $exp->format('d/m/Y') }}
                                                    @if($exp->isPast()) <i class="feather icon-alert-circle"></i> @endif
                                                </small>
                                            @else
                                                <small class="text-muted">—</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('demandes.show', $d->id) }}" class="btn btn-sm btn-outline-primary py-0">
                                                <i class="feather icon-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="text-center text-muted py-3">Aucune demande</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Watchlist --}}
                        @if($watchlistMatches->count() > 0)
                        <div class="bloc-card" style="border-left:4px solid #eb4d4b;">
                            <div class="bloc-card-header">
                                <div class="d-flex align-items-center">
                                    <span style="width:10px;height:10px;background:#eb4d4b;border-radius:50%;display:inline-block;animation:pulse 1.5s infinite;margin-right:8px;"></span>
                                    <strong class="text-danger">Correspondances Watchlist détectées</strong>
                                </div>
                                <span class="badge badge-danger" style="border-radius:20px;">{{ $watchlistMatches->count() }}</span>
                            </div>
                            <div class="p-3">
                                @foreach($watchlistMatches as $w)
                                <div class="alert alert-danger py-2 mb-2" style="border-radius:10px;font-size:12px;">
                                    <strong>{{ $w->nom }} {{ $w->prenom }}</strong>
                                    — {{ $w->motif ?? 'Motif non précisé' }}
                                    @if($w->date_naissance)
                                        — Né(e) le {{ \Carbon\Carbon::parse($w->date_naissance)->format('d/m/Y') }}
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Documents expirés --}}
                        @if($documentsExpires->count() > 0)
                        <div class="bloc-card">
                            <div class="bloc-card-header">
                                <strong class="text-warning">
                                    <i class="feather icon-file-text mr-1"></i> Documents expirés
                                </strong>
                                <span class="badge badge-warning" style="border-radius:20px;">{{ $documentsExpires->count() }}</span>
                            </div>
                            <table class="table table-sm mb-0">
                                <thead class="thead-light">
                                    <tr><th>Type</th><th>Numéro</th><th>Expiration</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($documentsExpires as $doc)
                                    <tr>
                                        <td><small>{{ $doc->type_document }}</small></td>
                                        <td><small>{{ $doc->numero_document }}</small></td>
                                        <td><small class="text-danger font-weight-bold">{{ \Carbon\Carbon::parse($doc->date_expiration)->format('d/m/Y') }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        {{-- Bilan indiscipline --}}
                        <div class="bloc-card">
                            <div class="bloc-card-header">
                                <strong><i class="feather icon-alert-triangle text-danger mr-1"></i> Bilan d'indiscipline</strong>
                                <small class="text-muted">{{ $totalInfractions }} infraction(s)</small>
                            </div>
                            <div class="p-3">
                                <div class="d-flex align-items-center flex-wrap" style="gap:16px;">
                                    <div class="text-center" style="min-width:120px;">
                                        <div style="font-size:2.5rem;font-weight:800;line-height:1;color:{{ $scoreIndiscipline >= 70 ? '#dc2626' : ($scoreIndiscipline >= 40 ? '#d97706' : '#16a34a') }}">
                                            {{ $scoreIndiscipline }}<span style="font-size:1rem;">/100</span>
                                        </div>
                                        <div style="background:#eee;border-radius:50px;height:8px;margin:8px 0 4px;">
                                            <div style="height:100%;border-radius:50px;width:{{ $scoreIndiscipline }}%;background:{{ $scoreIndiscipline >= 70 ? '#dc2626' : ($scoreIndiscipline >= 40 ? '#d97706' : '#16a34a') }};transition:width 1s ease;"></div>
                                        </div>
                                        <small class="text-muted" style="font-size:10px;">Score d'indiscipline</small>
                                    </div>
                                    @php $parType = $infractions->groupBy('type'); @endphp
                                    <div class="d-flex flex-wrap" style="gap:10px;">
                                        @foreach([
                                            'contentieux'                    => ['label'=>'Contentieux',  'color'=>'#dc2626','bg'=>'#fef2f2'],
                                            'expiration_sans_renouvellement' => ['label'=>'Exp. carte',   'color'=>'#d97706','bg'=>'#fffbeb'],
                                            'demande_expiree_sans_suite'     => ['label'=>'Exp. demande', 'color'=>'#7c3aed','bg'=>'#f5f3ff'],
                                            'manuelle'                       => ['label'=>'Manuelles',    'color'=>'#0369a1','bg'=>'#eff6ff'],
                                        ] as $type => $meta)
                                        <div class="stat-type-box" style="background:{{ $meta['bg'] }};">
                                            <div style="font-size:1.4rem;font-weight:800;color:{{ $meta['color'] }};">
                                                {{ $parType->get($type)?->count() ?? 0 }}
                                            </div>
                                            <div style="font-size:10px;color:{{ $meta['color'] }};font-weight:600;">{{ $meta['label'] }}</div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Liste infractions --}}
                        <div class="bloc-card">
                            <div class="bloc-card-header">
                                <strong><i class="feather icon-list mr-1"></i> Historique des infractions</strong>
                                <span class="badge badge-danger" style="border-radius:20px;">{{ $totalInfractions }}</span>
                            </div>
                            @forelse($infractions as $inf)
                            @php
                                $gc = match($inf->gravite) {
                                    'grave'  => ['bg'=>'#fef2f2','color'=>'#dc2626','border'=>'#fecaca'],
                                    'moyen'  => ['bg'=>'#fffbeb','color'=>'#d97706','border'=>'#fde68a'],
                                    default  => ['bg'=>'#f0fdf4','color'=>'#16a34a','border'=>'#bbf7d0'],
                                };
                                $sc = match($inf->statut) {
                                    'resolu' => ['bg'=>'#f0fdf4','color'=>'#16a34a'],
                                    'classe' => ['bg'=>'#f1f5f9','color'=>'#64748b'],
                                    default  => ['bg'=>'#fef2f2','color'=>'#dc2626'],
                                };
                                $ti = match($inf->type) {
                                    'contentieux'                    => 'icon-alert-octagon',
                                    'expiration_sans_renouvellement' => 'icon-clock',
                                    'demande_expiree_sans_suite'     => 'icon-file-minus',
                                    default                          => 'icon-edit-3',
                                };
                            @endphp
                            <div class="infraction-item">
                                <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:10px;">
                                    <div class="d-flex align-items-start" style="gap:12px;">
                                        <div class="type-icon" style="background:{{ $gc['bg'] }};">
                                            <i class="feather {{ $ti }}" style="color:{{ $gc['color'] }};font-size:16px;"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center flex-wrap" style="gap:6px;">
                                                <strong style="font-size:13px;">{{ $inf->typeLabel() }}</strong>
                                                <span class="tag-pill" style="background:{{ $gc['bg'] }};color:{{ $gc['color'] }};border:1px solid {{ $gc['border'] }};">
                                                    {{ strtoupper($inf->graviteLabel()) }}
                                                </span>
                                                @if($inf->auto_generee)
                                                <span class="tag-pill" style="background:#f1f5f9;color:#94a3b8;">AUTO</span>
                                                @endif
                                            </div>
                                            <p style="font-size:12px;color:#64748b;margin:4px 0 0;">{{ $inf->motif }}</p>
                                            <div style="font-size:11px;color:#94a3b8;margin-top:4px;">
                                                <i class="feather icon-calendar" style="font-size:10px;"></i>
                                                {{ $inf->date_infraction->format('d/m/Y') }}
                                                @if($inf->demande)
                                                &nbsp;·&nbsp;<i class="feather icon-file-text" style="font-size:10px;"></i>
                                                Réf. {{ $inf->demande->uuid }}
                                                @endif
                                                @if($inf->user)
                                                &nbsp;·&nbsp;<i class="feather icon-user" style="font-size:10px;"></i>
                                                {{ $inf->user->prenom }} {{ $inf->user->nom }}
                                                @endif
                                            </div>

                                            {{-- Preuves existantes --}}
                                            @if($inf->preuves->count() > 0)
                                            <div class="d-flex flex-wrap mt-2" style="gap:8px;">
                                                @foreach($inf->preuves as $preuve)
                                                <div style="position:relative;">
                                                    <img src="{{ asset('storage/'.$preuve->chemin_fichier) }}"
                                                         onclick="ouvrirLightbox('{{ asset('storage/'.$preuve->chemin_fichier) }}', '{{ $inf->typeLabel() }}')"
                                                         style="width:60px;height:60px;object-fit:cover;border-radius:8px;border:2px solid #edf2f9;cursor:pointer;transition:0.2s;"
                                                         onmouseover="this.style.borderColor='#4834d4'"
                                                         onmouseout="this.style.borderColor='#edf2f9'"
                                                         title="{{ $preuve->nom_original }}">
                                                    <form action="{{ route('impetrants.infractions.preuves.delete', $preuve->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Supprimer cette preuve ?')"
                                                          style="position:absolute;top:-6px;right:-6px;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                                style="width:18px;height:18px;border-radius:50%;background:#ef4444;border:none;color:white;font-size:10px;padding:0;display:flex;align-items:center;justify-content:center;cursor:pointer;">×</button>
                                                    </form>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif

                                            {{-- Upload preuves --}}
                                            <div class="mt-2">
                                                <form action="{{ route('impetrants.infractions.preuves.store', $inf->id) }}"
                                                      method="POST" enctype="multipart/form-data"
                                                      class="d-flex align-items-center" style="gap:8px;">
                                                    @csrf
                                                    <label style="cursor:pointer;margin:0;">
                                                        <input type="file" name="preuves[]" multiple accept="image/*"
                                                               style="display:none;"
                                                               onchange="this.nextElementSibling.textContent = this.files.length + ' fichier(s)'; this.closest('form').querySelector('button[type=submit]').style.display='inline-flex';">
                                                        <span style="font-size:11px;color:#4834d4;border:1px dashed #4834d4;border-radius:8px;padding:3px 8px;display:inline-block;">
                                                            <i class="feather icon-paperclip" style="font-size:11px;"></i> Ajouter preuve(s)
                                                        </span>
                                                    </label>
                                                    <span style="font-size:10px;color:#94a3b8;"></span>
                                                    <button type="submit" style="display:none;border-radius:8px;font-size:11px;"
                                                            class="btn btn-primary btn-sm">
                                                        <i class="feather icon-upload" style="font-size:11px;"></i> Envoyer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Statut + actions --}}
                                    <div class="d-flex align-items-center" style="gap:8px;">
                                        <span class="tag-pill" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};padding:4px 12px;">
                                            {{ $inf->statutLabel() }}
                                        </span>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                    style="border-radius:8px;padding:3px 8px;font-size:11px;"
                                                    data-toggle="dropdown">
                                                <i class="feather icon-settings" style="font-size:11px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" style="z-index:9999;">
                                                @foreach(['en_cours'=>'En cours','resolu'=>'Résolu','classe'=>'Classé'] as $val => $lab)
                                                <form action="{{ route('impetrants.infractions.statut', $inf->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="statut" value="{{ $val }}">
                                                    <button type="submit"
                                                            class="dropdown-item {{ $inf->statut === $val ? 'font-weight-bold text-primary' : '' }}"
                                                            style="font-size:12px;">
                                                        @if($inf->statut === $val) ✓ @endif {{ $lab }}
                                                    </button>
                                                </form>
                                                @endforeach
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('impetrants.infractions.delete', $inf->id) }}" method="POST"
                                                      onsubmit="return confirm('Supprimer cette infraction ?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" style="font-size:12px;">
                                                        <i class="feather icon-trash-2"></i> Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5 text-muted">
                                <i class="feather icon-check-circle" style="font-size:2.5rem;color:#16a34a;"></i>
                                <p class="mt-2">Aucune infraction enregistrée</p>
                            </div>
                            @endforelse
                        </div>

                    </div>

                    {{-- COLONNE DROITE (4) --}}
                    <div class="col-md-4">

                        {{-- Notes administratives --}}
                        <div class="bloc-card mb-3">
                            <div class="bloc-card-header">
                                <strong><i class="feather icon-edit-3 mr-1"></i> Notes administratives</strong>
                            </div>
                            <div class="p-3">
                                <form action="{{ route('impetrants.casier.note', $impetrant->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <textarea name="note" class="form-control" rows="3"
                                            placeholder="Ajouter une observation..."
                                            style="border-radius:10px;font-size:12px;"></textarea>
                                    </div>
                                    <div class="d-flex" style="gap:8px;">
                                        <select name="niveau" class="form-control form-control-sm" style="border-radius:8px;">
                                            <option value="info">ℹ️ Info</option>
                                            <option value="warning">⚠️ Avertissement</option>
                                            <option value="danger">🚨 Alerte</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;white-space:nowrap;">
                                            <i class="feather icon-plus"></i> Ajouter
                                        </button>
                                    </div>
                                </form>
                                <hr>
                                @forelse($notes as $note)
                                <div class="note-card {{ $note->niveau }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <small class="font-weight-bold text-dark">{{ $note->user->name ?? '—' }}</small>
                                        <form action="{{ route('impetrants.casier.note.delete', $note->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-muted" style="font-size:11px;">
                                                <i class="feather icon-x"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="mb-1 mt-1" style="font-size:12px;">{{ $note->note }}</p>
                                    <small class="text-muted" style="font-size:10px;">
                                        {{ $note->created_at->format('d/m/Y à H:i') }}
                                    </small>
                                </div>
                                @empty
                                <p class="text-muted text-center small mt-2">Aucune note pour l'instant.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Ajouter une infraction --}}
                        <div class="bloc-card" style="position:sticky;top:20px;">
                            <div class="bloc-card-header">
                                <strong><i class="feather icon-alert-triangle text-danger mr-1"></i> Ajouter une infraction</strong>
                            </div>
                            <div class="p-3">
                                <form action="{{ route('impetrants.infractions.store', $impetrant->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="small font-weight-bold text-muted">Date de l'infraction</label>
                                        <input type="date" name="date_infraction" class="form-control form-control-sm"
                                               style="border-radius:8px;" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="small font-weight-bold text-muted">Niveau de gravité</label>
                                        <select name="gravite" class="form-control form-control-sm" style="border-radius:8px;" required>
                                            <option value="mineur">🟢 Mineur</option>
                                            <option value="moyen">🟡 Moyen</option>
                                            <option value="grave">🔴 Grave</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="small font-weight-bold text-muted">Demande concernée (optionnel)</label>
                                        <select name="demande_id" class="form-control form-control-sm" style="border-radius:8px;">
                                            <option value="">Aucune</option>
                                            @foreach($demandes as $d)
                                            <option value="{{ $d->id }}">{{ $d->uuid }} — {{ $d->type_demande }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small font-weight-bold text-muted">Motif / Description</label>
                                        <textarea name="motif" class="form-control form-control-sm" rows="4"
                                                  style="border-radius:8px;" placeholder="Décrivez l'infraction..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-block" style="border-radius:8px;">
                                        <i class="feather icon-alert-triangle mr-1"></i> Enregistrer l'infraction
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL TOUTES LES PHOTOS               --}}
{{-- ══════════════════════════════════════ --}}
<div id="modal-photos"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;padding:20px;"
     onclick="if(event.target===this) this.style.display='none'">
    <div style="background:white;border-radius:16px;width:100%;max-width:600px;max-height:85vh;overflow:hidden;display:flex;flex-direction:column;">
        <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <strong><i class="feather icon-image mr-1"></i> Photos de {{ $impetrant->nom }} {{ $impetrant->prenom }}</strong>
            <button onclick="document.getElementById('modal-photos').style.display='none'"
                    style="background:none;border:none;font-size:20px;cursor:pointer;color:#94a3b8;line-height:1;">×</button>
        </div>
        <div style="overflow-y:auto;padding:16px;flex:1;">
            @php $photos = $demandes->whereNotNull('photo')->where('photo','!=','')->sortByDesc('created_at'); @endphp
            @forelse($photos as $d)
            <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom:1px solid #f8fafc;gap:12px;">
                <img src="{{ asset('app/'.$d->photo) }}"
                     onclick="ouvrirLightbox('{{ asset('app/'.$d->photo) }}', '{{ $d->type_demande }} — {{ \Carbon\Carbon::parse($d->date_demande)->format('d/m/Y') }}')"
                     style="width:70px;height:70px;object-fit:cover;border-radius:10px;border:2px solid #edf2f9;cursor:pointer;flex-shrink:0;transition:0.2s;"
                     onmouseover="this.style.borderColor='#4834d4';this.style.transform='scale(1.05)'"
                     onmouseout="this.style.borderColor='#edf2f9';this.style.transform='scale(1)'">
                <div style="flex:1;min-width:0;">
                    <div class="font-weight-bold" style="font-size:13px;">{{ $d->type_demande }}</div>
                    <small class="text-muted">
                        <i class="feather icon-calendar" style="font-size:10px;"></i>
                        {{ \Carbon\Carbon::parse($d->date_demande)->format('d/m/Y') }}
                    </small>
                    @if($d->uuid)
                    <br><small class="text-muted" style="font-size:10px;">Réf. {{ $d->uuid }}</small>
                    @endif
                    <br>
                    @php
                        $bc = match($d->statut_demande) {
                            'Approuvée'                => 'success',
                            'Envoyée au contentieux'   => 'danger',
                            "En attente d'approbation" => 'warning',
                            default                    => 'secondary',
                        };
                    @endphp
                    <span class="badge badge-{{ $bc }}" style="font-size:9px;">{{ $d->statut_demande }}</span>
                </div>
                <button onclick="ouvrirLightbox('{{ asset('app/'.$d->photo) }}', '{{ $d->type_demande }} — {{ \Carbon\Carbon::parse($d->date_demande)->format('d/m/Y') }}')"
                        class="btn btn-outline-primary btn-sm"
                        style="border-radius:8px;flex-shrink:0;"
                        title="Voir en grand">
                    <i class="feather icon-maximize-2" style="font-size:12px;"></i>
                </button>
            </div>
            @empty
            <div class="text-center py-4 text-muted">
                <i class="feather icon-camera" style="font-size:2rem;color:#cbd5e1;"></i>
                <p class="mt-2 small">Aucune photo disponible</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- LIGHTBOX                               --}}
{{-- ══════════════════════════════════════ --}}
<div id="lightbox"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:10000;align-items:center;justify-content:center;flex-direction:column;"
     onclick="if(event.target===this||event.target.id==='lightbox') fermerLightbox()">
    <button onclick="fermerLightbox()"
            style="position:absolute;top:20px;right:24px;background:none;border:none;color:white;font-size:2rem;cursor:pointer;line-height:1;">×</button>
    <img id="lightbox-img" src="" alt=""
         style="max-width:90vw;max-height:80vh;border-radius:12px;object-fit:contain;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
    <p id="lightbox-caption"
       style="color:rgba(255,255,255,0.7);margin-top:12px;font-size:13px;text-align:center;"></p>
</div>

<script>
function ouvrirLightbox(src, caption) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-caption').textContent = caption;
    document.getElementById('lightbox').style.display = 'flex';
    document.getElementById('modal-photos').style.display = 'none';
}

function fermerLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.getElementById('modal-photos').style.display = 'flex';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (document.getElementById('lightbox').style.display === 'flex') {
            fermerLightbox();
        } else if (document.getElementById('modal-photos').style.display === 'flex') {
            document.getElementById('modal-photos').style.display = 'none';
        }
    }
});
</script>

@endsection