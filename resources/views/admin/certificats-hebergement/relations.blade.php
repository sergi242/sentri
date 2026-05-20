@extends('admin.layouts.app')
@section('title') Relations Hébergeur / Hébergé @endsection
@section('styles')
<style>
.relation-card {
    border-radius: 10px;
    transition: all .2s;
    border: 1px solid #e9ecef;
}
.relation-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,.10);
    transform: translateY(-2px);
}
.avatar-sm {
    width: 44px; height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
}
.avatar-placeholder {
    width: 44px; height: 44px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 16px; color: #fff;
    flex-shrink: 0;
}
.arrow-badge {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 11px;
    color: #6c757d;
    white-space: nowrap;
}
.badge-type-congolais { background:#28D094; color:#fff; }
.badge-type-etranger  { background:#1E9FF2; color:#fff; }
.badge-type-societe   { background:#FF9149; color:#fff; }
.stat-pill {
    display: inline-block;
    background: #f0f4ff;
    color: #1E9FF2;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 11px;
    font-weight: 600;
}
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <h3 class="content-header-title">
                    <i class="la la-exchange"></i> Relations Hébergeur / Hébergé
                </h3>
            </div>
            <div class="content-header-right col-md-4 col-12 text-right">
                <a href="{{ route('certificats-hebergement.statistiques') }}" class="btn btn-info btn-sm">
                    <i class="la la-bar-chart"></i> Statistiques
                </a>
                <a href="{{ route('certificats-hebergement.index') }}" class="btn btn-secondary btn-sm ml-1">
                    <i class="la la-list"></i> Tous les certificats
                </a>
            </div>
        </div>

        <div class="content-body">

            {{-- Filtres --}}
            <div class="card mb-3">
                <div class="card-body py-2">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-3">
                            <label class="mb-0" style="font-size:12px;">Recherche (hébergeur ou hébergé)</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                   placeholder="Nom, prénom, code..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="mb-0" style="font-size:12px;">Type hébergeur</label>
                            <select name="type" class="form-control form-control-sm">
                                <option value="">Tous</option>
                                <option value="Congolais" {{ request('type')=='Congolais'?'selected':'' }}>Congolais</option>
                                <option value="Etranger"  {{ request('type')=='Etranger'?'selected':'' }}>Étranger</option>
                                <option value="Societe"   {{ request('type')=='Societe'?'selected':'' }}>Société</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="mb-0" style="font-size:12px;">Statut certificat</label>
                            <select name="statut" class="form-control form-control-sm">
                                <option value="">Tous</option>
                                @foreach(['En attente','Validé','Rejeté','Expiré'] as $s)
                                    <option value="{{ $s }}" {{ request('statut')==$s?'selected':'' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="mb-0" style="font-size:12px;">Du</label>
                            <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="mb-0" style="font-size:12px;">Au</label>
                            <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary btn-sm btn-block mt-1">
                                <i class="la la-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Compteur --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">
                    <strong>{{ $certificats->total() }}</strong> relation(s) trouvée(s)
                </span>
                <a href="{{ request()->fullUrlWithQuery(['export'=>'csv']) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="la la-download"></i> Exporter CSV
                </a>
            </div>

            {{-- Liste relations --}}
            @forelse($certificats as $cert)
            @php
                $colors = ['Congolais'=>'#28D094','Etranger'=>'#1E9FF2','Societe'=>'#FF9149'];
                $color  = $colors[$cert->hebergeur_type] ?? '#6c757d';
                $statutColor = [
                    'Validé'     => 'success',
                    'En attente' => 'warning',
                    'Rejeté'     => 'danger',
                    'Expiré'     => 'secondary',
                ][$cert->statut] ?? 'secondary';
            @endphp
            <div class="card relation-card mb-2">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">

                        {{-- HÉBERGEUR --}}
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                @php
                                    $photo = null;
                                    if ($cert->hebergeur_type === 'Congolais' && $cert->hebergeurCongolais?->photo) {
                                        $photo = asset('storage/' . $cert->hebergeurCongolais->photo);
                                    } elseif ($cert->hebergeur_type === 'Etranger' && $cert->hebergeurEtranger?->photo) {
                                        $photo = asset('app/' . $cert->hebergeurEtranger->photo);
                                    }
                                    $initiale = strtoupper(substr($cert->nom_hebergeur, 0, 1));
                                @endphp
                                @if($photo)
                                    <img src="{{ $photo }}" class="avatar-sm mr-3" alt="">
                                @else
                                    <div class="avatar-placeholder mr-3" style="background:{{ $color }};">
                                        {{ $initiale }}
                                    </div>
                                @endif
                                <div>
                                    <strong class="d-block" style="font-size:14px;">{{ $cert->nom_hebergeur }}</strong>
                                    <span class="badge badge-type-{{ strtolower($cert->hebergeur_type) }} badge-sm">
                                        {{ $cert->hebergeur_type }}
                                    </span>
                                    @if($cert->code_hebergeur !== '—')
                                        <small class="badge badge-light text-muted ml-1">{{ $cert->code_hebergeur }}</small>
                                    @endif
                                    <small class="d-block text-muted mt-1">
                                        @if($cert->hebergeur_type === 'Congolais' && $cert->hebergeurCongolais)
                                            <i class="la la-phone"></i> {{ $cert->hebergeurCongolais->telephone }}
                                        @elseif($cert->hebergeur_type === 'Etranger' && $cert->hebergeurEtranger)
                                            {{ $cert->hebergeurEtranger->pays?->lib_pays ?? '' }}
                                        @elseif($cert->hebergeur_type === 'Societe' && $cert->hebergeurSociete)
                                            {{ $cert->hebergeurSociete->adresse_physique ?? '' }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- FLÈCHE --}}
                        <div class="col-md-2 text-center">
                            <div class="arrow-badge">
                                <i class="la la-home"></i> héberge
                                <i class="la la-arrow-right"></i>
                            </div>
                            <div class="mt-1">
                                <small class="text-muted">
                                    {{ $cert->type_relation }}
                                    @if($cert->precision_relation)
                                        <span class="text-muted">({{ $cert->precision_relation }})</span>
                                    @endif
                                </small>
                            </div>
                        </div>

                        {{-- HÉBERGÉ --}}
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                @if($cert->heberge?->photo)
                                    <img src="{{ asset('app/'.$cert->heberge->photo) }}"
                                         class="avatar-sm mr-3" alt="">
                                @else
                                    <div class="avatar-placeholder mr-3" style="background:#6c757d;">
                                        {{ strtoupper(substr($cert->heberge?->nom ?? '?', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    @if($cert->heberge)
                                        <strong class="d-block" style="font-size:14px;">
                                            {{ strtoupper($cert->heberge->nom) }} {{ $cert->heberge->prenom }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $cert->heberge->pays?->lib_pays ?? '—' }}
                                        </small>
                                        <small class="text-muted">
                                            {{ $cert->heberge->date_naissance
                                                ? \Carbon\Carbon::parse($cert->heberge->date_naissance)->format('d/m/Y')
                                                : '—' }}
                                        </small>
                                    @else
                                        <span class="text-muted">Non enregistré</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- INFOS CERTIFICAT --}}
                        <div class="col-md-3 text-right">
                            <span class="badge badge-{{ $statutColor }} mb-1">{{ $cert->statut }}</span>
                            <small class="d-block text-muted">
                                <i class="la la-file-text"></i>
                                <a href="{{ route('certificats-hebergement.show', $cert->id) }}"
                                   class="text-primary font-weight-bold">
                                    {{ $cert->numero_certificat }}
                                </a>
                            </small>
                            <small class="d-block text-muted mt-1">
                                <i class="la la-calendar"></i>
                                {{ $cert->date_arrivee_prevue?->format('d/m/Y') }} →
                                {{ $cert->date_depart_prevue?->format('d/m/Y') }}
                            </small>
                            <small class="stat-pill mt-1">
                                {{ $cert->duree_sejour_jours ?? '?' }} jours
                            </small>
                        </div>

                    </div>
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="la la-inbox" style="font-size:3rem;"></i>
                    <p class="mt-2">Aucune relation trouvée</p>
                </div>
            </div>
            @endforelse

            {{-- Pagination --}}
            @if($certificats->hasPages())
            <div class="mt-3">
                {{ $certificats->links('admin.pagination.pagination') }}
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
