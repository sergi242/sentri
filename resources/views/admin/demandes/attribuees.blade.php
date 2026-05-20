@extends('admin.layouts.app')
@section('title', $status)

@section('styles')
<style>
    :root {
        --primary: #4834d4;
        --bg: #f4f7fa;
    }

    .page-wrapper { padding: 1.5rem; background: var(--bg); min-height: 100vh; }

    .page-header {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        color: white; border-radius: 14px; padding: 22px 28px; margin-bottom: 20px;
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;
    }

    .filter-card {
        background: white; border-radius: 14px; padding: 20px 24px;
        border: 1px solid #edf2f9; margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .filter-label {
        font-size: 11px; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.5px;
        margin-bottom: 5px; display: block;
    }

    .filter-tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;
        border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 600;
    }
    .filter-tag a { color: #94a3b8; text-decoration: none; font-size: 13px; margin-left: 2px; }
    .filter-tag a:hover { color: #dc2626; }

    /* ── Vue Dossier ── */
    .dossier-row {
        background: white; border-radius: 12px; padding: 14px 20px;
        border: 1px solid #edf2f9; margin-bottom: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        transition: 0.2s; display: flex; align-items: center; gap: 16px;
    }
    .dossier-row:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-1px); border-color: #dbeafe; }

    .imp-photo {
        width: 54px; height: 54px; border-radius: 12px;
        object-fit: cover; border: 2px solid #edf2f9; flex-shrink: 0;
    }
    .imp-photo-placeholder {
        width: 54px; height: 54px; border-radius: 12px;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .meta-pill { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: #64748b; }

    .status-badge {
        padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; white-space: nowrap;
    }

    /* ── Vue Carte CRT ── */
    .card-container { width: 380px; height: 240px; perspective: 1000px; margin: 0 auto 8px; }
    .crt-card-inner { position: relative; width: 100%; height: 100%; transition: transform 0.6s; transform-style: preserve-3d; cursor: pointer; }
    .card-container:hover .crt-card-inner { transform: rotateY(180deg); }
    .crt-card-front, .crt-card-back { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.12); }
    .crt-bg { position: absolute; inset: 0; background: url("{{ asset('img/crt/recto.png') }}") no-repeat center / cover; z-index: 1; }
    .crt-photo { position: absolute; top: 72px; left: 21px; width: 84px; height: 105px; object-fit: cover; z-index: 5; border-radius: 4px; }
    .crt-card-back { transform: rotateY(180deg); background: url("{{ asset('img/crt/verso.png') }}") no-repeat center / cover; }
    .crt-text { position: absolute; font-weight: 900; text-transform: uppercase; font-size: 11px; color: #000; z-index: 10; font-family: Arial, sans-serif; line-height: 1.1; }
    .val-doc-num   { top: 64px;  right: 33px; font-size: 12px; }
    .val-nom       { top: 70px;  left: 132px; }
    .val-prenom    { top: 98px;  left: 132px; }
    .val-sexe      { top: 126px; left: 132px; }
    .val-nationalite { top: 151px; left: 132px; }
    .val-date-naiss  { top: 176px; left: 132px; }
    .val-lieu-naiss  { top: 200px; left: 132px; }
    .val-emission    { top: 60px;  left: 48px; }
    .val-expiration  { top: 86px;  left: 48px; }
    .val-profession  { top: 110px; left: 48px; }
    .val-adresse     { top: 138px; left: 48px; font-size: 10px; line-height: 1.5; }
    .adr-line { display: block; text-transform: uppercase; }
    .crt-mrz {
        position: absolute; bottom: 14px; left: 30px; right: 34px;
        font-family: 'Courier New', monospace; font-size: 14px; line-height: 1;
        letter-spacing: 3px; color: #000; font-weight: bold; text-transform: uppercase;
        text-align: justify; text-align-last: justify; overflow: hidden; white-space: nowrap; z-index: 10;
    }
    .stamp-layer { position: absolute; pointer-events: none; }
    .layer-round     { z-index: 15; width: 76px; }
    .layer-nominatif { z-index: 16; width: 125px; }
    .layer-signature { z-index: 17; width: 115px; }

    /* ── Toggle ── */
    .view-toggle { display: flex; background: #f1f5f9; border-radius: 10px; padding: 3px; gap: 3px; }
    .view-toggle button {
        border: none; border-radius: 8px; padding: 6px 16px; font-size: 12px; font-weight: 700;
        cursor: pointer; transition: 0.2s; background: transparent; color: #64748b;
    }
    .view-toggle button.active { background: white; color: #4834d4; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }

    /* ── Checkbox sélection ── */
    .select-checkbox { width: 16px; height: 16px; cursor: pointer; accent-color: #4834d4; flex-shrink: 0; }
    .select-all-bar {
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px;
        padding: 8px 16px; margin-bottom: 12px;
        display: none; align-items: center; justify-content: space-between; gap: 10px;
    }
    .select-all-bar.visible { display: flex; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in { animation: fadeIn 0.3s ease; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <div class="page-wrapper">

                {{-- ══ HEADER ══ --}}
                <div class="page-header">
                    <div>
                        <h4 class="mb-1 font-weight-bold">
                            <i class="la la-id-card mr-2"></i> {{ $status }}
                        </h4>
                        <p class="mb-0 small" style="opacity:.8;">Gestion et impression des cartes de résident</p>
                    </div>
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <span style="background:rgba(255,255,255,0.2);padding:7px 16px;border-radius:20px;font-size:13px;font-weight:700;">
                            <i class="la la-file mr-1"></i> {{ $demandes->total() }} demande(s)
                        </span>
                        <div class="view-toggle">
                            <button id="btn-dossier" class="active" onclick="setView('dossier')">
                                <i class="la la-list"></i> Dossiers
                            </button>
                            <button id="btn-carte" onclick="setView('carte')">
                                <i class="la la-credit-card"></i> Cartes CRT
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ══ FILTRES ══ --}}
                <div class="filter-card">
                    <form method="GET" action="{{ request()->url() }}">
                        <div class="row">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="filter-label">Nom / Prénom</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="border-radius:8px 0 0 8px;background:#f8fafc;border-right:0;">
                                            <i class="la la-search text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="search" class="form-control form-control-sm"
                                           style="border-radius:0 8px 8px 0;"
                                           placeholder="Rechercher..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-2 mb-3 mb-md-0">
                                <label class="filter-label">Nationalité</label>
                                <select name="pays_id" class="form-control form-control-sm" style="border-radius:8px;">
                                    <option value="">Toutes</option>
                                    @foreach($pays as $p)
                                    <option value="{{ $p->id }}" {{ request('pays_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->lib_pays }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 mb-3 mb-md-0">
                                <label class="filter-label">Type de demande</label>
                                <select name="type_demande" class="form-control form-control-sm" style="border-radius:8px;">
                                    <option value="">Tous les types</option>
                                    @foreach($typesDemandes as $type)
                                    <option value="{{ $type }}" {{ request('type_demande') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 mb-3 mb-md-0">
                                <label class="filter-label">Sexe</label>
                                <select name="sexe" class="form-control form-control-sm" style="border-radius:8px;">
                                    <option value="">Tous</option>
                                    <option value="Masculin"  {{ request('sexe') === 'Masculin'  ? 'selected' : '' }}>Masculin</option>
                                    <option value="Féminin"   {{ request('sexe') === 'Féminin'   ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>

                            <div class="col-md-2 mb-3 mb-md-0">
                                <label class="filter-label">Date approbation</label>
                                <input type="date" name="date_approbation" class="form-control form-control-sm"
                                       style="border-radius:8px;" value="{{ request('date_approbation') }}">
                            </div>

                            <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0" style="gap:6px;">
                                <button type="submit" class="btn btn-primary btn-sm btn-block" style="border-radius:8px;">
                                    <i class="la la-search"></i>
                                </button>
                                @if(request()->hasAny(['search','pays_id','type_demande','sexe','date_approbation']))
                                <a href="{{ request()->url() }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;" title="Réinitialiser">
                                    <i class="la la-times"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- Tags actifs --}}
                    @if(request()->hasAny(['search','pays_id','type_demande','sexe','date_approbation']))
                    <div class="d-flex flex-wrap align-items-center mt-3 pt-3" style="border-top:1px solid #f1f5f9;gap:6px;">
                        <small class="text-muted font-weight-bold mr-1">Filtres actifs :</small>
                        @if(request('search'))
                        <span class="filter-tag"><i class="la la-search" style="font-size:11px;"></i>"{{ request('search') }}"<a href="{{ request()->fullUrlWithoutQuery('search') }}">×</a></span>
                        @endif
                        @if(request('pays_id'))
                        <span class="filter-tag"><i class="la la-globe" style="font-size:11px;"></i>{{ $pays->firstWhere('id', request('pays_id'))?->lib_pays }}<a href="{{ request()->fullUrlWithoutQuery('pays_id') }}">×</a></span>
                        @endif
                        @if(request('type_demande'))
                        <span class="filter-tag"><i class="la la-file" style="font-size:11px;"></i>{{ request('type_demande') }}<a href="{{ request()->fullUrlWithoutQuery('type_demande') }}">×</a></span>
                        @endif
                        @if(request('sexe'))
                        <span class="filter-tag"><i class="la la-user" style="font-size:11px;"></i>{{ request('sexe') }}<a href="{{ request()->fullUrlWithoutQuery('sexe') }}">×</a></span>
                        @endif
                        @if(request('date_approbation'))
                        <span class="filter-tag"><i class="la la-calendar" style="font-size:11px;"></i>{{ \Carbon\Carbon::parse(request('date_approbation'))->format('d/m/Y') }}<a href="{{ request()->fullUrlWithoutQuery('date_approbation') }}">×</a></span>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- ══ BARRE SÉLECTION (vue carte) ══ --}}
                <div class="select-all-bar" id="select-bar">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <input type="checkbox" id="checkAll" class="select-checkbox" onchange="toggleAll(this)">
                        <label for="checkAll" class="mb-0 small font-weight-bold" style="cursor:pointer;">
                            Tout sélectionner (<span id="selectedCount">0</span> sélectionné(s))
                        </label>
                    </div>
                    <button onclick="imprimerSelection()" class="btn btn-dark btn-sm" style="border-radius:8px;">
                        <i class="la la-print mr-1"></i> Imprimer la sélection
                    </button>
                </div>

                {{-- ══ RÉSULTATS ══ --}}
                @if($demandes->count() === 0)
                <div class="text-center py-5" style="background:white;border-radius:14px;border:1px solid #edf2f9;">
                    <i class="la la-search" style="font-size:3.5rem;color:#cbd5e1;"></i>
                    <p class="mt-2 mb-3 font-weight-bold text-muted">Aucune demande trouvée</p>
                    <a href="{{ request()->url() }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px;">
                        <i class="la la-times mr-1"></i> Réinitialiser les filtres
                    </a>
                </div>
                @else

                <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                    <small class="text-muted">
                        <strong>{{ $demandes->firstItem() }}–{{ $demandes->lastItem() }}</strong>
                        sur <strong>{{ $demandes->total() }}</strong> demande(s)
                    </small>
                    <small class="text-muted">Page <strong>{{ $demandes->currentPage() }}</strong> / {{ $demandes->lastPage() }}</small>
                </div>

                {{-- VUE DOSSIERS --}}
                <div id="view-dossier" class="fade-in">
                    @foreach($demandes as $demande)
                    @php
                        $badgeColor = match($demande->statut_demande) {
                            'Approuvée'                => ['bg'=>'#f0fdf4','color'=>'#16a34a','border'=>'#bbf7d0'],
                            'Envoyée au contentieux'   => ['bg'=>'#fef2f2','color'=>'#dc2626','border'=>'#fecaca'],
                            "En attente d'approbation" => ['bg'=>'#fffbeb','color'=>'#d97706','border'=>'#fde68a'],
                            default                    => ['bg'=>'#f8fafc','color'=>'#64748b','border'=>'#e2e8f0'],
                        };
                    @endphp
                    <div class="dossier-row">
                        {{-- Photo --}}
                        @if($demande->photo)
                            <img src="{{ asset('app/'.$demande->photo) }}"
                                 class="imp-photo"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="imp-photo-placeholder" style="display:none;">
                                <i class="la la-user" style="font-size:20px;color:#94a3b8;"></i>
                            </div>
                        @else
                            <div class="imp-photo-placeholder">
                                <i class="la la-user" style="font-size:20px;color:#94a3b8;"></i>
                            </div>
                        @endif

                        {{-- Identité --}}
                        <div style="flex:1;min-width:0;">
                            <div class="font-weight-bold text-uppercase" style="font-size:13px;color:#1e293b;">
                                {{ $demande->impetrant?->nom }} {{ $demande->impetrant?->prenom }}
                            </div>
                            <div class="d-flex flex-wrap mt-1" style="gap:10px;">
                                <span class="meta-pill">
                                    <i class="la la-file" style="font-size:11px;"></i>
                                    {{ $demande->type_demande }}
                                </span>
                                @if($demande->impetrant?->pays)
                                <span class="meta-pill">
                                    <i class="la la-globe" style="font-size:11px;"></i>
                                    {{ $demande->impetrant->pays->lib_pays }}
                                </span>
                                @endif
                                @if($demande->date_expiration)
                                <span class="meta-pill">
                                    <i class="la la-calendar" style="font-size:11px;"></i>
                                    Exp. {{ \Carbon\Carbon::parse($demande->date_expiration)->format('d/m/Y') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Statut --}}
                        <span class="status-badge" style="background:{{ $badgeColor['bg'] }};color:{{ $badgeColor['color'] }};border:1px solid {{ $badgeColor['border'] }};">
                            {{ $demande->statut_demande }}
                        </span>

                        {{-- Actions --}}
                        <div class="d-flex" style="gap:6px;flex-shrink:0;">
                            <a href="{{ route('demandes.show', $demande->id) }}"
                               class="btn btn-primary btn-sm" style="border-radius:8px;font-size:12px;">
                                <i class="la la-folder-open"></i> Dossier
                            </a>
                            <a href="{{ route('demandes.generate-pdf', $demande->id) }}"
                               class="btn btn-dark btn-sm" style="border-radius:8px;font-size:12px;" title="Imprimer la carte">
                                <i class="la la-print"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- VUE CARTES CRT --}}
                <div id="view-carte" class="d-none fade-in">
                    <div class="row">
                        @foreach($demandes as $demande)
                        @php
    $impetrant         = $demande->impetrant;
    $nationalite       = $impetrant?->pays?->nationalite ?? 'CONGOLAISE';
    $docNumber         = $demande->numero_document ?? 'N/A';
    $numRue            = trim(($demande->numero_adresse ?? '') . ' ' . ($demande->avenue_rue ?? ''));
    $quartierNom       = $demande->quartier?->lib_quartier ?? 'N/A';
    $arrondissementNom = $demande->quartier?->arrondissement?->lib_arrondissement ?? 'N/A';
    $largeur = 30;
    $l1 = 'RCCOG' . str_pad($demande->numero_document ?? '0', 9, '0', STR_PAD_LEFT);
    $l2 = date('ymd', strtotime($impetrant?->date_naissance ?? 'now')) . 'M' . date('ymd', strtotime($demande->date_expiration ?? 'now')) . strtoupper($demande->impetrant?->pays?->code_iso ?? 'COG');
    $l3 = str_replace(' ', '<', $impetrant?->nom ?? '') . '<<' . str_replace(' ', '<', $impetrant?->prenom ?? '');
    $mrz1 = str_pad(substr($l1, 0, $largeur), $largeur, '<');
    $mrz2 = str_pad(substr($l2, 0, $largeur), $largeur, '<');
    $mrz3 = str_pad(substr($l3, 0, $largeur), $largeur, '<');
@endphp
                        <div class="col-xl-4 col-lg-6 col-12 mb-4">
                            <div class="d-flex flex-column align-items-center">

                                {{-- Checkbox sélection --}}
                                <div class="d-flex align-items-center mb-1" style="gap:8px;align-self:flex-start;padding-left:10px;">
                                    <input type="checkbox" class="select-checkbox carte-check"
                                           data-id="{{ $demande->id }}"
                                           onchange="updateCount()">
                                    <small class="text-muted font-weight-bold text-uppercase" style="font-size:10px;">
                                        {{ $impetrant?->nom }} {{ $impetrant?->prenom }}
                                    </small>
                                </div>

                                {{-- Carte CRT --}}
                                <div class="card-container">
                                    <div class="crt-card-inner">
                                        <div class="crt-card-front">
                                            <div class="crt-bg"></div>
                                            <img src="{{ asset('app/'.$demande->photo) }}" class="crt-photo"
                                                 onerror="this.src='{{ asset('res/app-assets/images/portrait/small/avatar-s-1.png') }}'">
                                            <div class="crt-text val-doc-num">{{ $docNumber }}</div>
                                            <div class="crt-text val-nom">{{ $impetrant?->nom }}</div>
                                            <div class="crt-text val-prenom">{{ $impetrant?->prenom }}</div>
                                            <div class="crt-text val-sexe">{{ substr($impetrant?->sexe ?? 'M', 0, 1) }}</div>
                                            <div class="crt-text val-nationalite">{{ $nationalite }}</div>
                                            <div class="crt-text val-date-naiss">{{ $impetrant?->date_naissance ? date('d/m/Y', strtotime($impetrant->date_naissance)) : '' }}</div>
                                            <div class="crt-text val-lieu-naiss">{{ $impetrant?->lieu_naissance }}</div>
                                            <img src="{{ asset('img/crt/cachet_rond.png') }}" class="stamp-layer layer-round" style="top:143px;left:210px;opacity:.8;">
                                            <img src="{{ asset('img/crt/cachet_nominatif.png') }}" class="stamp-layer layer-nominatif" style="top:192px;left:240px;opacity:.9;">
                                            <img src="{{ asset('img/crt/signature.png') }}" class="stamp-layer layer-signature" style="top:153px;left:240px;">
                                        </div>
                                        <div class="crt-card-back">
                                            <div class="crt-text val-emission">{{ $demande->date_emission ? date('d/m/Y', strtotime($demande->date_emission)) : date('d/m/Y') }}</div>
                                            <div class="crt-text val-expiration">{{ $demande->date_expiration ? date('d/m/Y', strtotime($demande->date_expiration)) : '' }}</div>
                                            <div class="crt-text val-profession">{{ Str::limit($demande->profession ?? 'SANS PROFESSION', 25) }}</div>
                                            <div class="crt-text val-adresse">
                                                <span class="adr-line">{{ $numRue ?: 'NON SPÉCIFIÉE' }}</span>
                                                <span class="adr-line">{{ $quartierNom }}</span>
                                                <span class="adr-line">{{ $arrondissementNom }}</span>
                                            </div>
                                            <div class="crt-mrz">{{ $mrz1 }}<br>{{ $mrz2 }}<br>{{ $mrz3 }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="d-flex mt-2" style="gap:6px;">
                                    <a href="{{ route('demandes.generate-pdf', $demande->id) }}"
                                       class="btn btn-sm btn-dark" style="border-radius:8px;font-size:12px;">
                                        <i class="la la-print mr-1"></i> Imprimer
                                    </a>
                                    <a href="{{ route('demandes.show', $demande->id) }}"
                                       class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px;">
                                        <i class="la la-folder-open"></i> Dossier
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $demandes->appends(request()->query())->links('admin.pagination.pagination') }}
                </div>

                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Toggle vue
function setView(view) {
    const dossier = document.getElementById('view-dossier');
    const carte   = document.getElementById('view-carte');
    const bar     = document.getElementById('select-bar');
    const btnD    = document.getElementById('btn-dossier');
    const btnC    = document.getElementById('btn-carte');

    if (view === 'dossier') {
        dossier.classList.remove('d-none');
        carte.classList.add('d-none');
        bar.classList.remove('visible');
        btnD.classList.add('active');
        btnC.classList.remove('active');
    } else {
        dossier.classList.add('d-none');
        carte.classList.remove('d-none');
        bar.classList.add('visible');
        btnC.classList.add('active');
        btnD.classList.remove('active');
    }
}

// Sélection cartes
function updateCount() {
    const count = document.querySelectorAll('.carte-check:checked').length;
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('checkAll').indeterminate =
        count > 0 && count < document.querySelectorAll('.carte-check').length;
}

function toggleAll(checkbox) {
    document.querySelectorAll('.carte-check').forEach(c => c.checked = checkbox.checked);
    updateCount();
}

// Imprimer la sélection — ouvre chaque PDF dans un nouvel onglet
function imprimerSelection() {
    const ids = [...document.querySelectorAll('.carte-check:checked')].map(c => c.dataset.id);
    if (ids.length === 0) {
        alert('Veuillez sélectionner au moins une carte.');
        return;
    }
    ids.forEach(id => {
        window.open(`/demandes/${id}/generate-pdf`, '_blank');
    });
}
</script>
@endsection