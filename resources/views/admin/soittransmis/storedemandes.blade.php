@extends('admin.layouts.app')
@section('title')
    Ajout des demandes dans le Soit-Transmis
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
                        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #66DB4F">
                            <h2 class="card-title" style="color: #ffffff">
                                <strong>Ajout des demandes dans le Soit-Transmis</strong>
                            </h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">
                                <strong>Soit-Transmis :</strong> {{$soit_transmis->numero}}
                                |
                                <strong>Signataire :</strong> {{ $soit_transmis->user ? $soit_transmis->user->getNomPrenom() : 'Non défini' }}
                                | 
                                <strong>Nombre de demandes :</strong> {{$soit_transmis->demandes_count}}
                            </h4>
                            <div>
                                <a href="{{ route('soit-transmis.show', ['id' => $soit_transmis->id]) }}" class="btn btn-secondary">Suivant</a>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="card">
                        <div class="card-content collapse show">
                            <br>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Numero Fiche</th>
                                                <th>Nationnalité</th>
                                                <th>Genre</th>
                                                <th>Nom du demandeur</th>
                                                <th>Prenom du demandeur</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($demandes as $demande)
                                                    <tr>
                                                        <th><img src="{{asset('app/'.$demande->photo)}}" width="60" height="60" alt=""></th>
                                                        <td>{{$demande->uuid}}</td>
                                                        <td>{{$demande->impetrant->pays->nationalite}}</td>
                                                        <td>{{$demande->impetrant->sexe}}</td>
                                                        <td>{{$demande->impetrant->nom}}</td>
                                                        <td>{{$demande->impetrant->prenom}}</td>
                                                        <td>
                                                            <div class="btn-group btn-block">
                                                                <form action="{{route('soit-transmis.demandes.store')}}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="soit_transmis_id" value="{{$soit_transmis->id}}">
                                                                    <input type="hidden" name="demande_id" value="{{$demande->id}}">
                                                                    <button type="submit" class="btn btn-success"><i class="la  la-plus"></i>Ajouter</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            @empty

                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Numero Fiche</th>
                                                <th>Nationnalité</th>
                                                <th>Genre</th>
                                                <th>Nom du demandeur</th>
                                                <th>Prenom du demandeur</th>
                                                <th>Actions</th>

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
