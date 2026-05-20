@extends('admin.layouts.app')
@section('title') Casier Judiciaire — Liste des impétrants à antécédants @endsection

@section('content')
<style>
    :root {
        --bg: #f4f7fa;
        --primary: #4834d4;
    }
    .cj-wrapper { padding: 1.5rem; background: var(--bg); border-radius: 15px; }
    .cj-header { background: linear-gradient(135deg, #2d3748, #4a5568); color: white; border-radius: 14px; padding: 25px 30px; margin-bottom: 25px; }
    .filter-card { background: white; border-radius: 14px; padding: 20px; border: 1px solid #edf2f9; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
    .record-card { background: white; border-radius: 14px; padding: 18px; border: 1px solid #edf2f9; margin-bottom: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: 0.2s; }
    .record-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.09); transform: translateY(-2px); }
    .risk-badge { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .risk-eleve  { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .risk-moyen  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .risk-faible { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .antecedant-tag { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 600; margin-right: 4px; margin-bottom: 2px; }
    .tag-contentieux       { background: #fef2f2; color: #dc2626; }
    .tag-watchlist         { background: #1e1b4b; color: white; }
    .tag-documents_expires { background: #fff7ed; color: #c2410c; }
    .tag-fiches_expirees   { background: #f5f3ff; color: #7c3aed; }
    .risk-bar-wrap { background: #eee; border-radius: 50px; height: 6px; width: 80px; display: inline-block; vertical-align: middle; margin-left: 6px; }
    .risk-bar { height: 100%; border-radius: 50px; }
    .avatar-sm { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.6;transform:scale(1.3);} }
</style>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <div class="cj-wrapper">

                {{-- HEADER --}}
                <div class="cj-header d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                    <div>
                        <h4 class="mb-1 font-weight-bold">
                            <i class="feather icon-book-open mr-2"></i> Casier Judiciaire
                        </h4>
                        <p class="mb-0 small" style="opacity:.8;">
                            Liste des impétrants ayant au moins un antécédant enregistré
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="badge badge-light" style="font-size:14px; padding: 8px 16px;">
                            {{ $results->count() }} impétrant(s)
                        </span>
                    </div>
                </div>

                {{-- FILTRES --}}
                <div class="filter-card">
                    <form method="GET" action="{{ route('casier.global') }}">
                        <div class="row" style="gap: 0;">

                            <div class="col-md-3 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Nom / Prénom</label>
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Rechercher..." value="{{ $search }}">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Nationalité</label>
                                <select name="nationalite" class="form-control form-control-sm">
                                    <option value="">Toutes</option>
                                    @foreach($pays as $p)
                                        <option value="{{ $p->id }}" {{ $nationalite == $p->id ? 'selected' : '' }}>
                                            {{ $p->lib_pays }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Niveau de risque</label>
                                <select name="niveau_risque" class="form-control form-control-sm">
                                    <option value="">Tous</option>
                                    <option value="eleve"  {{ $niveauRisque === 'eleve'  ? 'selected' : '' }}>🔴 Élevé</option>
                                    <option value="moyen"  {{ $niveauRisque === 'moyen'  ? 'selected' : '' }}>🟡 Moyen</option>
                                    <option value="faible" {{ $niveauRisque === 'faible' ? 'selected' : '' }}>🟢 Faible</option>
                                </select>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Type d'antécédant</label>
                                <select name="type_antecedant" class="form-control form-control-sm">
                                    <option value="">Tous</option>
                                    <option value="contentieux"       {{ $typeAntecedant === 'contentieux'       ? 'selected' : '' }}>Contentieux</option>
                                    <option value="watchlist"         {{ $typeAntecedant === 'watchlist'         ? 'selected' : '' }}>Watchlist</option>
                                    <option value="documents_expires" {{ $typeAntecedant === 'documents_expires' ? 'selected' : '' }}>Documents expirés</option>
                                    <option value="fiches_expirees"   {{ $typeAntecedant === 'fiches_expirees'   ? 'selected' : '' }}>Fiches expirées</option>
                                </select>
                            </div>

                            <div class="col-md-2 mb-2 d-flex align-items-end" style="gap:8px;">
                                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">
                                    <i class="feather icon-search"></i> Filtrer
                                </button>
                                <a href="{{ route('casier.global') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
                                    <i class="feather icon-x"></i>
                                </a>
                            </div>

                        </div>
                    </form>
                </div>

                {{-- LISTE --}}
                @forelse($results->items() as $row)
                    @php $imp = $row['impetrant']; @endphp
                    <div class="record-card">
                        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">

                            {{-- Avatar + Identité --}}
                            <div class="d-flex align-items-center" style="gap:14px; min-width:220px;">
                                <img src="{{ $imp->demandes->first()?->photo ? asset('app/'.$imp->demandes->first()->photo) : asset('img/avatar-default.png') }}"
                                     class="avatar-sm">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <strong class="text-uppercase">{{ $imp->nom }}</strong>
                                        <span class="ml-1">{{ $imp->prenom }}</span>
                                        @if($row['watchlistCount'] > 0)
                                            <span style="width:8px;height:8px;background:#dc2626;border-radius:50%;display:inline-block;margin-left:6px;animation:pulse 1.5s infinite;" title="Watchlist"></span>
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($imp->date_naissance)->format('d/m/Y') }}
                                        — {{ $imp->pays?->lib_pays ?? '—' }}
                                    </small>
                                </div>
                            </div>

                            {{-- Antécédants tags --}}
                            <div style="min-width: 200px;">
                                @foreach($row['antecedants'] as $tag)
                                    <span class="antecedant-tag tag-{{ $tag }}">
                                        @switch($tag)
                                            @case('contentieux')       ⚖️ Contentieux ({{ $row['totalContentieux'] }}x) @break
                                            @case('watchlist')         🚨 Watchlist @break
                                            @case('documents_expires') 📄 Docs expirés @break
                                            @case('fiches_expirees')   🗂 Fiches expirées @break
                                        @endswitch
                                    </span>
                                @endforeach
                            </div>

                            {{-- Score de risque --}}
                            <div class="text-center" style="min-width:120px;">
                                <span class="risk-badge risk-{{ $row['niveau'] }}">
                                    @switch($row['niveau'])
                                        @case('eleve')  🔴 Élevé  @break
                                        @case('moyen')  🟡 Moyen  @break
                                        @case('faible') 🟢 Faible @break
                                    @endswitch
                                </span>
                                <div class="mt-1">
                                    <small class="text-muted" style="font-size:10px;">Score : {{ $row['score'] }}/100</small>
                                    <div class="risk-bar-wrap">
                                        <div class="risk-bar bg-{{ $row['niveau'] === 'eleve' ? 'danger' : ($row['niveau'] === 'moyen' ? 'warning' : 'success') }}"
                                             style="width:{{ $row['score'] }}%"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Stats rapides --}}
                            <div class="d-flex text-center" style="gap:16px;">
                                <div>
                                    <div class="font-weight-bold text-danger">{{ $row['totalContentieux'] }}</div>
                                    <small class="text-muted" style="font-size:9px;">Contentieux</small>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-warning">{{ $row['documentsExpires'] }}</div>
                                    <small class="text-muted" style="font-size:9px;">Docs exp.</small>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-purple">{{ $row['fichesExpirees'] }}</div>
                                    <small class="text-muted" style="font-size:9px;">Fiches exp.</small>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-muted">{{ $row['impetrant']->demandes->count() }}</div>
                                    <small class="text-muted" style="font-size:9px;">Demandes</small>
                                </div>
                            </div>

                            {{-- Dernière activité --}}
                            <div class="text-muted text-right" style="min-width:100px;">
                                <small style="font-size:10px;">Dernière activité</small><br>
                                <small class="font-weight-bold">
                                    {{ $row['derniere_activite'] ? \Carbon\Carbon::parse($row['derniere_activite'])->format('d/m/Y') : '—' }}
                                </small>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex" style="gap:8px;">
                                <a href="{{ route('impetrants.casier', $imp->id) }}"
                                   class="btn btn-dark btn-sm" style="border-radius:8px;">
                                    <i class="feather icon-book-open"></i> Casier
                                </a>
                                <a href="{{ route('impetrants.demandes', $imp->id) }}"
                                   class="btn btn-outline-primary btn-sm" style="border-radius:8px;">
                                    <i class="feather icon-folder"></i> Dossiers
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="feather icon-check-circle text-success" style="font-size:3rem;"></i>
                        <h5 class="mt-3 text-muted">Aucun impétrant avec antécédants trouvé</h5>
                        <p class="text-muted small">Modifiez vos critères de recherche</p>
                    </div>
                @endforelse

{{-- En bas de la liste, avant la fermeture de .cj-wrapper --}}
<div class="d-flex justify-content-center mt-3">
    {{ $results->appends(request()->query())->links() }}
</div>
            </div>
        </div>
    </div>
</div>
@endsection