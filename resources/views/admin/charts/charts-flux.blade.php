@extends("admin.layouts.app")

@section("title", "Statistiques Flux Migratoires")

@section("styles")
<link rel="stylesheet" type="text/css" href="{{asset("res/app-assets/vendors/css/vendors.min.css")}}">
<style>
    /* Design des filtres */
    .filter-bar {
        background: #f4f7fa;
        padding: 15px 25px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    .custom-select-modern {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 8px 15px;
        background: white;
        font-weight: 600;
        color: #495057;
        cursor: pointer;
    }
    .btn-load {
        background: #1e9ff2;
        color: white;
        border: none;
        padding: 8px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-load:hover { background: #0c84d1; box-shadow: 0 4px 12px rgba(30, 159, 242, 0.4); }

    /* KPI Cards */
    .stat-kpi {
        border-left: 4px solid #1e9ff2;
        padding: 10px 20px;
        background: #fcfdfe;
        border-radius: 8px;
    }
    .kpi-title { font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px; }
    .kpi-value { font-size: 1.5rem; font-weight: 800; color: #2c3e50; }

    /* Chart Container */
    .chart-container {
        position: relative;
        padding: 20px;
        background: white;
    }
    
    /* Loader overlay */
    #chart-loader {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10;
        backdrop-filter: blur(2px);
    }
</style>
@endsection

@section("content")
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            
            <div class="row align-items-center mb-2">
                <div class="col-md-6">
                    <h2 class="font-weight-bold"><i class="la la-line-chart text-primary"></i> Flux Migratoires</h2>
                    <p class="text-muted">Analyse comparative des entrées et sorties du territoire.</p>
                </div>
                <div class="col-md-6">
                    <form class="form-inline search justify-content-md-end">
                        <select name="month" id="month" class="custom-select-modern mr-1">
                            <option value="">Année complète</option>
                            @foreach ($month as $item)
                                <option value="{{$item + 1 }}" {{ ($item + 1) == date("m") ? "selected":"" }}>{{ $months[$item] }}</option>
                            @endforeach
                        </select>
                        <select name="year" id="year" class="custom-select-modern mr-1">
                            @foreach ($years as $item)
                                <option value="{{$item}}" {{$item == date("Y") ? "selected":""}}>{{ $item }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-load shadow-sm">
                            <i class="la la-refresh"></i> Analyser
                        </button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg border-0" style="border-radius: 20px;">
                        <div class="card-header bg-transparent border-0 pt-2 px-3">
                            <div class="row w-100">
                                <div class="col-md-3">
                                    <div class="stat-kpi">
                                        <div class="kpi-title">Entrées</div>
                                        <div class="kpi-value text-success" id="total-entrees">--</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-kpi" style="border-left-color: #ff9149;">
                                        <div class="kpi-title">Sorties</div>
                                        <div class="kpi-value text-warning" id="total-sorties">--</div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="heading-elements" style="top: 10px;">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="reload" id="manual-reload"><i class="ft-rotate-cw"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-content">
                            <div class="card-body chart-container">
                                <div id="chart-loader">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </div>
                                <canvas id="line-chart" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section("scripts")
<script src="{{asset("res/app-assets/vendors/js/charts/chart.min.js")}}"></script>
<script src="{{asset("res/app-assets/js/scripts/charts/chartjs/line/line-flux.js")}}"></script>

<script>
    $(window).on("load", function(){
        const div = $("#line-chart");
        const route = "{{route('graphes.migratoires')}}";
        const loader = $("#chart-loader");

        // Intercepter le formulaire
        $("form.search").on("submit", function(e){
            e.preventDefault();
            triggerLoad();
        });

        // Fonction pour rafraîchir les données
        function triggerLoad() {
            loader.css('display', 'flex');
            const month = $("#month").val();
            const year = $("#year").val();
            
            $.get(route, {month: month, year: year}, function(response){
                processData(response, month, year);
                updateKPIs(response);
                loader.fadeOut();
            });
        }

        function processData(response, month, year) {
            if(month && year){
                loadGraph(div, response.jours, [response.entrees, response.sorties], month, year);
            } else {
                // Nettoyage des données si année complète (shift des totaux si nécessaire)
                let ent = [...response.entrees];
                let sor = [...response.sorties];
                if(ent.length > 12) ent.shift();
                if(sor.length > 12) sor.shift();
                loadGraphYear(div, [ent, sor], year);
            }
        }

        function updateKPIs(data) {
            // Calcul simple des totaux reçus
            const sum = arr => arr.reduce((a, b) => parseInt(a) + parseInt(b), 0);
            $("#total-entrees").text(sum(data.entrees).toLocaleString());
            $("#total-sorties").text(sum(data.sorties).toLocaleString());
        }

        // Chargement initial
        triggerLoad();
    });
</script>
@endsection