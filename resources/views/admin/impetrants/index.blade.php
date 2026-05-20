@extends('admin.layouts.app')
@section('title') {{ $status }} @endsection

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
    --bg:     #f7f9fc;
    --radius: 10px;
}

/* ── Page header ─────────────────────────────────────────── */
.page-hero {
    background: linear-gradient(135deg, #1a2a3a 0%, #2d4a6a 100%);
    border-radius: 14px; padding: 22px 28px; margin-bottom: 20px;
    position: relative; overflow: hidden;
}
.page-hero::after {
    content: ''; position: absolute; right: -40px; top: -40px;
    width: 200px; height: 200px; border-radius: 50%;
    background: rgba(30,159,242,.08); pointer-events: none;
}

/* ── Filtre card ─────────────────────────────────────────── */
.filter-card {
    background: #fff; border-radius: 12px; padding: 16px 20px;
    border: 1px solid var(--border); margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.filter-card .form-control-sm {
    border-radius: 7px !important; border-color: var(--border) !important;
    font-size: 12px !important;
}
.filter-card .form-control-sm:focus {
    border-color: var(--blue) !important;
    box-shadow: 0 0 0 3px rgba(30,159,242,.1) !important;
}

/* ── Toggle vue ──────────────────────────────────────────── */
.toggle-view-btn {
    width: 34px; height: 34px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid var(--border); background: #fff; cursor: pointer;
    transition: .2s; color: var(--muted); font-size: 14px;
}
.toggle-view-btn.active {
    background: var(--blue); color: #fff; border-color: var(--blue);
    box-shadow: 0 3px 10px rgba(30,159,242,.3);
}

/* ── Tableau ─────────────────────────────────────────────── */
.table-card {
    background: #fff; border-radius: 12px; overflow: hidden;
    border: 1px solid var(--border);
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.table-card thead th {
    background: #f8fafc; font-size: 10px; text-transform: uppercase;
    color: var(--muted); font-weight: 800; letter-spacing: .06em;
    border-bottom: 1px solid var(--border); padding: 11px 14px;
}
.table-card tbody td {
    padding: 11px 14px; vertical-align: middle;
    font-size: 13px; border-bottom: 1px solid #f5f7fa;
}
.table-card tbody tr { transition: background .15s; }
.table-card tbody tr:hover { background: #f5f9ff; }
.table-card tbody tr:last-child td { border-bottom: none; }

/* ── Cartes impétrants ───────────────────────────────────── */
.imp-card {
    background: #fff; border-radius: 12px; padding: 16px;
    border: 1px solid var(--border);
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    transition: .2s; height: 100%; display: flex; flex-direction: column;
}
.imp-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
    transform: translateY(-2px);
    border-color: rgba(30,159,242,.2);
}
.imp-photo {
    width: 64px; height: 64px; border-radius: 10px;
    object-fit: cover; border: 2px solid var(--border); flex-shrink: 0;
}
.imp-photo-placeholder {
    width: 64px; height: 64px; border-radius: 10px;
    background: #eef2f7; display: flex; align-items: center;
    justify-content: center; border: 2px solid var(--border); flex-shrink: 0;
}
.photo-sm {
    width: 42px; height: 42px; border-radius: 8px;
    object-fit: cover; border: 1.5px solid var(--border);
}
.photo-sm-placeholder {
    width: 42px; height: 42px; border-radius: 8px;
    background: #eef2f7; display: flex; align-items: center;
    justify-content: center; border: 1.5px solid var(--border);
}
.stat-pill {
    background: #f7f9fc; border-radius: 8px; padding: 7px 10px;
    font-size: 11px; text-align: center; border: 1px solid var(--border);
}

/* ── Badges ──────────────────────────────────────────────── */
.badge-direct  { background:#e8f4fd; color:#1E9FF2; border:1px solid #b8dff9; font-size:10px; }
.badge-demande { background:#e6f9f1; color:#28D094; border:1px solid #b4eeda; font-size:10px; }

/* ── Boutons action ──────────────────────────────────────── */
.action-btn {
    width: 30px; height: 30px; border-radius: 7px; padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; transition: .15s;
}
.action-btn:hover { transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="container-fluid">

    {{-- ── Hero header ────────────────────────────────────────── --}}
    <div class="page-hero d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
        <div>
            <h4 class="mb-1 font-weight-bold text-white">
                <i class="la la-users mr-2"></i>{{ $status }}
            </h4>
            <p class="mb-0 text-white" style="opacity:.7; font-size:13px;">
                Impétrants ayant fait une demande de Visa ou CRT, et enregistrements directs
            </p>
        </div>
        <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
            <span class="badge badge-light" style="font-size:13px; padding:8px 14px; border-radius:8px;">
                <i class="la la-users mr-1"></i>
                {{ $demandes->total() }} impétrant(s)
            </span>
            @can('impetrants.create')
            <a href="{{ route('impetrants.create') }}"
               class="btn btn-success btn-sm"
               style="border-radius:8px; font-weight:600;">
                <i class="la la-user-plus mr-1"></i> Enregistrer un impétrant
            </a>
            @endcan
        </div>
    </div>

    {{-- ── Filtres ─────────────────────────────────────────────── --}}
    <div class="filter-card">
        <form method="GET">
            <div class="row align-items-end">

                <div class="col-md-3 mb-2">
                    <label class="d-block mb-1" style="font-size:10px;font-weight:800;text-transform:uppercase;color:var(--muted);letter-spacing:.06em;">
                        Recherche
                    </label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Nom ou prénom…" value="{{ request('search') }}">
                </div>

                <div class="col-md-2 mb-2">
                    <label class="d-block mb-1" style="font-size:10px;font-weight:800;text-transform:uppercase;color:var(--muted);letter-spacing:.06em;">
                        Nationalité
                    </label>
                    <select name="nationalite" class="form-control form-control-sm">
                        <option value="">Toutes</option>
                        @foreach($pays as $p)
                            <option value="{{ $p->id }}" {{ request('nationalite') == $p->id ? 'selected' : '' }}>
                                {{ $p->lib_pays }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <label class="d-block mb-1" style="font-size:10px;font-weight:800;text-transform:uppercase;color:var(--muted);letter-spacing:.06em;">
                        Sexe
                    </label>
                    <select name="sexe" class="form-control form-control-sm">
                        <option value="">Tous</option>
                        <option value="Masculin" {{ request('sexe') === 'Masculin' ? 'selected' : '' }}>Homme</option>
                        <option value="Féminin"  {{ request('sexe') === 'Féminin'  ? 'selected' : '' }}>Femme</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <label class="d-block mb-1" style="font-size:10px;font-weight:800;text-transform:uppercase;color:var(--muted);letter-spacing:.06em;">
                        Nb. demandes
                    </label>
                    <select name="nb_demandes" class="form-control form-control-sm">
                        <option value="">Tous</option>
                        <option value="0"  {{ request('nb_demandes') === '0'  ? 'selected' : '' }}>Sans demande</option>
                        <option value="1"  {{ request('nb_demandes') === '1'  ? 'selected' : '' }}>1 demande</option>
                        <option value="2"  {{ request('nb_demandes') === '2'  ? 'selected' : '' }}>2 demandes</option>
                        <option value="3+" {{ request('nb_demandes') === '3+' ? 'selected' : '' }}>3+ demandes</option>
                    </select>
                </div>

                <div class="col-md-1 mb-2">
                    <label class="d-block mb-1" style="font-size:10px;font-weight:800;text-transform:uppercase;color:var(--muted);letter-spacing:.06em;">
                        Source
                    </label>
                    <select name="source" class="form-control form-control-sm">
                        <option value="">Tous</option>
                        <option value="direct"  {{ request('source') === 'direct'  ? 'selected' : '' }}>Direct</option>
                        <option value="demande" {{ request('source') === 'demande' ? 'selected' : '' }}>Via demande</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <div class="d-flex align-items-end justify-content-between" style="gap:6px;">
                        <div class="d-flex" style="gap:5px;">
                            <button type="submit" class="btn btn-primary btn-sm" style="border-radius:7px;">
                                <i class="la la-search"></i> Filtrer
                            </button>
                            <a href="{{ request()->url() }}"
                               class="btn btn-outline-secondary btn-sm" style="border-radius:7px;"
                               title="Réinitialiser">
                                <i class="la la-times"></i>
                            </a>
                        </div>
                        <div class="d-flex" style="gap:4px;">
                            <button type="button"
                                    class="toggle-view-btn {{ session('impetrants_layout','table') === 'table' ? 'active' : '' }}"
                                    onclick="switchView('table', event)" title="Tableau">
                                <i class="la la-list"></i>
                            </button>
                            <button type="button"
                                    class="toggle-view-btn {{ session('impetrants_layout','table') === 'cards' ? 'active' : '' }}"
                                    onclick="switchView('cards', event)" title="Cartes">
                                <i class="la la-th-large"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         VUE TABLEAU
    ══════════════════════════════════════════════════════════ --}}
    <div id="view-table" style="{{ session('impetrants_layout','table') === 'table' ? '' : 'display:none;' }}">
        <div class="table-card">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:40px;">N°</th>
                        <th style="width:52px;">Photo</th>
                        <th>Impétrant</th>
                        <th>Sexe</th>
                        <th>Nationalité</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Demandes</th>
                        <th>Dernière demande</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $count = ($demandes->currentPage() - 1) * $demandes->perPage() + 1; @endphp
                    @forelse($demandes as $imp)
                    @php
                        $photoSrc = $imp->demandes->last()?->photo ?? $imp->photo ?? null;
                    @endphp
                    <tr>
                        <td class="text-muted" style="font-size:12px;">{{ $count++ }}</td>

                        <td>
                            @if($photoSrc)
                                <img src="{{ asset('app/'.$photoSrc) }}" class="photo-sm">
                            @else
                                <div class="photo-sm-placeholder">
                                    <i class="la la-user" style="font-size:18px;color:var(--muted);"></i>
                                </div>
                            @endif
                        </td>

                        <td>
                            <div class="font-weight-bold" style="color:var(--dark);">
                                {{ strtoupper($imp->nom) }} {{ $imp->prenom }}
                            </div>
                            <small class="text-muted">
                                {{ $imp->date_naissance
                                    ? \Carbon\Carbon::parse($imp->date_naissance)->format('d/m/Y')
                                    : '—' }}
                            </small>
                        </td>

                        <td style="font-size:12px;">{{ $imp->sexe }}</td>

                        <td>
                            <span class="badge badge-light" style="font-size:11px;">
                                {{ $imp->pays?->lib_pays ?? '—' }}
                            </span>
                        </td>

                        <td class="text-center">
                            @if(($imp->source ?? 'demande') === 'direct')
                                <span class="badge badge-direct">Direct</span>
                            @else
                                <span class="badge badge-demande">Via demande</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <span class="badge badge-primary"
                                  style="font-size:12px; padding:4px 10px; border-radius:6px;">
                                {{ $imp->demandes_count }}
                            </span>
                        </td>

                        <td style="font-size:12px; color:var(--muted);">
                            {{ $imp->demandes->last()?->date_demande
                                ? \Carbon\Carbon::parse($imp->demandes->last()->date_demande)->format('d/m/Y')
                                : '—' }}
                        </td>

                        <td>
                            <div class="d-flex justify-content-center" style="gap:4px;">
                                @can('demandes.create')
                                <a href="{{ route('demandes.create', ['impetrant_id' => $imp->id]) }}"
                                   class="action-btn btn btn-success btn-sm" title="Créer une demande">
                                    <i class="la la-file-alt"></i>
                                </a>
                                @endcan
                                @if($imp->demandes_count > 0)
                                @can('demandes.print')
                                <a href="{{ route('impetrants.demandes', $imp->id) }}"
                                   class="action-btn btn btn-info btn-sm" title="Situation complète">
                                    <i class="la la-folder"></i>
                                </a>
                                @endcan
                                @endif
                                <a href="{{ route('impetrants.casier', $imp->id) }}"
                                   class="action-btn btn btn-dark btn-sm" title="Casier">
                                    <i class="la la-book"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="la la-inbox" style="font-size:2.5rem;"></i>
                            <p class="mt-2 mb-0">Aucun impétrant trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         VUE CARTES
    ══════════════════════════════════════════════════════════ --}}
    <div id="view-cards" style="{{ session('impetrants_layout','table') === 'cards' ? '' : 'display:none;' }}">
        <div class="row">
            @forelse($demandes as $imp)
            @php
                $photoSrc = $imp->demandes->last()?->photo ?? $imp->photo ?? null;
            @endphp
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <div class="imp-card">

                    {{-- Entête carte --}}
                    <div class="d-flex align-items-center mb-3" style="gap:12px;">
                        @if($photoSrc)
                            <img src="{{ asset('app/'.$photoSrc) }}" class="imp-photo">
                        @else
                            <div class="imp-photo-placeholder">
                                <i class="la la-user" style="font-size:26px;color:var(--muted);"></i>
                            </div>
                        @endif
                        <div style="min-width:0; flex:1;">
                            <div class="font-weight-bold text-truncate"
                                 style="font-size:14px; color:var(--dark);">
                                {{ strtoupper($imp->nom) }}
                            </div>
                            <div class="text-muted text-truncate" style="font-size:12px;">
                                {{ $imp->prenom }}
                            </div>
                            <div style="font-size:11px; color:var(--muted); margin-top:2px;">
                                {{ $imp->date_naissance
                                    ? \Carbon\Carbon::parse($imp->date_naissance)->format('d/m/Y')
                                    : '—' }}
                            </div>
                            <div class="mt-1">
                                @if(($imp->source ?? 'demande') === 'direct')
                                    <span class="badge badge-direct">Direct</span>
                                @else
                                    <span class="badge badge-demande">Via demande</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="d-flex mb-3" style="gap:7px;">
                        <div class="stat-pill flex-fill">
                            <div class="font-weight-bold text-primary" style="font-size:17px;">
                                {{ $imp->demandes_count }}
                            </div>
                            <div style="font-size:9px; color:var(--muted); text-transform:uppercase; letter-spacing:.05em;">
                                Demandes
                            </div>
                        </div>
                        <div class="stat-pill flex-fill" style="min-width:0;">
                            <div class="font-weight-bold text-truncate" style="font-size:11px; color:var(--dark);">
                                {{ $imp->pays?->lib_pays ?? '—' }}
                            </div>
                            <div style="font-size:9px; color:var(--muted); text-transform:uppercase; letter-spacing:.05em;">
                                Nationalité
                            </div>
                        </div>
                    </div>

                    {{-- Dernière demande --}}
                    <div style="font-size:11px; color:var(--muted); margin-bottom:12px; flex:1;">
                        <i class="la la-clock-o mr-1"></i>
                        Dernière demande :
                        <strong class="text-dark">
                            {{ $imp->demandes->last()?->date_demande
                                ? \Carbon\Carbon::parse($imp->demandes->last()->date_demande)->format('d/m/Y')
                                : '—' }}
                        </strong>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex" style="gap:6px;">
                        @can('demandes.create')
                        <a href="{{ route('demandes.create', ['impetrant_id' => $imp->id]) }}"
                           class="btn btn-success btn-sm flex-fill"
                           style="border-radius:8px; font-size:11px; font-weight:600;">
                            <i class="la la-file-alt"></i> Demande
                        </a>
                        @endcan
                        @if($imp->demandes_count > 0)
                        @can('demandes.print')
                        <a href="{{ route('impetrants.demandes', $imp->id) }}"
                           class="btn btn-info btn-sm"
                           style="border-radius:8px;" title="Situation">
                            <i class="la la-folder"></i>
                        </a>
                        @endcan
                        @endif
                        <a href="{{ route('impetrants.casier', $imp->id) }}"
                           class="btn btn-dark btn-sm"
                           style="border-radius:8px;" title="Casier">
                            <i class="la la-book"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="la la-inbox" style="font-size:3rem;"></i>
                <p class="mt-2">Aucun impétrant trouvé</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── Pagination ──────────────────────────────────────────── --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $demandes->appends(request()->query())->links('admin.pagination.pagination') }}
    </div>

</div>
@endsection

@section('scripts')
<script>
function switchView(type, e) {
    document.querySelectorAll('.toggle-view-btn').forEach(b => b.classList.remove('active'));
    e.currentTarget.classList.add('active');
    document.getElementById('view-table').style.display = type === 'table' ? '' : 'none';
    document.getElementById('view-cards').style.display = type === 'cards' ? '' : 'none';
    fetch('{{ route("impetrants.set.layout") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ layout: type })
    });
}
</script>
@endsection