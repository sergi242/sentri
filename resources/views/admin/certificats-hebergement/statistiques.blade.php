@extends('admin.layouts.app')
@section('title') Statistiques — Certificats d'hébergement @endsection
@section('styles')
<style>
.stat-big { font-size:2.5rem; font-weight:800; line-height:1; }
.chart-container { position:relative; height:280px; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h3 class="content-header-title">
                    <i class="la la-bar-chart"></i> Statistiques — Certificats d'hébergement
                </h3>
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
                                <i class="la la-filter"></i> Appliquer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KPIs --}}
            <div class="row mb-3">
                <div class="col-md-3 col-6 mb-2">
                    <div class="card text-center py-3">
                        <div class="stat-big text-primary">{{ $stats['total'] }}</div>
                        <div class="text-muted" style="font-size:12px;">Certificats émis</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card text-center py-3">
                        <div class="stat-big text-success">{{ $stats['valides'] }}</div>
                        <div class="text-muted" style="font-size:12px;">Validés</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card text-center py-3">
                        <div class="stat-big text-warning">{{ $stats['en_attente'] }}</div>
                        <div class="text-muted" style="font-size:12px;">En attente</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card text-center py-3">
                        <div class="stat-big text-danger">{{ $stats['rejetes'] }}</div>
                        <div class="text-muted" style="font-size:12px;">Rejetés</div>
                    </div>
                </div>
            </div>

            {{-- Hébergeurs actifs --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header py-2">
                            <strong><i class="la la-users"></i> Hébergeurs enregistrés dans le système</strong>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div style="font-size:2rem; font-weight:800; color:#28D094;">
                                        {{ $stats['hebergeurs_actifs']['congolais'] }}
                                    </div>
                                    <div class="text-muted">Congolais</div>
                                </div>
                                <div class="col-md-4">
                                    <div style="font-size:2rem; font-weight:800; color:#1E9FF2;">
                                        {{ $stats['hebergeurs_actifs']['etrangers'] }}
                                    </div>
                                    <div class="text-muted">Étrangers (impétrants)</div>
                                </div>
                                <div class="col-md-4">
                                    <div style="font-size:2rem; font-weight:800; color:#FF9149;">
                                        {{ $stats['hebergeurs_actifs']['societes'] }}
                                    </div>
                                    <div class="text-muted">Sociétés</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Graphe par type d'hébergeur --}}
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header py-2">
                            <strong>Répartition par type d'hébergeur</strong>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartType"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Graphe par relation --}}
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header py-2">
                            <strong>Répartition par type de relation</strong>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartRelation"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Graphe par mois --}}
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header py-2">
                            <strong>Évolution mensuelle</strong>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartMois"></canvas>
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
const COLORS = ['#28D094','#1E9FF2','#FF9149','#FF4961','#6c757d'];

// Graphe type hébergeur
new Chart(document.getElementById('chartType'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($stats['par_type_heberg']->pluck('hebergeur_type')) !!},
        datasets: [{
            data: {!! json_encode($stats['par_type_heberg']->pluck('total')) !!},
            backgroundColor: COLORS,
        }]
    },
    options: { responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } } }
});

// Graphe relation
new Chart(document.getElementById('chartRelation'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($stats['par_relation']->pluck('type_relation')) !!},
        datasets: [{
            data: {!! json_encode($stats['par_relation']->pluck('total')) !!},
            backgroundColor: COLORS,
        }]
    },
    options: { responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } } }
});

// Graphe mensuel
new Chart(document.getElementById('chartMois'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($stats['par_mois']->pluck('mois')) !!},
        datasets: [{
            label: 'Certificats',
            data: {!! json_encode($stats['par_mois']->pluck('total')) !!},
            backgroundColor: '#1E9FF2',
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