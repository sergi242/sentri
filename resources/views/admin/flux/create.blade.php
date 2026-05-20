@extends('admin.layouts.app')

@section('title', 'Ajouter un Nouveau Flux de Données')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; }
        .card {
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden; /* Pour la transition du titre */
        }
        .card-header {
            background: linear-gradient(45deg, #666ee8, #1890ff);
            color: white;
            padding: 25px 30px;
            border-bottom: none;
            position: relative;
        }
        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 2;
        }
        .card-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://picsum.photos/1200/300') no-repeat center center / cover; /* Image de fond dynamique */
            opacity: 0.15;
            z-index: 1;
        }
        .form-section {
            border-bottom: 2px solid #e0e6ed;
            padding-bottom: 10px;
            margin-top: 30px;
            margin-bottom: 25px;
            color: #4a4a4a;
            font-weight: 600;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }
        .form-section i {
            margin-right: 10px;
            color: #666ee8;
        }
        .form-group label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }
        .form-control, .select2-container .select2-selection--single {
            border-radius: 10px;
            border: 1px solid #dcdcdc;
            padding: 10px 15px;
            transition: all 0.3s ease;
            box-shadow: none !important;
        }
        .form-control:focus, .select2-container--open .select2-selection--single {
            border-color: #666ee8;
            box-shadow: 0 0 0 3px rgba(102, 110, 232, 0.25) !important;
        }
        .btn {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #666ee8;
            border-color: #666ee8;
        }
        .btn-primary:hover {
            background-color: #555bc4;
            border-color: #555bc4;
            transform: translateY(-2px);
        }
        .btn-outline-warning {
            color: #ff9800;
            border-color: #ff9800;
        }
        .btn-outline-warning:hover {
            background-color: #ff9800;
            color: white;
            transform: translateY(-2px);
        }
        .select2-container .select2-selection--single {
            height: auto !important; /* Ajuste la hauteur pour Select2 */
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
            right: 10px;
        }
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .input-group-append .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .total-summary {
            background-color: #f8f9fa;
            border-left: 5px solid #666ee8;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            font-size: 1.1rem;
            color: #333;
        }
        .total-summary strong {
            color: #666ee8;
        }
        .is-invalid + .select2-container .select2-selection--single {
            border-color: #dc3545 !important;
        }
        .invalid-feedback {
            display: block; /* S'assurer qu'il est toujours affiché en cas d'erreur */
        }
    </style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <section id="flow-data-form" class="animate__animated animate__fadeInUp">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><i class="fas fa-plus-circle mr-2"></i> Ajouter un Nouveau Flux</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form class="form form-horizontal" id="fluxForm" method="POST" action="{{route('flux.store')}}">
                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="fas fa-map-marked-alt"></i> Localisation du Flux</h4>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group @error('departements_id') is-invalid @enderror">
                                                        <label for="departements_id">Département <span class="text-danger">*</span></label>
                                                        <select id="departements_id" class="form-control select2" name="departements_id" required>
                                                            <option value="">-- Choisir un département --</option>
                                                            @foreach ($departements as $departement)
                                                                <option value="{{$departement->id}}" {{ $departement->id == old("departements_id") ? "selected":"" }}>
                                                                    {{ $departement->lib_departement }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('departements_id')
                                                            <div class="invalid-feedback">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group @error('frontieres_id') is-invalid @enderror position-relative">
                                                        <label for="frontieres_id">Frontière <span class="text-danger">*</span></label>
                                                        <select id="frontieres_id" class="form-control select2" name="frontieres_id" required>
                                                            <option value="">-- Sélectionner une frontière --</option>
                                                        </select>
                                                        <div class="loading-overlay" id="frontiere-loading">
                                                            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                                                        </div>
                                                        @error('frontieres_id')
                                                            <div class="invalid-feedback">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <h4 class="form-section mt-4"><i class="fas fa-chart-line"></i> Détails du Mouvement</h4>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group @error('total_entree') is-invalid @enderror">
                                                        <label for="total_entree">Total Entrées <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-sign-in-alt"></i></span>
                                                            </div>
                                                            <input type="number" id="total_entree" class="form-control" placeholder="0" name="total_entree" value="{{old('total_entree')}}" min="0" required>
                                                        </div>
                                                        @error('total_entree')
                                                            <div class="invalid-feedback">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group @error('total_sortie') is-invalid @enderror">
                                                        <label for="total_sortie">Total Sorties <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-sign-out-alt"></i></span>
                                                            </div>
                                                            <input type="number" id="total_sortie" class="form-control" placeholder="0" name="total_sortie" value="{{old('total_sortie')}}" min="0" required>
                                                        </div>
                                                        @error('total_sortie')
                                                            <div class="invalid-feedback">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group @error('pays_id') is-invalid @enderror">
                                                        <label for="pays_id">Nationalité <span class="text-danger">*</span></label>
                                                        <select id="pays_id" class="form-control select2" name="pays_id" required>
                                                            <option value="">-- Sélectionner le pays --</option>
                                                            @foreach ($pays as $pay)
                                                                <option value="{{$pay->id}}" {{$pay->id == old("pays_id") ? "selected":""}}>{{$pay->lib_pays}}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('pays_id')
                                                            <div class="invalid-feedback">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group @error('date_movement') is-invalid @enderror">
                                                        <label for="date_movement">Date des Données <span class="text-danger">*</span></label>
                                                        <input type="date" id="date_movement" class="form-control" name="date_movement" value="{{old('date_movement', date('Y-m-d'))}}" max="{{ date('Y-m-d') }}" required>
                                                        @error('date_movement')
                                                            <div class="invalid-feedback">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="total-summary animate__animated animate__fadeIn mt-4" id="dynamic-summary">
                                                <p>Résumé du Jour :</p>
                                                <p>Total Entrées Saisies: <strong id="summary-entree">0</strong> personnes</p>
                                                <p>Total Sorties Saisies: <strong id="summary-sortie">0</strong> personnes</p>
                                                <p>Mouvement Net: <strong id="summary-net">0</strong> personnes</p>
                                            </div>

                                        </div>

                                        <div class="form-actions text-right mt-5 animate__animated animate__fadeInUp animate__delay-1s">
                                            <hr>
                                            <a href="{{route('flux.index')}}" class="btn btn-outline-warning mr-2">
                                                <i class="fas fa-arrow-alt-circle-left"></i> Retour
                                            </a>
                                            <button type="submit" class="btn btn-primary" id="submitButton">
                                                <i class="fas fa-check-circle"></i> Enregistrer le Flux
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialisation de Select2 avec des options de recherche
        $('.select2').select2({
            width: '100%',
            placeholder: "Sélectionner une option",
            allowClear: true // Permet de vider la sélection
        });

        // Fonction pour mettre à jour le résumé dynamique
        function updateDynamicSummary() {
            const entree = parseInt($('#total_entree').val()) || 0;
            const sortie = parseInt($('#total_sortie').val()) || 0;
            const net = entree - sortie;

            $('#summary-entree').text(entree);
            $('#summary-sortie').text(sortie);
            $('#summary-net').text(net);

            // Appliquer des classes pour la couleur du mouvement net
            const $netSummary = $('#summary-net');
            $netSummary.removeClass('text-success text-danger');
            if (net > 0) {
                $netSummary.addClass('text-success');
            } else if (net < 0) {
                $netSummary.addClass('text-danger');
            }
        }

        // Mettre à jour le résumé lors de la saisie
        $('#total_entree, #total_sortie').on('input', updateDynamicSummary);
        // Appel initial pour afficher les valeurs par défaut
        updateDynamicSummary();


        // Gestionnaire de changement de département avec indicateur de chargement
        $("#departements_id").on("change", function() {
            const id = $(this).val();
            const $frontiereSelect = $("#frontieres_id");
            const $loadingOverlay = $("#frontiere-loading");

            // Réinitialiser les options de frontière
            $frontiereSelect.empty().append('<option value="">-- Sélectionner une frontière --</option>').val(null).trigger('change');
            
            if (id) {
                $loadingOverlay.addClass('active'); // Afficher le spinner
                
                getFrontieresByDepartement(id, function() {
                    $loadingOverlay.removeClass('active'); // Masquer le spinner après chargement
                });
            } else {
                $loadingOverlay.removeClass('active');
            }
        });

        // Fonction AJAX pour récupérer les frontières
        function getFrontieresByDepartement(id, callback) {
            let route = "{{route('flux.getFrontieresByDepartement', ':id')}}";
            route = route.replace(":id", id);

            $.ajax({
                url: route,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let options = "<option value=''>-- Sélectionner une frontière --</option>";
                    if (response.length > 0) {
                        response.forEach(item => {
                            options += `<option value="${item.id}">${item.lib_frontiere}</option>`;
                        });
                    } else {
                        options = "<option value=''>Aucune frontière trouvée pour ce département</option>";
                    }
                    $("#frontieres_id").empty().append(options).trigger('change');

                    // Pré-sélectionner si old('frontieres_id') existe (pour la validation Laravel)
                    @if(old('frontieres_id'))
                        $("#frontieres_id").val("{{ old('frontieres_id') }}").trigger('change');
                    @endif
                    
                    if (callback) callback();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Erreur lors de la récupération des frontières. Veuillez réessayer.',
                        confirmButtonColor: '#666ee8'
                    });
                    if (callback) callback();
                }
            });
        }

        // Initialisation pour le cas où un département est déjà sélectionné (par exemple, après une erreur de validation)
        @if(old('departements_id'))
            $("#departements_id").trigger('change');
        @endif

        // Validation front-end simple et SweetAlert pour la soumission
        $('#fluxForm').on('submit', function(e) {
            // Ici, vous pouvez ajouter une validation JavaScript plus complexe avant la soumission
            // Pour l'instant, on se base sur la validation HTML5 'required' et Laravel

            const $submitButton = $('#submitButton');
            $submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...');

            // Afficher une alerte de succès (ou d'erreur) après la réponse du serveur
            // Ceci est un exemple, la vraie gestion se ferait côté Laravel avec des sessions flash
            // Pour simuler:
            // e.preventDefault(); // Empêche la soumission réelle pour la démo
            // setTimeout(() => {
            //     Swal.fire({
            //         icon: 'success',
            //         title: 'Enregistré!',
            //         text: 'Les données de flux ont été ajoutées avec succès.',
            //         showConfirmButton: false,
            //         timer: 2000
            //     }).then(() => {
            //         // Recharger la page ou rediriger
            //         // window.location.href = "{{ route('flux.index') }}";
            //     });
            //     $submitButton.prop('disabled', false).html('<i class="fas fa-check-circle"></i> Enregistrer le Flux');
            // }, 2000);
        });

        // Gérer les messages de session Laravel avec SweetAlert2
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                confirmButtonColor: '#666ee8'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#666ee8'
            });
        @endif
    });
</script>
@endsection