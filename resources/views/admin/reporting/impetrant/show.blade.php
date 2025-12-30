@extends('admin.layouts.app')
@section('title')
    Recherche avancée
@endsection
@section('styles')

    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('img/editorial.css')}}" type="text/css">
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-content collapse show" style="background-color: #f9f9f9;">
                            <div class="card-body">
                                <!-- Formulaire principal -->
                                <form action="" method="GET" id="mainForm" target="_blank" onsubmit="return validateForm()">
                                    @csrf
                                    <input type="hidden" name="nom_document" value="{{ $nom_document }}">
                                    <input type="hidden" name="pays_id" value="{{ $pays_id }}">
                                    <input type="hidden" name="categories_id" value="{{ $categories_id }}">
                                    <input type="hidden" name="type_demande" value="{{ $type_demande }}">
                                    <input type="hidden" name="age_a" value="{{ $age_a }}">
                                    <input type="hidden" name="age_de" value="{{ $age_de }}">
                                    <input type="hidden" name="demande_de" value="{{ $demande_de }}">
                                    <input type="hidden" name="demande_a" value="{{ $demande_a }}">
                                    <input type="hidden" name="statut_demande" value="{{ $statut_demande }}">
                                    <input type="hidden" name="etat_civil" value="{{ $etat_civil }}">
                                    <input type="hidden" name="genre" value="{{ $genre }}">
            
                                    <!-- Champ caché pour l'action -->
                                    <input type="hidden" name="action" id="action">
            
                                    <div class="row align-items-center">
                                        <!-- Sélection de l'entête -->
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <label for="entete">Entête du Document</label>
                                                <select name="entete" id="entete" class="form-control" required>
                                                    <option value="" disabled selected>Choisir une entête</option>
                                                    <option value="1">MINISTERE DE L’INTERIEUR, DE LA DECENTRALISATION ET DU DEVELOPPEMENT LOCAL</option>
                                                    <option value="3">DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="row align-items-center mb-2">
                                        <!-- Sélection de la section -->
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="section">Section</label>
                                                <select name="section" id="section" class="form-control" required>
                                                    <option value="" disabled selected>Choisir une section</option>
                                                    @foreach(config('sections.sections') as $division)
                                                        <optgroup label="{{ $division['division'] }}">
                                                            @foreach($division['sections'] as $section)
                                                                <option value="{{ $section['name'] }}">{{ $section['section'] }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
            
                                        <!-- Sélection du signataire -->
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="signataire">Signataire</label>
                                                <select name="signataire" id="signataire" class="form-control" required>
                                                    <option value="" disabled selected>Choisir un signataire</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->getNomPrenom() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
            
                                    <!-- Commentaire -->
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="commentaire">Commentaires</label>
                                                <textarea name="commentaire" id="commentaire" class="form-control" rows="4" style="height: 120px; resize: none; background-color: white;" placeholder="Ajoutez un commentaire ici..."></textarea>
                                            </div>
                                        </div>
                                    </div>
            
                                    <!-- Graphique et Boutons d'action -->
                                    <div class="row align-items-center mt-3">
                                        <div class="col-md-8">
                                            <div style="width: 100%; height: 200px;">
                                                <canvas id="nationalitesChart" style="width: 100%; height: 100%;"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex flex-column">
                                            <button type="button" class="btn btn-secondary mb-2" style="font-size: 12px; padding: 6px 12px;" onclick="submitForm('{{ route('reporting.impetrant.pdf') }}')">
                                                <i class="la la-file-pdf-o"></i> Exporter PDF
                                            </button>
                                            <button type="button" class="btn btn-info mb-2" style="font-size: 12px; padding: 6px 12px;" onclick="submitForm('{{ route('reporting.impetrant.liste') }}')">
                                                <i class="la la-users"></i> Liste des utilisateurs
                                            </button>
                                        </div>
                                    </div>
                                </form>
            
                                <!-- Graphique des nationalités -->
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                <script>
                                    function validateForm() {
                                        const entete = document.getElementById('entete');
                                        const section = document.getElementById('section');
                                        const signataire = document.getElementById('signataire');
            
                                        if (!entete.value) {
                                            alert("Veuillez sélectionner une entête.");
                                            return false;
                                        }
            
                                        if (!section.value) {
                                            alert("Veuillez sélectionner une section.");
                                            return false;
                                        }
            
                                        if (!signataire.value) {
                                            alert("Veuillez sélectionner un signataire.");
                                            return false;
                                        }
            
                                        return true; // Si tout est valide, on soumet le formulaire
                                    }
            
                                    function submitForm(actionUrl) {
                                        const form = document.getElementById('mainForm');
                                        form.action = actionUrl; // Définir l'URL d'action
                                        if (validateForm()) {
                                            form.submit(); // Soumettre le formulaire seulement si la validation est OK
                                        }
                                    }
            
                                    // Initialisation du graphique
                                    var nationalitesParGenre = @json($nationalitesParGenre);
                                    var ctx = document.getElementById('nationalitesChart').getContext('2d');
                                    var genreColors = {
                                        'Féminin': '#ed4fa9',
                                        'Masculin': '#57a6dd'
                                    };
                                    var nationalitesChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: Object.keys(nationalitesParGenre),
                                            datasets: Object.keys(nationalitesParGenre[Object.keys(nationalitesParGenre)[0]]).map(function (genre) {
                                                return {
                                                    label: genre,
                                                    data: Object.values(nationalitesParGenre).map(function (data) {
                                                        return data[genre] || 0;
                                                    }),
                                                    backgroundColor: genreColors[genre] || '#75c6c9',
                                                    borderWidth: 1
                                                };
                                            })
                                        },
                                        options: {
                                            scales: {
                                                x: { stacked: false },
                                                y: { beginAtZero: true }
                                            },
                                            plugins: {
                                                title: { display: true, text: 'Nationalités par Genre' },
                                                legend: { display: true, position: 'top' }
                                            }
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            
            
            <!-- // Basic form layout section end -->
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection
@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('res/app-assets/js/scripts/forms/select/form-select2.min.js')}}"></script>
@endsection
