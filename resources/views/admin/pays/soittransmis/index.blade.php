@extends('admin.layouts.app')
@section('title')
    Gestion des Soit Transmis
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
                            <h4 class="card-title">Liste des Soit-ransmis</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <a href="{{ route('soit-transmis.create') }}" class="btn btn-primary">Ajouter un Soit-Transmi</a>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Nombre de demandes</th>
                                                <th>Signataire</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($soit_transmis as $st)
                                                    <tr>
                                                        <td>{{$st->numero}}</td>
                                                        <td> {{$st->demandes_count}} </td>
                                                        <td>{{$st->users->nom}}&nbsp;{{$st->users->prenom}}</td>
                                                        <td>
                                                            <div class="btn-group btn-block">
                                                                <button type="button" class="btn btn-dark btn-sm">Action</button>
                                                                <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">
    
    
                                                                    <a class="dropdown-item" href="{{route('soit-transmis.show',$st->id)}}">Voir le Soit-Transmis</a>
                                                                    <a class="dropdown-item" href="{{route('soit-transmis.demandes.show',$st->id)}}">Ajouter des demandes</a>
                                                                    <a class="dropdown-item" href="{{route('demandes.edit',$st->id)}}">Modifier</a>
    
                                                                    {{-- <a class="dropdown-item a-del" href="{{route('package.destroy',$package->id)}}">Supprimer</a> --}}
                                                                    <form action="{{route('soit-transmis.destroy',$st->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item a-del">Supprimer</button>
                                                                    </form>
                                                                </div>
                                                                </div>
                                                        </td>
                                                    </tr>
                                            @empty

                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Nombre de demandes</th>
                                                <th>Signataire</th>
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
