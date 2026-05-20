@extends('admin.layouts.app')

@section('title', 'Attribution en Masse - Soit-Transmis')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <h3 class="content-header-title mb-0">
                    <i class="la la-check-square-o"></i> Attribution en Masse
                </h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('users.home') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('soit-transmis.index') }}">Soit-Transmis</a></li>
                            <li class="breadcrumb-item active">Attribution en Masse</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            
            <!-- RECHERCHE DU SOIT-TRANSMIS -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="la la-search"></i> Rechercher un Soit-Transmis
                    </h4>
                </div>
                <div class="card-body">
                   <div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Numéro Soit-Transmis</label>
            <input type="text" class="form-control" id="search-numero" placeholder="Ex: 24D25000">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Commanditaire</label>
            <select class="form-control select2" id="search-commanditaire">
                <option value="">-- Sélectionner un commanditaire --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->getNomPrenom() }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Signataire</label>
            <select class="form-control select2" id="search-signataire">
                <option value="">-- Sélectionner un signataire --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->getNomPrenom() }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Description</label>
            <input type="text" class="form-control" id="search-destination" placeholder="Description">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Statut d'attribution</label>
            <select class="form-control" id="search-statut">
                <option value="">-- Tous --</option>
                <option value="non_attribue">Non attribué</option>
                <option value="partiel">Partiellement attribué</option>
                <option value="complet">Complètement attribué</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Date de création</label>
            <input type="date" class="form-control" id="search-date">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>&nbsp;</label>
            <button type="button" class="btn btn-primary btn-block" id="btn-rechercher">
                <i class="la la-search"></i> Rechercher
            </button>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>&nbsp;</label>
            <button type="button" class="btn btn-secondary btn-block" id="btn-reset">
                <i class="la la-refresh"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date de création</label>
                                <input type="date" class="form-control" id="search-date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-primary btn-block" id="btn-rechercher">
                                    <i class="la la-search"></i> Rechercher
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-secondary btn-block" id="btn-reset">
                                    <i class="la la-refresh"></i> Réinitialiser
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- RÉSULTATS DE LA RECHERCHE -->
                    <div id="resultats-recherche" style="display:none;">
                        <hr>
                        <h5 class="text-primary mb-3">
                            <i class="la la-list"></i> Résultats de la recherche
                            <span class="badge badge-primary" id="count-resultats"></span>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Numéro ST</th>
                                        <th>Description</th>
                                        <th>Commanditaire</th>
                                        <th>Signataire</th>
                                        <th>Demandes</th>
                                        <th>Statut Attribution</th>
                                        <th>Date création</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-resultats">
                                    <!-- Rempli dynamiquement -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LISTE DES DEMANDES -->
            <div class="card" id="card-demandes" style="display:none;">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title text-white">
                        <i class="la la-list"></i> 
                        Demandes du Soit-Transmis <strong id="st-numero"></strong>
                    </h4>
                    <div class="heading-elements">
                        <span class="badge badge-light" id="count-demandes"></span>
                    </div>
                </div>
                <div class="card-body">
                    
                    <!-- DATE DE SORTIE COMMUNE -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="la la-calendar"></i> Date de sortie commune
                                </label>
                                <input type="date" class="form-control" id="date-sortie-commune" 
                                    value="{{ date('Y-m-d') }}">
                                <small class="text-muted">Cette date sera appliquée à toutes les demandes</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="la la-hashtag"></i> Format numéro
                                </label>
                                <input type="text" class="form-control" value="00+(UUID-1)" readonly>
                                <small class="text-muted">Exemple : UUID=123 → 00122</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-success btn-block btn-lg" id="btn-attribuer-tout">
                                <i class="la la-check-circle"></i> Attribuer TOUT en masse
                            </button>
                        </div>
                    </div>

                    <!-- TABLEAU DES DEMANDES -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-demandes">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="check-all">
                                            <label class="custom-control-label" for="check-all"></label>
                                        </div>
                                    </th>
                                    <th width="8%">UUID</th>
                                    <th width="20%">Impétrant</th>
                                    <th width="15%">Type</th>
                                    <th width="12%">Numéro calculé</th>
                                    <th width="12%">Date sortie</th>
                                    <th width="13%">Statut</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-demandes">
                                <!-- Rempli dynamiquement -->
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
<script>
$(document).ready(function() {
    
    let demandesData = [];
    let soitTransmisId = null;
    let soitTransmisNumero = null;
    
    // Rechercher les Soit-Transmis
    $('#btn-rechercher').on('click', function() {
        rechercherSoitTransmis();
    });
    
    // Fonction de recherche
    function rechercherSoitTransmis() {
        const criteres = {
            numero: $('#search-numero').val(),
            commanditaire: $('#search-commanditaire').val(),
            destination: $('#search-destination').val(),
            date: $('#search-date').val(),
            statut: $('#search-statut').val()
        };
        
        // Vérifier qu'au moins un critère est rempli
        const hasValue = Object.values(criteres).some(val => val !== '');
        if (!hasValue) {
            toastr.warning('Veuillez renseigner au moins un critère de recherche');
            return;
        }
        
        $.ajax({
            url: '/soit-transmis/recherche-avancee',
            method: 'GET',
            data: criteres,
            beforeSend: function() {
                $('#btn-rechercher').prop('disabled', true)
                    .html('<i class="la la-spinner la-spin"></i> Recherche...');
            },
            success: function(response) {
                console.log('Résultats:', response);
                afficherResultats(response.soitTransmis);
                $('#btn-rechercher').prop('disabled', false)
                    .html('<i class="la la-search"></i> Rechercher');
            },
            error: function(xhr) {
                console.error('Erreur:', xhr);
                toastr.error('Erreur lors de la recherche');
                $('#btn-rechercher').prop('disabled', false)
                    .html('<i class="la la-search"></i> Rechercher');
            }
        });
    }
    
    // Afficher les résultats
    function afficherResultats(resultats) {
        console.log('Affichage de', resultats.length, 'résultats');
        
        if (resultats.length === 0) {
            toastr.info('Aucun résultat trouvé');
            $('#tbody-resultats').html(`
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        <i class="la la-info-circle"></i> Aucun résultat trouvé
                    </td>
                </tr>
            `);
            $('#resultats-recherche').slideDown();
            return;
        }
        
        $('#count-resultats').text(resultats.length);
        let html = '';
        
        resultats.forEach(st => {
            html += `
                <tr>
                    <td class="font-weight-bold text-primary">${st.numero_soit_transmis || 'N/A'}</td>
                    <td>${st.destination || '-'}</td>
                    <td>${st.commanditaire || '-'}</td>
                    <td>${st.signataire_nom || '-'}</td>
                    <td>
                        <span class="badge badge-info">${st.demandes_count} demandes</span>
                    </td>
                    <td>
                        <span class="badge badge-${st.statut_color || 'secondary'}">${st.statut_label || 'N/A'}</span>
                    </td>
                    <td>${st.date_creation}</td>
                    <td>
                        <button class="btn btn-sm btn-success btn-selectionner" 
                            data-st-id="${st.id}" 
                            data-st-numero="${st.numero_soit_transmis || 'N/A'}"
                            data-st-count="${st.demandes_count}">
                            <i class="la la-check"></i> Sélectionner
                        </button>
                    </td>
                </tr>
            `;
        });
        
        $('#tbody-resultats').html(html);
        $('#resultats-recherche').slideDown();
    }
    
    // Sélectionner un ST depuis les résultats
    $(document).on('click', '.btn-selectionner', function() {
        soitTransmisId = $(this).data('st-id');
        soitTransmisNumero = $(this).data('st-numero');
        const count = $(this).data('st-count');
        
        chargerDemandes(soitTransmisId, soitTransmisNumero, count);
    });
    
    // Réinitialiser la recherche
    $('#btn-reset').on('click', function() {
        $('#search-numero, #search-commanditaire, #search-destination, #search-date, #search-statut').val('');
        $('#resultats-recherche').slideUp();
        $('#card-demandes').slideUp();
    });
    
    // Charger les demandes d'un ST
    function chargerDemandes(stId, stNumero, count) {
        $('#st-numero').text(stNumero);
        $('#count-demandes').text(count + ' demandes');
        
        $.ajax({
            url: '/soit-transmis/' + stId + '/demandes-attribution',
            method: 'GET',
            beforeSend: function() {
                toastr.info('Chargement des demandes...');
            },
            success: function(response) {
                console.log('Demandes chargées:', response);
                demandesData = response.demandes;
                renderDemandesTable();
                $('#card-demandes').slideDown();
                
                // Scroll vers le tableau
                $('html, body').animate({
                    scrollTop: $('#card-demandes').offset().top - 100
                }, 500);
            },
            error: function(xhr) {
                console.error('Erreur chargement demandes:', xhr);
                toastr.error('Erreur lors du chargement des demandes');
            }
        });
    }
    
    // Rendre le tableau des demandes
    function renderDemandesTable() {
        const dateSortie = $('#date-sortie-commune').val();
        let html = '';
        
        demandesData.forEach((dem, index) => {
            const numeroCalcule = '00' + (parseInt(dem.uuid.toString().slice(0, -1)) - 1);
            const dejaAttribue = dem.attribue == 1;
            const statutClass = dejaAttribue ? 'success' : 'warning';
            const statutText = dejaAttribue ? 'Déjà attribué' : 'En attente';
            
            html += `
                <tr data-demande-id="${dem.id}">
                    <td class="text-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input check-demande" 
                                id="check-${dem.id}" value="${dem.id}" ${dejaAttribue ? 'disabled' : ''}>
                            <label class="custom-control-label" for="check-${dem.id}"></label>
                        </div>
                    </td>
                    <td class="font-weight-bold text-primary">${dem.uuid}</td>
                    <td>${dem.impetrant_nom} ${dem.impetrant_prenom}</td>
                    <td><small>${dem.type_demande}</small></td>
                    <td>
                        <code class="text-primary">${numeroCalcule}</code>
                    </td>
                    <td>
                        <input type="date" class="form-control form-control-sm date-sortie-individuelle" 
                            value="${dateSortie}" data-demande-id="${dem.id}" ${dejaAttribue ? 'disabled' : ''}>
                    </td>
                    <td>
                        <span class="badge badge-${statutClass}">${statutText}</span>
                    </td>
                    <td>
                        ${dejaAttribue ? 
                            `<small class="text-muted">Attribué le ${dem.date_attribution}</small>` :
                            `<button class="btn btn-sm btn-success btn-attribuer-un" data-demande-id="${dem.id}">
                                <i class="la la-check"></i> Attribuer
                            </button>`
                        }
                    </td>
                </tr>
            `;
        });
        
        $('#tbody-demandes').html(html);
    }
    
    // Cocher/décocher tout
    $('#check-all').on('change', function() {
        $('.check-demande:not(:disabled)').prop('checked', $(this).is(':checked'));
    });
    
    // Appliquer la date commune
    $('#date-sortie-commune').on('change', function() {
        const newDate = $(this).val();
        $('.date-sortie-individuelle:not(:disabled)').val(newDate);
    });
    
    // Attribuer TOUT en masse
    $('#btn-attribuer-tout').on('click', function() {
        const checked = $('.check-demande:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (checked.length === 0) {
            toastr.warning('Veuillez cocher au moins une demande');
            return;
        }
        
        if (!confirm(`Attribuer ${checked.length} demande(s) avec les numéros calculés automatiquement ?`)) {
            return;
        }
        
        const attributions = [];
        
        checked.forEach(demandeId => {
            const demande = demandesData.find(d => d.id == demandeId);
            const dateSortie = $(`input.date-sortie-individuelle[data-demande-id="${demandeId}"]`).val();
            const uuidSansDernier = demande.uuid.toString().slice(0, -1);
            const numeroDocument = '00' + (parseInt(uuidSansDernier) - 1);
            
            attributions.push({
                demande_id: demandeId,
                numero_document: numeroDocument,
                date_sortie: dateSortie
            });
        });
        
        console.log('Attributions à envoyer:', attributions);
        
        // Envoyer via AJAX
        $.ajax({
            url: '/soit-transmis/attribuer-masse',
            method: 'POST',
            data: {
                attributions: attributions,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $('#btn-attribuer-tout').prop('disabled', true)
                    .html('<i class="la la-spinner la-spin"></i> Attribution...');
            },
            success: function(response) {
                console.log('Réponse attribution:', response);
                toastr.success(response.message);
                chargerDemandes(soitTransmisId, soitTransmisNumero, demandesData.length);
                $('#btn-attribuer-tout').prop('disabled', false)
                    .html('<i class="la la-check-circle"></i> Attribuer TOUT en masse');
            },
            error: function(xhr) {
                console.error('Erreur attribution:', xhr);
                toastr.error('Erreur lors de l\'attribution');
                $('#btn-attribuer-tout').prop('disabled', false)
                    .html('<i class="la la-check-circle"></i> Attribuer TOUT en masse');
            }
        });
    });
    
    // Attribuer une seule demande
    $(document).on('click', '.btn-attribuer-un', function() {
        const demandeId = $(this).data('demande-id');
        const demande = demandesData.find(d => d.id == demandeId);
        const dateSortie = $(`input.date-sortie-individuelle[data-demande-id="${demandeId}"]`).val();
        const uuidSansDernier = demande.uuid.toString().slice(0, -1);
        const numeroDocument = '00' + (parseInt(uuidSansDernier) - 1);
        
        console.log('Attribution individuelle:', { demandeId, numeroDocument, dateSortie });
        
        $.ajax({
            url: '/soit-transmis/attribuer-masse',
            method: 'POST',
            data: {
                attributions: [{
                    demande_id: demandeId,
                    numero_document: numeroDocument,
                    date_sortie: dateSortie
                }],
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Réponse attribution individuelle:', response);
                toastr.success('Demande attribuée avec succès');
                chargerDemandes(soitTransmisId, soitTransmisNumero, demandesData.length);
            },
            error: function(xhr) {
                console.error('Erreur attribution individuelle:', xhr);
                toastr.error('Erreur lors de l\'attribution');
            }
        });
    });
});
// Initialiser Select2 sur les sélecteurs
$('.select2').select2({
    placeholder: "Sélectionner...",
    allowClear: true,
    width: '100%'
});
</script>
@endsection