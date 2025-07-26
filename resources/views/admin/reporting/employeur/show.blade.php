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
                                <form action="{{ route('reporting.employeur.pdf') }}" method="GET" target="_blank">
                                    @csrf
                                    <!-- Champs cachés existants -->
                                    <input type="hidden" name="employeur_id" value="{{ $employeurId }}">
                                    <input type="hidden" name="nomDocument" value="{{ $nomDocument }}">
                                    <input type="hidden" name="duree_travail_domicile_de" value="{{ $dateDebut }}">
                                    <input type="hidden" name="duree_travail_domicile_a" value="{{ $dateFin }}">
                                    <input type="hidden" name="type_employeur" value="{{ $typeEmployeur }}">

                                    <div class="row align-items-center">
                                        <!-- Sélection de l'entête -->
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <label for="entete">Entête du Document</label>
                                                <select name="entete" id="entete" class="form-control">
                                                    <option value="1" selected>MINISTERE DE L’INTERIEUR, DE LA DECENTRALISATION ET DU DEVELOPPEMENT LOCAL</option>
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
                                            <button type="submit" class="btn btn-secondary mb-2" style="font-size: 12px; padding: 6px 12px;">
                                                <i class="la la-file-pdf-o"></i> Exporter PDF
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Graphique des nationalités -->
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                <script>
                                    var nationalitesParEmployeur = @json($nationalitesParEmployeur);
                                    var ctx = document.getElementById('nationalitesChart').getContext('2d');
                                    var genreColors = {
                                        'Féminin': '#ed4fa9',
                                        'Masculin': '#57a6dd'
                                    };
                                    var nationalitesChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: Object.keys(nationalitesParEmployeur),
                                            datasets: Object.keys(nationalitesParEmployeur[Object.keys(nationalitesParEmployeur)[0]]).map(function (nationalite) {
                                                return {
                                                    label: nationalite,
                                                    data: Object.values(nationalitesParEmployeur).map(function (data) {
                                                        return data[nationalite] || 0;
                                                    }),
                                                    backgroundColor: genreColors[nationalite] || '#75c6c9',
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
                                                title: { display: true, text: 'Nationalités par Employeur' },
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
