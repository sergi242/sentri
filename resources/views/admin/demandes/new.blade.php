@extends('admin.layouts.app')
@section('title')
Liste des demandes
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Revenue, Hit Rate & Deals -->
            <div class="row">
                <div class="col-2"></div>
                <div class="col-3 animated fadeIn">
                    <h3 class="text-center text-white bg-blue">Nouvelle demande CRT</h3>
                    <a href="{{ route("demandes.newcrt") }}" class="demande"><img src=" {{ asset("img/models/ccrt.jpg") }}" alt="" class="img-thumbnail"></a>
                </div>
                <div class="col-2"></div>
                <div class="col-3 animated fadeIn">
                    <h3 class="text-center text-white bg-blue">Nouvelle demande Visa</h3>
                    <a href="{{ route("demandes.newvisa") }}" class="demande"><img src=" {{ asset("img/models/cvisa.jpg") }}" alt="" class="img-thumbnail"></a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-3 animated fadeIn">
                    <h3 class="text-center text-white bg-blue">Renouvellement CRT</h3>
                    <a href="{{ route("demandes.renouvellement") }}" class="demande"><img src=" {{ asset("img/crt/recto.png") }}" alt="" class="img-thumbnail"></a>
                </div>
                <div class="col-2"></div>
                <div class="col-3 animated fadeIn">
                    <h3 class="text-center text-white bg-blue">Renouvellement Visa</h3>
                    <a href="{{ route("demandes.renouvellement") }}" class="demande"><img src=" {{ asset("img/models/cvisa.jpg") }}" alt="" class="img-thumbnail"></a>
                </div>
            </div>
            <!--/ Revenue, Hit Rate & Deals -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script>
    $(function(){

    });
</script>
@endsection
