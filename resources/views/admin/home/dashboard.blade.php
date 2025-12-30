@extends('admin.layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body animated rotateInDownRight">
            <h3 class="text text-white bg-secondary p-1">Statistiques des demandes et impétrants</h3>
            <!-- Revenue, Hit Rate & Deals -->
            <div class="row">
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <a href="{{route("impetrants.index")}}?layout=cards">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left">
                                            <h6 class="text-muted">Total impétrants</h6>
                                            <h3>{{ collect(DB::select("select count(*) as total from impetrants"))->first()->total }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <a href="{{route("demandes.demandestats")}}?critere=jour">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left">
                                            <h6 class="text-muted">Aujourd'hui</h6>
                                            <h3>{{ $today->nombre }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <a href="{{route("demandes.demandestats")}}?critere=semaine">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Semaine courante</h6>
                                        <h3>{{ $week->nombre }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </a>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <a href="{{route("demandes.demandestats")}}?critere=mois">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Mois courant</h6>
                                        <h3>{{ $month->nombre}}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <a href="{{route("demandes.demandestats") }}?critere=annee">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Année courante</h6>
                                        <h3>{{ $year->nombre }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>

            </div>
            <h3 class="text text-white bg-secondary p-1">Statistiques des attributions documents (Visa et CRT)</h3>
            <!-- Revenue, Hit Rate & Deals -->
            <div class="row">
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <a href="{{ route("demandes.stats.attributions") }}?critere=jour">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Aujourd'hui</h6>
                                        <h3>{{ $todayAtt->nombre }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-car success font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <a href="{{ route("demandes.stats.attributions") }}?critere=semaine">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Semaine courante</h6>
                                        <h3>{{ $weekAtt->nombre }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <a href="{{ route("demandes.stats.attributions") }}?critere=mois">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Mois courant</h6>
                                        <h3>{{ $monthAtt->nombre}}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <a href="{{ route("demandes.stats.attributions") }}?critere=annee">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Année courante</h6>
                                        <h3>{{ $yearAtt->nombre }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </a>
                    </div>
                </div>

            </div>
            <h3 class="text text-white bg-secondary p-1">Statistiques des flux migratoire (Entrée)</h3>
            <!-- Revenue, Hit Rate & Deals -->
            <div class="row">
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                    <a href="{{ route("flux.stats.entre") }}?critere=jour">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Aujourd'hui</h6>
                                        <h3>{{ $todayFlux->total_entree ?? 0 }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-car success font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </a>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <a href="{{ route("flux.stats.entre") }}?critere=semaine">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Semaine courante</h6>
                                        <h3>{{ $weekFlux->total_entree ?? 0 }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <a href="{{ route("flux.stats.entre") }}?critere=mois">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Mois courant</h6>
                                        <h3>{{ $monthFlux->total_entree ?? 0 }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <a href="{{ route("flux.stats.entre") }}?critere=annee">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Année courante</h6>
                                        <h3>{{ $yearFlux->total_entree ?? 0 }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <h3 class="text text-white bg-secondary p-1">Statistiques des flux migratoire (Sortie)</h3>
            <!-- Revenue, Hit Rate & Deals -->
            <div class="row">
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <a href="{{ route("flux.stats.sortie") }}?critere=jour">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">

                                        <h6 class="text-muted">Aujourd'hui</h6>
                                        <h3>{{ $todayFlux->total_sortie ?? 0 }}</h3>

                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-car success font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <a href="{{ route("flux.stats.sortie") }}?critere=semaine">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Semaine courante</h6>
                                        <h3>{{ $weekFlux->total_sortie ?? 0 }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <a href="{{ route("flux.stats.sortie") }}?critere=mois">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Mois courant</h6>
                                        <h3>{{ $monthFlux->total_sortie ?? 0 }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card pull-up">
                        <div class="card-content">
                            <a href="{{ route("flux.stats.sortie") }}?critere=annee">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h6 class="text-muted">Année courante</h6>
                                        <h3>{{ $yearFlux->total_sortie ?? 0 }}</h3>
                                    </div>
                                    {{-- <div class="align-self-center">
                                        <i class="icon-call-in danger font-large-2 float-right"></i>
                                    </div> --}}
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
