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
                    <div class="col-md-9">
                        <div class="card animated fadeIn">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    {{-- <img src="{{asset('img/icon/pdf.png')}}" alt="" style="width: 6%;"> --}}
                                    <h3>Reporting par Employeur</h3>
                                    <!-- ... (votre code existant) -->

                                    <!-- ... (votre code existant) -->
                                    <form action="{{ route('reporting.employeur.pdf') }}" method="GET">
                                        <!-- ... autres champs du formulaire ... -->
                                        <input type="hidden" name="export_pdf" value="1">
                                        <input type="hidden" name="employeur_id" value="{{ $employeurId }}">
                                        <input type="hidden" name="nomDocument" value="{{ $nomDocument }}">
                                        <input type="hidden" name="duree_travail_domicile_de" value="{{ $dateDebut }}">
                                        <input type="hidden" name="duree_travail_domicile_a" value="{{ $dateFin }}">
                                        <input type="hidden" name="type_employeur" value="{{ $typeEmployeur }}">

                                        <button type="submit" class="btn btn-secondary">Exporter</button>
                                    </form>



                                    <canvas id="nationalitesChart" width="200" height="200"></canvas>

                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                    <script>
                                        // Récupérer les données du contrôleur
                                        var nationalitesParEmployeur = @json($nationalitesParEmployeur);

                                        // Créer un graphique à barres empilées par employeur
                                        var ctx = document.getElementById('nationalitesChart').getContext('2d');
                                        var nationalitesChart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: Object.keys(nationalitesParEmployeur),
                                                datasets: Object.keys(nationalitesParEmployeur[Object.keys(nationalitesParEmployeur)[0]]).map(function (nationalite, index) {
                                                    return {
                                                        label: nationalite,
                                                        data: Object.values(nationalitesParEmployeur).map(function (data) {
                                                            return data[nationalite] || 0;
                                                        }),
                                                        backgroundColor: 'rgba(' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ', 0.6)',
                                                        borderColor: 'rgba(' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ', 1)',
                                                        borderWidth: 1
                                                    };
                                                })
                                            },
                                            options: {
                                                scales: {
                                                    x: { stacked: true },
                                                    y: { stacked: true }
                                                }
                                            }
                                        });
                                    </script>


                                </div>
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
