@extends('admin.layouts.app')

@section('title', 'Tableau de Bord Dynamique')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row mb-2">
            <div class="content-header-left col-md-6 col-12">
                <h2 class="content-header-title mb-0">Vue d'ensemble de l'activité</h2>
                <p class="text-muted">Statistiques en temps réel des flux et documents</p>
            </div>
        </div>

        <div class="content-body">
            
            <div class="d-flex align-items-center mb-1">
                <div class="bg-primary p-1 rounded mr-1"><i class="la la-users text-white"></i></div>
                <h4 class="mb-0 text-bold-600">Demandes et Impétrants</h4>
            </div>
            <hr>
            
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card bg-gradient-directional-primary">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-white text-left">
                                        <a href="{{ route('impetrants.index') }}" style="text-decoration:none;">
                                            <h3 class="text-white">{{ collect(DB::select("select count(*) as total from impetrants"))->first()->total }}</h3>
                                            <span style="font-size:11px; opacity:0.8;">Voir la liste →</span>
                                        </a>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-database text-white font-large-2 float-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $statsDemandes = [
                        ['label' => "Aujourd'hui", 'val' => $today->nombre, 'critere' => 'jour', 'color' => 'primary'],
                        ['label' => "Semaine", 'val' => $week->nombre, 'critere' => 'semaine', 'color' => 'info'],
                        ['label' => "Mois", 'val' => $month->nombre, 'critere' => 'mois', 'color' => 'warning'],
                        ['label' => "Année", 'val' => $year->nombre, 'critere' => 'annee', 'color' => 'success'],
                    ];
                @endphp

                @foreach($statsDemandes as $stat)
                <div class="col-xl-2 col-md-6 col-12">
                    <a href="{{ route('demandes.demandestats') }}?critere={{ $stat['critere'] }}">
                        <div class="card pull-up border-top-{{ $stat['color'] }} border-top-3">
                            <div class="card-body">
                                <p class="text-muted mb-0">{{ $stat['label'] }}</p>
                                <h3 class="text-bold-600 mb-0">{{ $stat['val'] }}</h3>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="d-flex align-items-center mt-3 mb-1">
                <div class="bg-success p-1 rounded mr-1"><i class="la la-file-text text-white"></i></div>
                <h4 class="mb-0 text-bold-600">Attributions Documents (Visa & CRT)</h4>
            </div>
            <hr>

            <div class="row">
                @php
                    $statsAtt = [
                        ['label' => "Aujourd'hui", 'val' => $todayAtt->nombre, 'crit' => 'jour', 'icon' => 'la-clock-o'],
                        ['label' => "Semaine", 'val' => $weekAtt->nombre, 'crit' => 'semaine', 'icon' => 'la-calendar-check-o'],
                        ['label' => "Mois", 'val' => $monthAtt->nombre, 'crit' => 'mois', 'icon' => 'la-calendar'],
                        ['label' => "Année", 'val' => $yearAtt->nombre, 'crit' => 'annee', 'icon' => 'la-trophy'],
                    ];
                @endphp

                @foreach($statsAtt as $att)
                <div class="col-lg-3 col-12">
                    <div class="card pull-up shadow-sm">
                        <a href="{{ route('demandes.stats.attributions') }}?critere={{ $att['crit'] }}">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="la {{ $att['icon'] }} text-success font-large-1 mr-1"></i>
                                    </div>
                                    <div class="media-body text-right">
                                        <h5 class="text-muted mb-0">{{ $att['label'] }}</h5>
                                        <h3 class="text-bold-700 mb-0">{{ $att['val'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex align-items-center mt-3 mb-1">
                <div class="bg-dark p-1 rounded mr-1"><i class="la la-exchange text-white"></i></div>
                <h4 class="mb-0 text-bold-600">Flux Migratoires</h4>
            </div>
            <hr>

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card border-left-success border-left-3">
                        <div class="card-header"><h4 class="card-title text-success">Entrées</h4></div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Période</th>
                                            <th class="text-right">Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Aujourd'hui</td><td class="text-right text-bold-600">{{ $todayFlux->total_entree ?? 0 }}</td><td><a href="{{ route('flux.stats.entre') }}?critere=jour" class="btn btn-sm btn-outline-success">Voir</a></td></tr>
                                        <tr><td>Mois courant</td><td class="text-right text-bold-600">{{ $monthFlux->total_entree ?? 0 }}</td><td><a href="{{ route('flux.stats.entre') }}?critere=mois" class="btn btn-sm btn-outline-success">Voir</a></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card border-left-danger border-left-3">
                        <div class="card-header"><h4 class="card-title text-danger">Sorties</h4></div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Période</th>
                                            <th class="text-right">Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Aujourd'hui</td><td class="text-right text-bold-600">{{ $todayFlux->total_sortie ?? 0 }}</td><td><a href="{{ route('flux.stats.sortie') }}?critere=jour" class="btn btn-sm btn-outline-danger">Voir</a></td></tr>
                                        <tr><td>Mois courant</td><td class="text-right text-bold-600">{{ $monthFlux->total_sortie ?? 0 }}</td><td><a href="{{ route('flux.stats.sortie') }}?critere=mois" class="btn btn-sm btn-outline-danger">Voir</a></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CALENDRIER DES DEMANDES -->
            <div class="d-flex align-items-center mt-3 mb-1">
                <div class="bg-info p-1 rounded mr-1"><i class="la la-calendar text-white"></i></div>
                <h4 class="mb-0 text-bold-600">Calendrier des Demandes</h4>
            </div>
            <hr>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="la la-calendar-check-o"></i> 
                                Vue mensuelle des demandes créées
                            </h4>
                            <div class="heading-elements">
                                <button class="btn btn-sm btn-outline-primary" id="btn-today">Aujourd'hui</button>
                                <button class="btn btn-sm btn-outline-info" id="btn-refresh">
                                    <i class="la la-refresh"></i> Actualiser
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal détails du jour -->
            <div class="modal fade" id="modalDemandesJour" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="la la-calendar-day"></i> 
                                Demandes du <span id="modal-date"></span>
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="loader" class="text-center" style="display:none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Chargement...</span>
                                </div>
                            </div>
                            <div id="modal-content"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
    }
    
    .fc-event {
        cursor: pointer;
        border-radius: 3px;
        padding: 2px 5px;
    }
    
    .fc-daygrid-day-number {
        font-size: 14px;
        font-weight: 600;
    }
    
    .fc-day-today {
        background-color: #e3f2fd !important;
    }
    
    .demande-card {
        border-left: 3px solid #2c5aa0;
        margin-bottom: 10px;
        padding: 10px;
        background: #f8faff;
        border-radius: 4px;
    }
    
    .demande-card:hover {
        background: #eef5ff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>

<script>
console.log('=== SCRIPT CHARGÉ ===');
console.log('FullCalendar:', typeof FullCalendar);

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM READY ===');
    
    const calendarEl = document.getElementById('calendar');
    console.log('Calendar element:', calendarEl);
    
    if (!calendarEl) {
        console.error('Element #calendar introuvable!');
        return;
    }
    
    try {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'fr',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            buttonText: {
                today: "Aujourd'hui",
                month: 'Mois',
                week: 'Semaine'
            },
            height: 'auto',
            events: function(info, successCallback, failureCallback) {
                console.log('Chargement événements...');
                fetch('/api/demandes/calendar?start=' + info.startStr + '&end=' + info.endStr)
                    .then(response => {
                        console.log('Response:', response);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data:', data);
                        successCallback(data);
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        failureCallback(error);
                    });
            },
            eventClick: function(info) {
                showDemandesModal(info.event.start);
            },
            dateClick: function(info) {
                showDemandesModal(new Date(info.dateStr));
            }
        });
        
        console.log('Rendu du calendrier...');
        calendar.render();
        console.log('=== CALENDRIER RENDU ===');
        
        // Boutons
        document.getElementById('btn-today').addEventListener('click', function() {
            calendar.today();
        });
        
        document.getElementById('btn-refresh').addEventListener('click', function() {
            calendar.refetchEvents();
        });
        
        // Fonction pour afficher le modal
        function showDemandesModal(date) {
            const dateFormatted = date.toLocaleDateString('fr-FR', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            $('#modal-date').text(dateFormatted);
            $('#loader').show();
            $('#modal-content').html('');
            $('#modalDemandesJour').modal('show');
            
            const dateStr = date.toISOString().split('T')[0];
            
            fetch('/api/demandes/jour/' + dateStr)
                .then(response => response.json())
                .then(data => {
                    console.log('Demandes:', data);
                    $('#loader').hide();
                    renderDemandesDetails(data);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    $('#loader').hide();
                    $('#modal-content').html('<div class="alert alert-danger">Erreur de chargement</div>');
                });
        }
        
        // Fonction pour afficher les détails
        function renderDemandesDetails(data) {
            if (!data.demandes || data.demandes.length === 0) {
                $('#modal-content').html(`
                    <div class="alert alert-info">
                        <i class="la la-info-circle"></i> Aucune demande enregistrée ce jour.
                    </div>
                `);
                return;
            }
            
            let html = `
                <div class="mb-3">
                    <h6 class="text-muted">
                        <i class="la la-file-text"></i> 
                        ${data.total} demande(s) · ${data.agents_count} agent(s)
                    </h6>
                </div>
            `;
            
            // Grouper par agent
            const parAgent = {};
            data.demandes.forEach(dem => {
                const agentNom = dem.agent_nom || 'Non défini';
                if (!parAgent[agentNom]) {
                    parAgent[agentNom] = [];
                }
                parAgent[agentNom].push(dem);
            });
            
            // Afficher par agent
            Object.keys(parAgent).forEach(agentNom => {
                const demandes = parAgent[agentNom];
                html += `
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="la la-user"></i> ${agentNom}
                            <span class="badge badge-primary ml-1">${demandes.length}</span>
                        </h6>
                `;
                
                demandes.forEach(dem => {
                    const statusColor = 
                        dem.statut_demande.includes('Approuvée') ? 'success' :
                        dem.statut_demande.includes('contentieux') ? 'danger' :
                        dem.statut_demande.includes('attente') ? 'warning' : 'secondary';
                        
                    html += `
                        <div class="demande-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="text-primary">#${dem.uuid}</strong>
                                    <br>
                                    <small class="text-muted">
                                        ${dem.impetrant_nom} ${dem.impetrant_prenom}
                                    </small>
                                    <br>
                                    <span class="badge badge-${statusColor} mt-1">${dem.statut_demande}</span>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted">${dem.type_demande}</small>
                                    <br>
                                    <small class="text-muted">${dem.heure}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `</div>`;
            });
            
            $('#modal-content').html(html);
        }
        
    } catch(error) {
        console.error('Erreur initialisation calendrier:', error);
    }
});
</script>
@endsection