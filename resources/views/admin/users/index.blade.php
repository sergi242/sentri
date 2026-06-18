@extends('admin.layouts.app')

@section('title')
    Gestion des Utilisateurs
@endsection

@section('styles')
<style>
    :root {
        --grad-active:   linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --grad-inactive: linear-gradient(135deg, #8e9eab 0%, #52616b 100%);
        --radius: 18px;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .ucw { animation: fadeUp .3s ease backwards; }

    /* ── Filtres ──────────────────────────────────────────────── */
    .filter-card {
        background: #fff; border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        padding: 16px 20px 12px; margin-bottom: 20px;
    }
    .flabel {
        font-size: 10px; font-weight: 700; letter-spacing: .6px;
        text-transform: uppercase; color: #b2bec3;
        margin-bottom: 4px; display: block;
    }
    .filter-card .form-control,
    .filter-card .custom-select {
        font-size: 13px; height: 36px; border-radius: 8px;
        border-color: #e9ecef; background: #f8f9fa;
    }
    .filter-card .form-control:focus,
    .filter-card .custom-select:focus {
        background:#fff; border-color:#667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,.15);
    }
    .badge-filtre {
        display:inline-flex; align-items:center; gap:4px;
        background:#eef0ff; color:#667eea; border:1px solid #d0d6f9;
        border-radius:20px; font-size:11px; padding:2px 10px;
        margin:2px; font-weight:600;
    }

    /* ── Barre résultats ──────────────────────────────────────── */
    .result-bar {
        display:flex; align-items:center;
        justify-content:space-between;
        margin-bottom:16px; flex-wrap:wrap; gap:8px;
    }
    .result-count { font-size:13px; color:#636e72; }
    .result-count strong { color:#2d3436; font-size:15px; }

    /* ── CARTE ────────────────────────────────────────────────── */
    .uc {
        border-radius: var(--radius);
        border: 1px solid rgba(0,0,0,.07);
        background: #fff;
        overflow: hidden;               /* clips the header */
        margin-bottom: 24px;
        transition: transform .22s ease, box-shadow .22s ease;
        position: relative;
    }
    .uc:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 36px rgba(102,126,234,.16) !important;
    }
    .uc.inactive { border-color: #ffd6da; }

    /* Header */
    .uc-head {
        height: 88px;
        background: var(--grad-active);
        position: relative;
    }
    .uc.inactive .uc-head { background: var(--grad-inactive); }

    /* Badge inactif */
    .uc-badge-off {
        position: absolute; top: 8px; left: 10px;
        background: rgba(255,73,97,.82); color:#fff;
        font-size:9px; font-weight:700; letter-spacing:.5px;
        text-transform:uppercase; padding:2px 9px; border-radius:20px;
    }

    /* Bouton ⋯ — PAS de dropdown-toggle (pas de caret) */
    .uc-menu-btn {
        position: absolute; top: 10px; right: 10px;
        width: 30px; height: 30px; border-radius: 8px;
        background: rgba(255,255,255,.22);
        border: 1px solid rgba(255,255,255,.35);
        color: #fff; cursor: pointer; padding:0;
        display:flex; align-items:center; justify-content:center;
        font-size:17px; transition: background .18s;
        /* Supprime la flèche Bootstrap sur dropdown-toggle */
    }
    .uc-menu-btn::after { display:none !important; }   /* retire le caret */
    .uc-menu-btn:hover  { background: rgba(255,255,255,.42); }

    /* Dropdown */
    .uc-drop .dropdown-menu {
        border:none; border-radius:12px;
        box-shadow:0 8px 28px rgba(0,0,0,.13);
        padding:6px; min-width:168px; font-size:13px;
    }
    .uc-drop .dropdown-item {
        border-radius:8px; padding:7px 12px; font-weight:500;
        color:#2d3436; display:flex; align-items:center; gap:8px;
        transition: background .12s, padding-left .12s;
    }
    .uc-drop .dropdown-item:hover { background:#f1f3f9; padding-left:16px; }
    .uc-drop .dropdown-item.text-danger:hover  { background:#fff5f5; }
    .uc-drop .dropdown-item.text-success:hover { background:#f0fff8; }
    .uc-drop .dropdown-divider { margin:4px 0; }

    /* Avatar — chevauchement header/body */
    .uc-avatar-wrap {
        position: absolute;
        bottom: -30px;          /* dépasse du header vers le bas */
        left: 16px;
        z-index: 2;
    }
    .uc-avatar,
    .uc-avatar-ph {
        width: 64px; height: 64px; border-radius: 16px;
        border: 3px solid #fff;
        box-shadow: 0 3px 12px rgba(0,0,0,.14);
        object-fit: cover; background: #f1f3f9;
        display:flex; align-items:center; justify-content:center;
        color:#c8c8c8; font-size:1.7rem;
    }

    /* Corps */
    .uc-body {
        padding: 40px 16px 16px;   /* 40px = avatar (64px/2 + 8px marge) */
    }
    .uc-role {
        font-size:10px; font-weight:700; letter-spacing:.7px;
        text-transform:uppercase; color:#667eea;
        background:rgba(102,126,234,.1);
        padding:2px 9px; border-radius:6px;
        display:inline-block; margin-bottom:6px;
    }
    .uc-name {
        font-size:.95rem; font-weight:800; color:#2d3436;
        line-height:1.25; margin-bottom:2px;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .uc-email {
        font-size:11px; color:#adb5bd; margin-bottom:0;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }

    /* Pied */
    .uc-foot {
        display:grid; grid-template-columns:1fr 1fr;
        border-top:1px dashed #f1f1f1;
        margin-top:12px; padding-top:10px; gap:8px;
    }
    .uc-meta label {
        font-size:9px; font-weight:700; letter-spacing:.5px;
        text-transform:uppercase; color:#ced4da;
        display:block; margin-bottom:1px;
    }
    .uc-meta span { font-size:12px; font-weight:700; color:#2d3436; }
    .on  { color:#28D094 !important; }
    .off { color:#FF4961 !important; }

    /* ── Sélection ────────────────────────────────────────────── */
    .uc-cb {
        position:absolute; top:10px; left:10px; z-index:10; display:none;
    }
    .uc-cb input { width:17px; height:17px; cursor:pointer; accent-color:#667eea; }
    .sel-mode .uc-cb { display:block; }
    .sel-mode .uc    { cursor:pointer; }
    .uc.picked {
        outline:2px solid #667eea;
        box-shadow:0 0 0 5px rgba(102,126,234,.18) !important;
    }

    /* ── Barre sticky ─────────────────────────────────────────── */
    #selBar {
        position:fixed; bottom:0; left:0; right:0;
        background:linear-gradient(90deg,#667eea,#764ba2);
        color:#fff; padding:12px 28px;
        display:none; align-items:center; justify-content:space-between;
        z-index:1060; box-shadow:0 -4px 24px rgba(102,126,234,.4);
    }
    #selBar.on { display:flex; }
    #selBar .btn-light       { border-radius:8px; font-size:13px; font-weight:600; }
    #selBar .btn-outline-light { border-radius:8px; font-size:13px; }
</style>
@endsection

@section('content')
@can("users.view")
<div class="app-content content">
    <div class="content-wrapper">

        {{-- En-tête --}}
        <div class="content-header row mb-3 align-items-center">
            <div class="col-md-7">
                <h2 class="font-weight-bold mb-0">
                    <i class="la la-users" style="color:#667eea"></i>
                    Annuaire des Agents
                </h2>
                <p class="text-muted mb-0" style="font-size:13px">
                    Gestion des accès et profils de la plateforme.
                </p>
            </div>
            <div class="col-md-5 text-md-right">
                @can("users.create")
                <a href="{{ route('users.create') }}" class="btn btn-primary round shadow-sm px-3">
                    <i class="la la-user-plus mr-1"></i> Nouvel Agent
                </a>
                @endcan
            </div>
        </div>

        {{-- Filtres --}}
        <div class="filter-card">
            <form method="GET" action="{{ route('users.index') }}" id="filterForm">
                <div class="row align-items-end">

                    <div class="col-xl-3 col-md-6 mb-2">
                        <span class="flabel"><i class="la la-search"></i> Recherche</span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Nom, prénom, e-mail…"
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-xl-2 col-md-6 mb-2">
                        <span class="flabel"><i class="la la-shield"></i> Rôle</span>
                        <select name="roles_id" class="custom-select" id="selRole">
                            <option value="">— Tous —</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ request('roles_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->lib_role }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6 mb-2">
                        <span class="flabel"><i class="la la-medal"></i> Grade</span>
                        <select name="grades_id" class="custom-select" id="selGrade">
                            <option value="">— Tous —</option>
                            @foreach($grades as $grade)
                            <option value="{{ $grade->id }}"
                                {{ request('grades_id') == $grade->id ? 'selected' : '' }}>
                                {{ $grade->grade }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6 mb-2">
                        <span class="flabel"><i class="la la-toggle-on"></i> État</span>
                        <select name="active" class="custom-select" id="selActive">
                            <option value="">— Tous —</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Actifs</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactifs</option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-md-12 mb-2">
                        <span class="flabel">&nbsp;</span>
                        <div class="d-flex" style="gap:6px">
                            <button type="submit" class="btn btn-primary flex-fill"
                                    style="height:36px;border-radius:8px;font-size:13px;font-weight:600">
                                <i class="la la-filter mr-1"></i> Filtrer
                            </button>
                            @if(request()->hasAny(['search','roles_id','grades_id','active']))
                            <a href="{{ route('users.index') }}"
                               class="btn btn-outline-secondary px-3"
                               style="height:36px;border-radius:8px;line-height:22px"
                               title="Réinitialiser">
                                <i class="la la-times"></i>
                            </a>
                            @endif
                        </div>
                    </div>

                </div>

                @if(request()->hasAny(['search','roles_id','grades_id','active']))
                <div class="mt-2 pt-2" style="border-top:1px dashed #f0f0f0">
                    <small class="text-muted mr-1" style="font-size:11px">Filtres actifs :</small>
                    @if(request('search'))
                        <span class="badge-filtre">
                            <i class="la la-search" style="font-size:10px"></i>
                            "{{ request('search') }}"
                        </span>
                    @endif
                    @if(request('roles_id'))
                        <span class="badge-filtre">
                            <i class="la la-shield" style="font-size:10px"></i>
                            {{ $roles->firstWhere('id', request('roles_id'))?->lib_role }}
                        </span>
                    @endif
                    @if(request('grades_id'))
                        <span class="badge-filtre">
                            <i class="la la-medal" style="font-size:10px"></i>
                            {{ $grades->firstWhere('id', request('grades_id'))?->grade }}
                        </span>
                    @endif
                    @if(request('active') !== null && request('active') !== '')
                        <span class="badge-filtre">
                            {{ request('active') ? 'Actifs seulement' : 'Inactifs seulement' }}
                        </span>
                    @endif
                </div>
                @endif
            </form>
        </div>

        {{-- Barre résultats --}}
        <div class="result-bar">
            <div class="result-count">
                <strong>{{ $users->count() }}</strong> agent(s) trouvé(s)
                @if($users->where('active',0)->count() > 0)
                &nbsp;·&nbsp;
                <span style="color:#FF4961;font-weight:600;font-size:12px">
                    <i class="la la-ban"></i>
                    {{ $users->where('active',0)->count() }} inactif(s)
                </span>
                @endif
            </div>
            <div class="d-flex" style="gap:8px">
                <button type="button" id="btnSel" class="btn btn-outline-secondary btn-sm round">
                    <i class="la la-check-square mr-1"></i> Sélectionner
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm round"
                        onclick="exportPdf(false)">
                    <i class="la la-file-pdf-o mr-1"></i> PDF liste
                </button>
            </div>
        </div>

        {{-- Grille --}}
        <div class="content-body">
            <div class="row" id="grid">

                @forelse($users as $k => $user)
                <div class="col-xl-3 col-lg-4 col-md-6 ucw"
                     style="animation-delay:{{ min($k*.04,.4) }}s"
                     data-id="{{ $user->id }}">

                    {{-- Checkbox --}}
                    <div class="uc-cb">
                        <input type="checkbox" class="rc" value="{{ $user->id }}">
                    </div>

                    <div class="card uc shadow-sm {{ $user->active ? '' : 'inactive' }}"
                         onclick="cardClick(event,{{ $user->id }})">

                        {{-- ── Header ── --}}
                        <div class="uc-head">

                            @if(!$user->active)
                                <span class="uc-badge-off">
                                    <i class="la la-ban"></i> Inactif
                                </span>
                            @endif

                            {{-- Menu dropdown ⋯ --}}
                            <div class="uc-drop dropdown"
                                 style="position:absolute;top:10px;right:10px;z-index:5">
                                <button class="uc-menu-btn dropdown-toggle"
                                        type="button"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        onclick="event.stopPropagation()">
                                    <i class="la la-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item"
                                       href="{{ route('users.show', $user->id) }}"
                                       onclick="event.stopPropagation()">
                                        <i class="la la-eye text-info"></i> Voir le profil
                                    </a>
                                    @can('users.edit')
                                    <a class="dropdown-item"
                                       href="{{ route('users.edit', $user->id) }}"
                                       onclick="event.stopPropagation()">
                                        <i class="la la-edit text-warning"></i> Modifier
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <div class="dropdown-divider"></div>
                                    <form method="POST"
                                          action="{{ route('users.toggle-active', $user->id) }}"
                                          onsubmit="return confirm('{{ $user->active ? 'Désactiver' : 'Activer' }} {{ addslashes($user->getNomPrenom()) }} ?')">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                                class="dropdown-item {{ $user->active ? 'text-danger' : 'text-success' }}"
                                                onclick="event.stopPropagation()">
                                            <i class="la la-{{ $user->active ? 'ban' : 'check-circle' }}"></i>
                                            {{ $user->active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                    @endif
                                    @endcan
                                    @can('users.destroy')
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger a-del"
                                       href="{{ route('users.destroy', $user->id) }}"
                                       onclick="event.stopPropagation()">
                                        <i class="la la-trash"></i> Supprimer
                                    </a>
                                    @endcan
                                </div>
                            </div>

                            {{-- Avatar qui déborde sur le body --}}
                            <div class="uc-avatar-wrap">
                                <a href="{{ route('users.show', $user->id) }}"
                                   onclick="event.stopPropagation()">
                                    @if($user->photo)
                                        <img src="{{ asset('uploads/users/'.$user->photo) }}"
                                             class="uc-avatar" alt="">
                                    @else
                                        <div class="uc-avatar-ph">
                                            <i class="la la-user"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        </div>{{-- /uc-head --}}

                        {{-- ── Corps ── --}}
                        <div class="uc-body">
                            <span class="uc-role">{{ $user->role?->lib_role ?? 'Sans Rôle' }}</span>
                            <div class="uc-name">
                                <a href="{{ route('users.show', $user->id) }}"
                                   style="color:inherit;text-decoration:none"
                                   onclick="event.stopPropagation()">
                                    {{ $user->prenom }} {{ $user->nom }}
                                </a>
                            </div>
                            <p class="uc-email">{{ $user->email }}</p>

                            <div class="uc-foot">
                                <div class="uc-meta">
                                    <label>Grade</label>
                                    <span>{{ $user->grade?->grade ?? '—' }}</span>
                                </div>
                                <div class="uc-meta">
                                    <label>État</label>
                                    <span class="{{ $user->active ? 'on' : 'off' }}">
                                        <i class="la la-{{ $user->active ? 'check-circle' : 'ban' }}"></i>
                                        {{ $user->active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="la la-inbox text-muted"
                       style="font-size:64px;display:block;opacity:.2;margin-bottom:12px"></i>
                    <p class="text-muted mb-2">Aucun agent ne correspond à ces critères.</p>
                    <a href="{{ route('users.index') }}"
                       class="btn btn-outline-secondary btn-sm round">
                        <i class="la la-times mr-1"></i> Réinitialiser
                    </a>
                </div>
                @endforelse

            </div>
        </div>

    </div>
</div>

{{-- Barre sticky sélection --}}
<div id="selBar">
    <div style="font-size:14px;font-weight:600">
        <i class="la la-check-circle mr-2" style="font-size:18px"></i>
        <span id="selN">0</span> agent(s) sélectionné(s)
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        <button class="btn btn-light btn-sm" onclick="exportPdf(true)">
            <i class="la la-file-pdf-o mr-1"></i> Générer liste PDF
        </button>
        <button class="btn btn-outline-light btn-sm" onclick="quitter()">
            <i class="la la-times mr-1"></i> Annuler
        </button>
    </div>
</div>

@endcan
@endsection

@section('scripts')
<script>
$(function () {

    // Auto-submit selects
    $('#selRole,#selGrade,#selActive').on('change', function () {
        $('#filterForm').submit();
    });

    // Checkbox change
    $(document).on('change', '.rc', function () {
        $(this).closest('.ucw').find('.uc').toggleClass('picked', this.checked);
        maj();
    });

    $('#btnSel').on('click', toggleSel);
});

var selMode = false;

function toggleSel() {
    selMode = !selMode;
    if (selMode) {
        $('#grid').addClass('sel-mode');
        $('#btnSel').html('<i class="la la-times mr-1"></i> Annuler');
    } else {
        quitter();
    }
}

function quitter() {
    selMode = false;
    $('.rc').prop('checked', false);
    $('.uc').removeClass('picked');
    $('#grid').removeClass('sel-mode');
    $('#selBar').removeClass('on');
    $('#btnSel').html('<i class="la la-check-square mr-1"></i> Sélectionner');
}

function cardClick(e, id) {
    if (!selMode) return;
    if ($(e.target).closest('a,button,form,.dropdown-menu').length) return;
    var cb = $('.ucw[data-id="'+id+'"]').find('.rc');
    cb.prop('checked', !cb.prop('checked')).trigger('change');
}

function maj() {
    var n = $('.rc:checked').length;
    $('#selN').text(n);
    $('#selBar').toggleClass('on', selMode && n > 0);
}

function exportPdf(sel) {
    var p = new URLSearchParams(window.location.search);
    if (sel) {
        var ids = $('.rc:checked').map(function(){ return $(this).val(); }).get();
        if (!ids.length) { alert('Sélectionnez au moins un agent.'); return; }
        p.set('ids', ids.join(','));
    } else { p.delete('ids'); }
    window.open('{{ route("users.liste-pdf") }}?'+p.toString(), '_blank');
}
</script>
@endsection