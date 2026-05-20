@extends('admin.layouts.app')
@section('title') Statistiques avancées — Hébergement @endsection
@section('styles')
<style>
.kpi-card {
    border-radius: 12px;
    padding: 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.kpi-card .kpi-num  { font-size: 2.4rem; font-weight: 800; line-height: 1; }
.kpi-card .kpi-label{ font-size: 12px; opacity: .85; margin-top: 6px; }
.kpi-card .kpi-icon {
    position: absolute; right: 16px; top: 50%;
    transform: translateY(-50%);
    font-size: 3.5rem; opacity: .18;
}
.table-rank td, .table-rank th { vertical-align: middle; padding: 8px 12px; }
.rank-bar { height: 8px; border-radius: 4px; background: #1E9FF2; }
.chart-container { position: relative; height: 260px; }
.section-title {
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; color: #6c757d;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 8px; margin-bottom: 16px;
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
                    <i class="la la-bar-chart"></i> Statistiques avancées — Hébergement
                </h3>
            </div>
            <div class="content-header-right col-md-4 col-12 text-right">
                <a href="{{ route('certificats-hebergement.relations') }}" class="btn btn-info btn-sm">
                    <i class="la la-exchange"></i> Relations
                </a>
                <a href="{{ route('certificats-hebergement.index') }}" class="btn btn-secondary btn-sm ml-1">
                    <i class="la la-list"></i> Certificats
                </a>
            </div>
        </div>

        <div class="content-body">

            {{-- Filtres dates --}}
            <div class="card mb-3">
                <div class="card-body py-2">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-4">
                            <label class="mb-0" style="font-size:12px;">Du</label>
                            <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
                        </div>
                        <div class="col-md-4">
                            <label class="mb-0" style="font-size:12px;">Au</label>
                            <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-sm btn-block mt-1">
                                <i class="la la-filter"></i> Appliquer la période
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ══ KPIs PRINCIPAUX ══ --}}
            <div class="row mb-3">
                <div class="col-md-2 col-6 mb-2">
                    <div class="kpi-card" style="background:linear-gradient(135deg,#1E9FF2,#0d6ebc);">
                        <div class="kpi-num">{{ $stats['total'] }}</div>
                        <div class="kpi-label">Certificats émis</div>
                        <i class="la la-file-text kpi-icon"></i>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="kpi-card" style="background:linear-gradient(135deg,#28D094,#1a9e6e);">
                        <div class="kpi-num">{{ $stats['valides'] }}</div>
                        <div class="kpi-label">Validés</div>
                        <i class="la la-check-circle kpi-icon"></i>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="kpi-card" style="background:linear-gradient(135deg,#FF9149,#d96e2a);">
                        <div class="kpi-num">{{ $stats['en_attente'] }}</div>
                        <div class="kpi-label">En attente</div>
                        <i class="la la-clock-o kpi-icon"></i>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="kpi-card" style="background:linear-gradient(135deg,#FF4961,#c72a3e);">
                        <div class="kpi-num">{{ $stats['rejetes'] }}</div>
                        <div class="kpi-label">Rejetés</div>
                        <i class="la la-times-circle kpi-icon"></i>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="kpi-card" style="background:linear-gradient(135deg,#6c757d,#495057);">
                        <div class="kpi-num">{{ $stats['expires'] }}</div>
                        <div class="kpi-label">Expirés</div>
                        <i class="la la-calendar-times-o kpi-icon"></i>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="kpi-card" style="background:linear-gradient(135deg,#7367f0,#5a52d5);">
                        <div class="kpi-num">{{ round($stats['duree_moyenne']) }}</div>
                        <div class="kpi-label">Durée moy. (jours)</div>
                        <i class="la la-hourglass-half kpi-icon"></i>
                    </div>
                </div>
            </div>

            {{-- ══ HÉBERGEURS ══ --}}
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <div class="kpi-card" style="background:linear-gradient(135deg,#28D094,#1a9e6e);">
                        <div class="kpi-num">{{ $stats['hebergeurs']['congolais'] }}</div>
                        <div class="kpi-label"><i class="la la-flag"></i> Hébergeurs congolais</div>
                        <i class="la la-users kpi-icon"></i>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="kpi-card" style="background:linear-gradient(135deg,#1E9FF2,#0d6ebc);">
                        <div class="kpi-num">{{ $stats['hebergeurs']['etrangers'] }}</div>
                        <div class="kpi-label"><i class="la la-globe"></i> Hébergeurs étrangers</div>
                        <i class="la la-users kpi-icon"></i>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="kpi-card" style="background:linear-gradient(135deg,#FF9149,#d96e2a);">
                        <div class="kpi-num">{{ $stats['hebergeurs']['societes'] }}</div>
                        <div class="kpi-label"><i class="la la-building"></i> Sociétés hébergeuses</div>
                        <i class="la la-building kpi-icon"></i>
                    </div>
                </div>
            </div>

            <div class="row">

                {{-- ══ GRAPHE ÉVOLUTION MENSUELLE ══ --}}
                <div class="col-md-8 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">Évolution mensuelle des certificats</div>
                            <div class="chart-container">
                                <canvas id="chartMois"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ RÉPARTITION PAR TYPE ══ --}}
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">Répartition par type d'hébergeur</div>
                            <div class="chart-container">
                                <canvas id="chartType"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ TOP NATIONALITÉS HÉBERGÉES ══ --}}
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="la la-arrow-down text-danger"></i>
                                Top nationalités les plus hébergées
                            </div>
                            @php $maxHbergee = $stats['nat_hebergees']->max('total') ?: 1; @endphp
                            <table class="table table-rank table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nationalité</th>
                                        <th>Nb</th>
                                        <th style="width:35%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['nat_hebergees'] as $i => $row)
                                    <tr>
                                        <td>
                                            @if($i === 0) 🥇
                                            @elseif($i === 1) 🥈
                                            @elseif($i === 2) 🥉
                                            @else {{ $i + 1 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->code)
                                                <img src="{{ asset('res/flags/'.strtolower($row->code).'.png') }}"
                                                     style="width:20px;height:auto;margin-right:6px;"
                                                     onerror="this.style.display='none'">
                                            @endif
                                            {{ $row->nationalite }}
                                        </td>
                                        <td><strong>{{ $row->total }}</strong></td>
                                        <td>
                                            <div class="rank-bar" style="width:{{ round(($row->total/$maxHbergee)*100) }}%; background:#FF4961;"></div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-muted text-center">Aucune donnée</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ══ TOP NATIONALITÉS HÉBERGEUSES ══ --}}
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="la la-arrow-up text-success"></i>
                                Top nationalités qui hébergent le plus
                            </div>
                            @php $maxHbergeur = $stats['nat_hebergeurs']->max('total') ?: 1; @endphp
                            <table class="table table-rank table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nationalité</th>
                                        <th>Nb</th>
                                        <th style="width:35%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['nat_hebergeurs'] as $i => $row)
                                    <tr>
                                        <td>
                                            @if($i === 0) 🥇
                                            @elseif($i === 1) 🥈
                                            @elseif($i === 2) 🥉
                                            @else {{ $i + 1 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->code ?? null)
                                                <img src="{{ asset('res/flags/'.strtolower($row->code).'.png') }}"
                                                     style="width:20px;height:auto;margin-right:6px;"
                                                     onerror="this.style.display='none'">
                                            @endif
                                            {{ $row->nationalite }}
                                        </td>
                                        <td><strong>{{ $row->total }}</strong></td>
                                        <td>
                                            <div class="rank-bar" style="width:{{ round(($row->total/$maxHbergeur)*100) }}%;"></div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-muted text-center">Aucune donnée</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ══ TOP HÉBERGEURS (nb invitations) ══ --}}
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="la la-trophy text-warning"></i>
                                Hébergeurs les plus actifs
                            </div>
                            <table class="table table-rank table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Hébergeur</th>
                                        <th>Type</th>
                                        <th>Invitations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['top_hebergeurs'] as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <strong>{{ $row->nom_hebergeur }}</strong>
                                            @if($row->code_hebergeur)
                                                <small class="badge badge-light text-muted ml-1">{{ $row->code_hebergeur }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-sm
                                                @if($row->hebergeur_type==='Congolais') badge-success
                                                @elseif($row->hebergeur_type==='Etranger') badge-info
                                                @else badge-warning @endif">
                                                {{ $row->hebergeur_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $row->total }}</strong>
                                            invitation(s)
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-muted text-center">Aucune donnée</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ══ RÉPARTITION PAR RELATION ══ --}}
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">Par type de relation</div>
                            <div class="chart-container">
                                <canvas id="chartRelation"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ DURÉES DE SÉJOUR ══ --}}
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">Durées de séjour</div>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td class="text-muted">Durée minimale</td>
                                    <td class="text-right"><strong>{{ $stats['duree_min'] ?? '—' }} j</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Durée maximale</td>
                                    <td class="text-right"><strong>{{ $stats['duree_max'] ?? '—' }} j</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Durée moyenne</td>
                                    <td class="text-right"><strong>{{ round($stats['duree_moyenne']) ?? '—' }} j</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Durée médiane</td>
                                    <td class="text-right"><strong>{{ $stats['duree_mediane'] ?? '—' }} j</strong></td>
                                </tr>
                                <tr class="table-light">
                                    <td class="text-muted">≤ 7 jours</td>
                                    <td class="text-right"><strong>{{ $stats['durees']['court'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">8–30 jours</td>
                                    <td class="text-right"><strong>{{ $stats['durees']['moyen'] }}</strong></td>
                                </tr>
                                <tr class="table-light">
                                    <td class="text-muted">31–90 jours</td>
                                    <td class="text-right"><strong>{{ $stats['durees']['long'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">> 90 jours</td>
                                    <td class="text-right"><strong>{{ $stats['durees']['tres_long'] }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ══ PAR AGENT ══ --}}
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">Certificats par agent</div>
                            <table class="table table-rank table-sm">
                                <thead>
                                    <tr><th>Agent</th><th>Total</th><th>Validés</th><th>En attente</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['par_agent'] as $row)
                                    <tr>
                                        <td>{{ $row->agent }}</td>
                                        <td><strong>{{ $row->total }}</strong></td>
                                        <td class="text-success">{{ $row->valides }}</td>
                                        <td class="text-warning">{{ $row->en_attente }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-muted text-center">Aucune donnée</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ══ PAR JOUR DE LA SEMAINE ══ --}}
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">Activité par jour de la semaine</div>
                            <div class="chart-container" style="height:200px;">
                                <canvas id="chartJour"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ TAUX DE VALIDATION ══ --}}
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="section-title">Taux de validation</div>
                            @php
                                $tauxValidation = $stats['total'] > 0
                                    ? round(($stats['valides'] / $stats['total']) * 100, 1) : 0;
                                $tauxRejet = $stats['total'] > 0
                                    ? round(($stats['rejetes'] / $stats['total']) * 100, 1) : 0;
                            @endphp
                            <div class="text-center mb-3">
                                <div style="font-size:2.5rem; font-weight:800; color:#28D094;">
                                    {{ $tauxValidation }}%
                                </div>
                                <div class="text-muted small">taux de validation global</div>
                            </div>
                            <div class="progress mb-2" style="height:12px; border-radius:6px;">
                                <div class="progress-bar bg-success" style="width:{{ $tauxValidation }}%"
                                     title="Validés {{ $tauxValidation }}%"></div>
                                <div class="progress-bar bg-warning"
                                     style="width:{{ $stats['total'] > 0 ? round(($stats['en_attente']/$stats['total'])*100,1) : 0 }}%"
                                     title="En attente"></div>
                                <div class="progress-bar bg-danger" style="width:{{ $tauxRejet }}%"
                                     title="Rejetés {{ $tauxRejet }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between" style="font-size:11px;">
                                <span class="text-success">✓ Validés {{ $tauxValidation }}%</span>
                                <span class="text-warning">⏳ Attente</span>
                                <span class="text-danger">✗ Rejetés {{ $tauxRejet }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
var COLORS = ['#1E9FF2','#28D094','#FF9149','#FF4961','#7367f0','#6c757d','#ffc107','#17a2b8'];

// Évolution mensuelle
new Chart(document.getElementById('chartMois'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($stats['par_mois']->pluck('mois')) !!},
        datasets: [
            {
                label: 'Total',
                data: {!! json_encode($stats['par_mois']->pluck('total')) !!},
                backgroundColor: '#1E9FF2',
                borderRadius: 4,
            },
            {
                label: 'Validés',
                data: {!! json_encode($stats['par_mois']->pluck('valides')) !!},
                backgroundColor: '#28D094',
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// Type hébergeur
new Chart(document.getElementById('chartType'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($stats['par_type']->pluck('hebergeur_type')) !!},
        datasets: [{
            data: {!! json_encode($stats['par_type']->pluck('total')) !!},
            backgroundColor: ['#28D094','#1E9FF2','#FF9149'],
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Relation
new Chart(document.getElementById('chartRelation'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($stats['par_relation']->pluck('type_relation')) !!},
        datasets: [{
            data: {!! json_encode($stats['par_relation']->pluck('total')) !!},
            backgroundColor: COLORS,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Jour de la semaine
new Chart(document.getElementById('chartJour'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($stats['par_jour']->pluck('jour_label')) !!},
        datasets: [{
            label: 'Certificats',
            data: {!! json_encode($stats['par_jour']->pluck('total')) !!},
            backgroundColor: '#7367f0',
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
</script>
@endpush
