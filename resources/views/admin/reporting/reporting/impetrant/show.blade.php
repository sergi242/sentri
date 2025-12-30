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
                                    <h3>Reporting par Impetrant ff {{ $nomDocument }}</h3>
                                    <!-- ... (votre code existant) -->

                                    <!-- ... (votre code existant) -->
                                    <form action="{{ route('reporting.impetrant.pdf') }}" method="GET">
                                        <!-- ... autres champs du formulaire ... -->
                                        <input type="hidden" name="nomDocument" value="{{ $nomDocument }}">
                                        <input type="hidden" name="paysId" value="{{ $paysId }}">
                                        <input type="hidden" name="categorieId" value="{{ $categorieId }}">
                                        <input type="hidden" name="type_demande" value="{{ $type_demande }}">
                                        <input type="hidden" name="age_a" value="{{ $age_a }}">
                                        <input type="hidden" name="age_de" value="{{ $age_de }}">
                                        <input type="hidden" name="demande_de" value="{{ $demande_de }}">
                                        <input type="hidden" name="demande_a" value="{{ $demande_a }}">
                                        <input type="hidden" name="statut_demande" value="{{ $statut_demande }}">
                                        <input type="hidden" name="etat_civil" value="{{ $etat_civil }}">
                                        <input type="hidden" name="genre" value="{{ $genre }}">

                                        <button type="submit" class="btn btn-secondary">Exporter</button>
                                    </form>



                                    <canvas id="nationalitesChart" width="200" height="200"></canvas>

                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                    <script>
                                        // Récupérer les données du contrôleur
                                        var nationalitesParGenre = @json($nationalitesParGenre);

                                        // Créer un graphique à barres empilées par employeur
                                        var ctx = document.getElementById('nationalitesChart').getContext('2d');
                                        var nationalitesChart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: Object.keys(nationalitesParGenre),
                                                datasets: Object.keys(nationalitesParGenre[Object.keys(nationalitesParGenre)[0]]).map(function (nationalite, index) {
                                                    return {
                                                        label: nationalite,
                                                        data: Object.values(nationalitesParGenre).map(function (data) {
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
