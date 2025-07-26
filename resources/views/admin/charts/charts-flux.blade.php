@extends("admin.layouts.app")
@section("title")
    Graphes
@endsection
@section("styles")
<link rel="stylesheet" type="text/css" href="{{asset("res/app-assets/vendors/css/vendors.min.css")}}">
<link rel="stylesheet" type="text/css" href="{{asset("res/app-assets/vendors/css/charts/chartist.css")}}">
@endsection
@section("content")
<div class="app-content content">
<div class="content-overlay"></div>
<div class="content-wrapper">
<div class="content-header row"></div>
<div class="content-body">
    <!-- chartist line charts section start -->
    <section id="chartjs-line-charts">
        <!-- Line Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Statistique générale </h4>
                        <form class="form-inline search">
                            <select name="" id="month">
                                <option value="">Choisir</option>
                                @forelse ($month as $item)
                                    <option value="{{$item + 1 }}" {{ ($item + 1) == date("m") ? "selected":"" }}>{{ $months[$item] }}</option>
                                @empty

                                @endforelse
                            </select>
                            <select name="" id="year">
                                @forelse ($years as $item)
                                    <option value="{{$item}}"  {{$item == date("Y") ? "selected":""}}>{{ $item }}</option>
                                @empty

                                @endforelse
                            </select>
                            <input type="submit" value="Charger">
                        </form>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body chartjs">
                            <canvas id="line-chart" height="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // chartist line charts section end -->
</div>
</div>
</div>
@endsection
@section("scripts")
<!-- BEGIN: Page Vendor JS-->
<script src="{{asset("res/app-assets/vendors/js/charts/chart.min.js")}}"></script>
<!-- END: Page Vendor JS-->
<script src="{{asset("res/app-assets/js/scripts/charts/chartjs/line/line-flux.js")}}"></script>
<script>
    $(window).on("load", function(){
        var div = $("#line-chart");
        var route = "{{route("graphes.migratoires")}}";
        $("form.search").on("submit",function(){
            var month = $("#month").val();
            var year = $("#year").val();
            load(div,month,year,route);
            return false;
        });

        var month = "{{date("m")}}";
        var year = "{{date("Y")}}";
        load(div,month,year,route);

    });

    function load(div,month,year,route){
        $.get(route,{month:month,year:year},function(response){

                if(month && year){
                    var labels = response.jours;
                    var matrix = [
                        response.entrees,
                        response.sorties
                    ];
                    loadGraph(div,labels,matrix,month,year);
                }else{
                    var entrees = response.entrees;
                    var sorties = response.sorties;
                    var theRemovedTotal = entrees.shift();
                    var moisRemoved = sorties.shift()
                    var matrix = [
                        entrees,
                        sorties
                    ];
                    loadGraphYear(div,matrix,year);
                }

            });
    }
</script>
@endsection
