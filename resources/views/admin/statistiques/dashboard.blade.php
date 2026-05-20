@extends('admin.layouts.app')

@section('title', 'Statistiques Avancées')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        
        <!-- En-tête avec filtres -->
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">
                    <i class="la la-bar-chart"></i> Statistiques Avancées
                </h3>
                <p class="text-muted">Analyse détaillée des demandes et flux migratoires</p>
            </div>
            <div class="content-header-right col-md-6 col-12">
                <div class="btn-group float-md-right">
                    <button type="button" class="btn btn-info" id="btn-export-pdf">
                        <i class="la la-file-pdf-o"></i> Export PDF
                    </button>
                    <button type="button" class="btn btn-secondary" id="btn-refresh">
                        <i class="la la-refresh"></i> Actualiser
                    </button>
                </div>
            </div>
        </div>

        <div class="content-body">
            
            <!-- Filtres globaux -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="la la-filter"></i> Filtres</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mois</label>
                                <select class="form-control" id="filter-mois">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Année</label>
                                <select class="form-control" id="filter-annee">
                                    @for($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-primary btn-block" id="btn-appliquer-filtres">
                                    <i class="la la-check"></i> Appliquer
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-outline-secondary btn-block" id="btn-reset-filtres">
                                    <i class="la la-undo"></i> Réinitialiser
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartes statistiques rapides -->
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Demandes</h6>
                                        <h3 class="mb-0" id="stat-demandes">{{ $stats['demandes_mois'] }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-file-text text-primary font-large-2"></i>
                                    </div>
                                </div>
                                <div class="progress mt-1 mb-0" style="height: 4px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Approuvées</h6>
                                        <h3 class="mb-0 text-success" id="stat-approuvees">{{ $stats['demandes_approuvees'] }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-check-circle text-success font-large-2"></i>
                                    </div>
                                </div>
                                <div class="progress mt-1 mb-0" style="height: 4px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Flux Entrées</h6>
                                        <h3 class="mb-0 text-info" id="stat-entrees">{{ $stats['flux_entrees_mois'] }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-arrow-down text-info font-large-2"></i>
                                    </div>
                                </div>
                                <div class="progress mt-1 mb-0" style="height: 4px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 60%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Flux Sorties</h6>
                                        <h3 class="mb-0 text-danger" id="stat-sorties">{{ $stats['flux_sorties_mois'] }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-arrow-up text-danger font-large-2"></i>
                                    </div>
                                </div>
                                <div class="progress mt-1 mb-0" style="height: 4px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 50%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparaison mois actuel vs précédent -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="la la-exchange"></i> Comparaison Périodique
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row" id="comparaison-container">
                        <div class="col-12 text-center">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques principaux -->
            <div class="row">
                
                <!-- Graphique : Demandes par jour -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="la la-line-chart"></i> Évolution des demandes par jour
                            </h4>
                            <div class="heading-elements">
                                <span class="badge badge-primary" id="periode-label"></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-demandes-jour" height="80"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Graphique : Demandes vs Flux -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="la la-area-chart"></i> Demandes vs Flux Migratoires
                            </h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-demandes-flux" height="80"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Graphique : Par type -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="la la-pie-chart"></i> Demandes par Type
                            </h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-par-type"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Graphique : Par statut -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="la la-pie-chart"></i> Demandes par Statut
                            </h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-par-statut"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Graphique : Par agent -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="la la-bar-chart"></i> Top 10 Agents Actifs
                            </h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-par-agent" height="60"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Graphique : Flux par frontière -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="la la-map-marker"></i> Flux par Frontière
                            </h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-flux-frontiere"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Graphique : Flux par nationalité -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="la la-flag"></i> Top 15 Nationalités
                            </h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-flux-nationalite"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tableau des agents actifs -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="la la-users"></i> Agents Actifs ce Mois
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Agent</th>
                                    <th>Demandes créées</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agents as $index => $agent)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $agent->getNomPrenom() }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $agent->demandes_count }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('users.show', $agent->id) }}" class="btn btn-sm btn-info">
                                            <i class="la la-eye"></i> Voir profil
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
let moisActuel = {{ now()->month }};
let anneeActuelle = {{ now()->year }};

// Configuration des couleurs
const colors = {
    primary: '#1E9FF2',
    success: '#28D094',
    danger: '#FF4961',
    warning: '#FF9149',
    info: '#1E9FF2',
    secondary: '#6c757d'
};

// Tous les graphiques
let charts = {};

$(document).ready(function() {
    
    // Charger toutes les données
    chargerToutesLesDonnees();
    
    // Bouton appliquer filtres
    $('#btn-appliquer-filtres').on('click', function() {
        moisActuel = $('#filter-mois').val();
        anneeActuelle = $('#filter-annee').val();
        chargerToutesLesDonnees();
    });
    
    // Bouton reset filtres
    $('#btn-reset-filtres').on('click', function() {
        moisActuel = {{ now()->month }};
        anneeActuelle = {{ now()->year }};
        $('#filter-mois').val(moisActuel);
        $('#filter-annee').val(anneeActuelle);
        chargerToutesLesDonnees();
    });
    
    // Bouton refresh
    $('#btn-refresh').on('click', function() {
        chargerToutesLesDonnees();
    });
    
    // Bouton export PDF
    $('#btn-export-pdf').on('click', function() {
        window.location.href = `/statistiques/export-pdf?mois=${moisActuel}&annee=${anneeActuelle}`;
    });
});

