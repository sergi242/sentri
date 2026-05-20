@extends('admin.layouts.app')
@section('title') Certificats d'hébergement @endsection
@section('styles')
<style>
.stat-card { border-radius: 10px; padding: 16px 20px; color: #fff; }
.stat-card .stat-num { font-size: 2rem; font-weight: 800; line-height: 1; }
.stat-card .stat-label { font-size: 12px; opacity: .85; margin-top: 4px; }
.badge-statut-en-attente { background:#FF9149; color:#fff; }
.badge-statut-valide     { background:#28D094; color:#fff; }
.badge-statut-rejete     { background:#FF4961; color:#fff; }
.badge-statut-expire     { background:#6c757d; color:#fff; }
.badge-type-congolais  { background:#28D094; color:#fff; }
.badge-type-etranger   { background:#1E9FF2; color:#fff; }
.badge-type-societe    { background:#FF9149; color:#fff; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h3 class="content-header-title">
                    <i class="la la-building"></i> Certificats d'hébergement
                </h3>
            </div>
            <div class="content-header-right col-md-3 col-12 text-right">
                <a href="{{ route('certificats-hebergement.create') }}" class="btn btn-primary">
                    <i class="la la-plus"></i> Nouveau certificat
                </a>
            </div>
        </div>

        <div class="content-body">

            {{-- Stats --}}
            <div class="row mb-3">
                <div class="col-md-3 col-6 mb-2">
                    <div class="stat-card" style="background:linear-gradient(135deg,#1E9FF2,#0d7bc4);">
                        <div class="stat-num">{{ $stats['total'] }}</div>
                        <div class="stat-label"><i class="la la-file-text"></i> Total certificats</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="stat-card" style="background:linear-gradient(135deg,#FF9149,#e07030);">
                        <div class="stat-num">{{ $stats['en_attente'] }}</div>
                        <div class="stat-label"><i class="la la-clock-o"></i> En attente</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="stat-card" style="background:linear-gradient(135deg,#28D094,#1a9e6e);">
                        <div class="stat-num">{{ $stats['valides'] }}</div>
                        <div class="stat-label"><i class="la la-check-circle"></i> Validés</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="stat-card" style="background:linear-gradient(135deg,#6c757d,#495057);">
                        <div class="stat-num">{{ $stats['expires'] }}</div>
                        <div class="stat-label"><i class="la la-calendar-times-o"></i> Expirés</div>
                    </div>
                </div>
            </div>

            {{-- Filtres --}}
            <div class="card mb-3">
                <div class="card-body py-2">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-5">
                            <label class="mb-0" style="font-size:12px;">Recherche</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                   placeholder="Numéro certificat, nom hébergeur/hébergé..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="mb-0" style="font-size:12px;">Statut</label>
                            <select name="statut" class="form-control form-control-sm">
                                <option value="">Tous</option>
                                @foreach(['En attente','Validé','Rejeté','Expiré'] as $s)
                                    <option value="{{ $s }}" {{ request('statut') == $s ? 'selected':'' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm btn-block">
                                <i class="la la-search"></i> Filtrer
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('certificats-hebergement.index') }}" class="btn btn-secondary btn-sm btn-block">
                                <i class="la la-refresh"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>N° Certificat</th>
                                    <th>Hébergeur</th>
                                    <th>Type</th>
                                    <th>Hébergé</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th>Statut</th>
                                    <th>Agent</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($certificats as $cert)
                                <tr>
                                    <td>
                                        <a href="{{ route('certificats-hebergement.show', $cert->id) }}"
                                           class="font-weight-bold text-primary">
                                            {{ $cert->numero_certificat }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>{{ $cert->nom_hebergeur }}</strong>
                                        @if($cert->code_hebergeur !== '—')
                                            <br><small class="text-muted">{{ $cert->code_hebergeur }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $typeClass = ['Congolais'=>'congolais','Etranger'=>'etranger','Societe'=>'societe'][$cert->hebergeur_type] ?? '';
                                        @endphp
                                        <span class="badge badge-type-{{ $typeClass }}">
                                            {{ $cert->hebergeur_type ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($cert->heberge)
                                            {{ strtoupper($cert->heberge->nom) }} {{ $cert->heberge->prenom }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $cert->date_arrivee_prevue ? $cert->date_arrivee_prevue->format('d/m/Y') : '—' }}</td>
                                    <td>{{ $cert->date_depart_prevue  ? $cert->date_depart_prevue->format('d/m/Y')  : '—' }}</td>
                                    <td>
                                        @php $sc = strtolower(str_replace(' ','',str_replace('é','e',$cert->statut))); @endphp
                                        <span class="badge badge-statut-{{ $sc }}">{{ $cert->statut }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $cert->createur?->prenom }} {{ $cert->createur?->nom }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('certificats-hebergement.show', $cert->id) }}"
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="la la-eye"></i>
                                        </a>
                                        <a href="{{ route('certificats-hebergement.imprimer', $cert->id) }}"
                                           class="btn btn-sm btn-dark" title="Imprimer" target="_blank">
                                            <i class="la la-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="la la-inbox" style="font-size:2rem;"></i><br>
                                        Aucun certificat trouvé
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($certificats->hasPages())
                <div class="card-footer">
                    {{ $certificats->links('admin.pagination.pagination') }}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection