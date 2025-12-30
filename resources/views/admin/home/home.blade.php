@extends('admin.layouts.app')
@section('title')
    Dashboard
@endsection
@section("styles")
    <style>
        html body {
    height: 100%;
    /* background-color: #f4f5fa; */
    background:url("{{ asset("res/app-assets/images/backgrounds/bg-9.jpg") }}") center center no-repeat;
    direction: ltr; }
    </style>
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-2">
                    <div class="row">
                        @can("demandes.create")
                        <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <a href="{{route("demandes.newdocument")}}" class="btn btn-secondary btn-block">Nouvelle demande</a>
                        </div>
                        @endcan
                        @can("demandes.renew")
                        <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <a href="{{route("demandes.renouvellement")}}" class="btn btn-secondary btn-block">Renouvellement</a>
                        </div>
                        @endcan
                        {{-- <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <a href="{{route("demandes.index")}}" class="btn btn-secondary btn-block">Liste des demandes</a>
                        </div> --}}
                        @can("demandes.renew")
                        <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <a href="{{route("demandes.contentieux")}}" class="btn btn-secondary btn-block"> Demandes aux contentieux</a>
                        </div>
                        @endcan
                        {{-- <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <a href="{{route("demandes.approuvees")}}" class="btn btn-secondary btn-block">Liste des approuvées</a>
                        </div> --}}
                        @can("demandes.view.pending")
                        <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <a href="{{route("demandes.attentes")}}" class="btn btn-secondary btn-block">Demande en attente</a>
                        </div>
                        @endcan
                        @can("demandes.search.advanced")
                        <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <a href="{{route("demandes.search.form")}}" class="btn btn-secondary btn-block">Recherche avancée</a>
                        </div>
                        @endcan

                    </div>
                </div>
                <div class="col">
                    <h1 style="font-size: 40px; text-align:center !important"><strong>MINISTERE DE L'INTERIEUR, DE LA DECENTRALISATION <br>ET DU DEVELOPPEMENT LOCAL</strong></h1>
            <hr>
            <h2 style="font-size: 30px; text-align:center !important"><strong>CENTRALE D'INTELLINGENCE ET DE DOCUMENTATION</strong></h2>
            <hr>
            <h3 style="font-size: 25px; text-align:center !important"><strong>DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS</strong></h3>
                </div>
                <div class="col-2"></div>
            </div>

        </div>
    </div>
</div>
@endsection
