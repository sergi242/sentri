@extends('admin.layouts.app')
@section('title')
Liste des attributions
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
                <div class="col-xl-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Liste des demandes {{ $critere=="jour" ? "d'aujourd'hui":"" }} {{ $critere=="semaine" ? "de cette semaine":"" }} {{ $critere=="mois" ? "du mois courant":"" }} {{ $critere=="annee" ? "de l'année courante":"" }}</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a href="{{ route('demandes.create') }}" class="btn btn-primary" style="color: white">Ajouter une nouvelle demande</a></li>
                                </ul>

                            </div>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Photo</th>
                                                <th>Impétrant</th>
                                                <th>Sexe</th>
                                                <th>Date de naissance</th>
                                                <th>Type demande</th>
                                                <th>Validité</th>
                                                <th>Statut demande</th>
                                                <th>Date demande</th>
                                                {{-- <th>Actions</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count = 1;
                                            @endphp
                                            @forelse ($demandes as $demande)

                                                <tr>
                                                    <td>{{ $count++ }}</td>
                                                    <td><img src="{{asset('app/'.$demande->photo)}}" width="60" height="60" alt=""></td>
                                                    <td>{{$demande->nom}} {{$demande->prenom}}</td>
                                                    <td>{{$demande->sexe}}</td>
                                                    <td>{{$demande->date_naissance}}</td>
                                                    <td>{{$demande->type_demande}}</td>
                                                    {{-- <td>{{$demande->type_demande}}</td> --}}
                                                    <td>{{$demande->validite}} an(s)</td>
                                                    <td>{{$demande->statut_demande}}</td>
                                                    <td>{{$demande->date_demande}}</td>


                                                </tr>
                                            @empty

                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>N°</th>
                                                <th>Photo</th>
                                                <th>Impétrant</th>
                                                <th>Sexe</th>
                                                <th>Date de naissance</th>
                                                <th>Type demande</th>
                                                <th>Validité</th>
                                                <th>Statut demande</th>
                                                <th>Date demande</th>
                                                {{-- <th>Actions</th> --}}
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
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
        $('.zero-configuration').DataTable();
    });
</script>
@endsection
