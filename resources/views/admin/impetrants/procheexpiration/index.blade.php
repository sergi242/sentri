@extends('admin.layouts.app')
@section('title')
    Liste des impétrants irreguliers
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
            <div class="col-12">
                {{-- <h4 class="text-uppercase">Liste des impétrants irreguliers</h4> --}}
                {{-- <p>Une liste des personnes ayant fait la demande d'un Visa ou une Carte de Résident Temporaire. <br><a href="?layout=cards">Afficher sous forme de block</a></p> --}}
            </div>
            <!-- Revenue, Hit Rate & Deals -->
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Liste des impétrants dont les demandent expirent dans au moins 3 mois</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                {{-- <ul class="list-inline mb-0">
                                    <li><a href="{{ route('demandes.create') }}" class="btn btn-primary" style="color: white">Ajouter une nouvelle demande</a></li>
                                </ul> --}}

                            </div>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                {{ $demandes->links('admin.pagination.pagination') }}
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Photo</th>
                                                <th>Impétrant</th>
                                                <th>Sexe</th>
                                                <th>Nationalité</th>
                                                <th>Nombre demande</th>
                                                <th>Date expiration</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count = 1;
                                            @endphp
                                            @forelse ($demandes as $demande)

                                                <tr>
                                                    <td>{{ $count++ }}</td>
                                                    <td><img src="{{asset('app/'.$demande->demandes->last()?->photo)}}" width="60" height="60" alt=""></td>
                                                    <td>{{$demande->nomcomplet()}}</td>
                                                    <td>{{$demande->sexe}}</td>
                                                    <td>{{$demande->pays?->lib_pays}}</td>
                                                    <td>{{$demande->demandes->count()}}</td>
                                                    <td>{{$demande->demandes->last()?->date_expiration}}</td>

                                                    <td>
                                                        <div class="btn-group btn-block">
                                                            <button type="button" class="btn btn-dark btn-sm">Action</button>
                                                            <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">
                                                                @can("demandes.print")
                                                                <a class="dropdown-item" href="{{ route("impetrants.demandes",$demande->id) }}">Voir la situation complète</a>
                                                                @endcan
                                                            </div>
                                                          </div>
                                                    </td>
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
                                                <th>Nationalité</th>
                                                <th>Nombre demande</th>
                                                <th>Dernière demande</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                {{ $demandes->links('admin.pagination.pagination') }}
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
        // $('.zero-configuration').DataTable();
    });
</script>
@endsection