function chargerToutesLesDonnees() {
    updatePeriodeLabel();
    chargerComparaison();
    chargerDemandesParJour();
    chargerDemandesFlux();
    chargerDemandesParType();
    chargerDemandesParStatut();
    chargerDemandesParAgent();
    chargerFluxParFrontiere();
    chargerFluxParNationalite();
}

function updatePeriodeLabel() {
    const mois = new Date(anneeActuelle, moisActuel - 1).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
    $('#periode-label').text(mois.charAt(0).toUpperCase() + mois.slice(1));
}

// ========================================
// COMPARAISON PÉRIODIQUE
// ========================================
function chargerComparaison() {
    $.get('/statistiques/api/comparaison', function(data) {
        let html = '';
        
        const items = [
            { key: 'demandes', label: 'Demandes', icon: 'file-text', color: 'primary' },
            { key: 'approuvees', label: 'Approuvées', icon: 'check-circle', color: 'success' },
            { key: 'flux_entrees', label: 'Entrées', icon: 'arrow-down', color: 'info' },
            { key: 'flux_sorties', label: 'Sorties', icon: 'arrow-up', color: 'danger' }
        ];
        
        items.forEach(item => {
            const current = data.current[item.key];
            const previous = data.previous[item.key];
            const diff = current - previous;
            const percent = previous > 0 ? ((diff / previous) * 100).toFixed(1) : 0;
            const trending = diff > 0 ? 'up' : (diff < 0 ? 'down' : 'right');
            const trendColor = diff > 0 ? 'success' : (diff < 0 ? 'danger' : 'secondary');
            
            html += `
                <div class="col-md-3">
                    <div class="text-center">
                        <i class="la la-${item.icon} text-${item.color} font-large-2"></i>
                        <h6 class="mt-1">${item.label}</h6>
                        <h3 class="text-bold-600">${current}</h3>
                        <p class="text-muted">
                            <i class="la la-arrow-${trending} text-${trendColor}"></i>
                            <span class="text-${trendColor}">${diff > 0 ? '+' : ''}${diff}</span>
                            (${percent > 0 ? '+' : ''}${percent}%)
                        </p>
                        <small class="text-muted">vs ${data.previous_label}</small>
                    </div>
                </div>
            `;
        });
        
        $('#comparaison-container').html(html);
    });
}

// ========================================
// DEMANDES PAR JOUR
// ========================================
function chargerDemandesParJour() {
    $.get('/statistiques/api/demandes-par-jour', { mois: moisActuel, annee: anneeActuelle }, function(data) {
        
        const labels = data.map(d => new Date(d.date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' }));
        const totaux = data.map(d => d.total);
        const approuvees = data.map(d => d.approuvees);
        const attente = data.map(d => d.attente);
        const contentieux = data.map(d => d.contentieux);
        
        if (charts.demandesJour) charts.demandesJour.destroy();
        
        const ctx = document.getElementById('chart-demandes-jour').getContext('2d');
        charts.demandesJour = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total',
                        data: totaux,
                        borderColor: colors.primary,
                        backgroundColor: 'rgba(30, 159, 242, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Approuvées',
                        data: approuvees,
                        borderColor: colors.success,
                        backgroundColor: 'rgba(40, 208, 148, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'En attente',
                        data: attente,
                        borderColor: colors.warning,
                        backgroundColor: 'rgba(255, 145, 73, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Contentieux',
                        data: contentieux,
                        borderColor: colors.danger,
                        backgroundColor: 'rgba(255, 73, 97, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
}

// ========================================
// DEMANDES VS FLUX MIGRATOIRES
// ========================================
function chargerDemandesFlux() {
    Promise.all([
        $.get('/statistiques/api/demandes-par-jour', { mois: moisActuel, annee: anneeActuelle }),
        $.get('/statistiques/api/flux-par-jour', { mois: moisActuel, annee: anneeActuelle })
    ]).then(([demandes, flux]) => {
        
        const labels = demandes.map(d => new Date(d.date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' }));
        const demandesData = demandes.map(d => d.total);
        const entreesData = flux.map(f => f.entrees);
        const sortiesData = flux.map(f => f.sorties);
        
        if (charts.demandesFlux) charts.demandesFlux.destroy();
        
        const ctx = document.getElementById('chart-demandes-flux').getContext('2d');
        charts.demandesFlux = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Demandes',
                        data: demandesData,
                        backgroundColor: 'rgba(30, 159, 242, 0.8)',
                        borderColor: colors.primary,
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Flux Entrées',
                        data: entreesData,
                        backgroundColor: 'rgba(40, 208, 148, 0.8)',
                        borderColor: colors.success,
                        borderWidth: 1,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'Flux Sorties',
                        data: sortiesData,
                        backgroundColor: 'rgba(255, 73, 97, 0.8)',
                        borderColor: colors.danger,
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Demandes'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Flux Migratoires'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    });
}

// ========================================
// DEMANDES PAR TYPE
// ========================================
function chargerDemandesParType() {
    $.get('/statistiques/api/demandes-par-type', { mois: moisActuel, annee: anneeActuelle }, function(data) {
        
        const labels = data.map(d => d.type_demande);
        const values = data.map(d => d.total);
        
        const backgroundColors = [
            'rgba(30, 159, 242, 0.8)',
            'rgba(40, 208, 148, 0.8)',
            'rgba(255, 145, 73, 0.8)',
            'rgba(255, 73, 97, 0.8)',
            'rgba(108, 117, 125, 0.8)',
            'rgba(155, 89, 182, 0.8)',
            'rgba(52, 152, 219, 0.8)'
        ];
        
        if (charts.parType) charts.parType.destroy();
        
        const ctx = document.getElementById('chart-par-type').getContext('2d');
        charts.parType = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: backgroundColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
}

// ========================================
// DEMANDES PAR STATUT
// ========================================
function chargerDemandesParStatut() {
    $.get('/statistiques/api/demandes-par-statut', { mois: moisActuel, annee: anneeActuelle }, function(data) {
        
        const labels = data.map(d => d.statut_demande);
        const values = data.map(d => d.total);
        
        const backgroundColors = labels.map(label => {
            if (label.includes('Approuvée')) return 'rgba(40, 208, 148, 0.8)';
            if (label.includes('contentieux')) return 'rgba(255, 73, 97, 0.8)';
            if (label.includes('attente')) return 'rgba(255, 145, 73, 0.8)';
            return 'rgba(108, 117, 125, 0.8)';
        });
        
        if (charts.parStatut) charts.parStatut.destroy();
        
        const ctx = document.getElementById('chart-par-statut').getContext('2d');
        charts.parStatut = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: backgroundColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
}

// ========================================
// DEMANDES PAR AGENT
// ========================================
function chargerDemandesParAgent() {
    $.get('/statistiques/api/demandes-par-agent', { mois: moisActuel, annee: anneeActuelle }, function(data) {
        
        const labels = data.map(d => d.agent);
        const values = data.map(d => d.total);
        
        if (charts.parAgent) charts.parAgent.destroy();
        
        const ctx = document.getElementById('chart-par-agent').getContext('2d');
        charts.parAgent = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Demandes créées',
                    data: values,
                    backgroundColor: 'rgba(30, 159, 242, 0.8)',
                    borderColor: colors.primary,
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
}

// ========================================
// FLUX PAR FRONTIÈRE
// ========================================
function chargerFluxParFrontiere() {
    $.get('/statistiques/api/flux-par-frontiere', { mois: moisActuel, annee: anneeActuelle }, function(data) {
        
        // Grouper par frontière
        const groupedData = {};
        data.forEach(item => {
            if (!groupedData[item.frontiere]) {
                groupedData[item.frontiere] = { entrees: 0, sorties: 0 };
            }
            if (item.type === 'Entrée') {
                groupedData[item.frontiere].entrees = item.total;
            } else {
                groupedData[item.frontiere].sorties = item.total;
            }
        });
        
        const labels = Object.keys(groupedData);
        const entrees = labels.map(f => groupedData[f].entrees);
        const sorties = labels.map(f => groupedData[f].sorties);
        
        if (charts.fluxFrontiere) charts.fluxFrontiere.destroy();
        
        const ctx = document.getElementById('chart-flux-frontiere').getContext('2d');
        charts.fluxFrontiere = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Entrées',
                        data: entrees,
                        backgroundColor: 'rgba(40, 208, 148, 0.8)',
                        borderColor: colors.success,
                        borderWidth: 1
                    },
                    {
                        label: 'Sorties',
                        data: sorties,
                        backgroundColor: 'rgba(255, 73, 97, 0.8)',
                        borderColor: colors.danger,
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
}

// ========================================
// FLUX PAR NATIONALITÉ
// ========================================
function chargerFluxParNationalite() {
    $.get('/statistiques/api/flux-par-nationalite', { mois: moisActuel, annee: anneeActuelle }, function(data) {
        
        const labels = data.map(d => d.nationalite);
        const values = data.map(d => d.total);
        
        if (charts.fluxNationalite) charts.fluxNationalite.destroy();
        
        const ctx = document.getElementById('chart-flux-nationalite').getContext('2d');
        charts.fluxNationalite = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nombre de flux',
                    data: values,
                    backgroundColor: 'rgba(155, 89, 182, 0.8)',
                    borderColor: '#9b59b6',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
}
</script>

@endsection
