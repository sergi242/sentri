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
            <div class="col-12">
                <h4 class="text-uppercase">Description du soit transmis {{$soit_transmis->numero}}</h4>
                <p>{{$soit_transmis->description}}  <br><a href="{{Route('soit-transmis.index')}}">Voir tout les soit-transmis</a></p>
            </div>
            <div class="form-group">
                <a href="{{ route('soit-transmis.edit', ['id' => $soit_transmis->id]) }}" class="btn btn-primary">Modifier</a>
            </div>

            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #5888A6">
                            <h2 class="card-title" style="color: #ffffff">
                                <strong>Soit-Transmis : {{$soit_transmis->numero}}</strong>
                            </h2>
                            <div>
                                <form action="{{route('soit.transmis.pdf')}}"  method="GET" target="_blank">
                                    @csrf
                                    <input type="hidden" name="soit_transmis_id" value="{{ $soit_transmis->id}}">
                                    <button class="btn btn-secondary">Exporter en PDF</button>
                                </form>
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
                                                <th>Uuid</th>
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
                                                        <td><img src="{{asset('app/'.$demande->photo)}}" width="60" height="60" alt=""></td>
                                                        <td>{{$demande->uuid}}</td>
                                                        <td>{{$demande->impetrant->pays->nationalite}}</td>
                                                        <td>{{$demande->impetrant->sexe}}</td>
                                                        <td>{{$demande->impetrant->nom}}</td>
                                                        <td>{{$demande->impetrant->prenom}}</td>
*                                                       <td>
                                                            <div style="display: flex; flex-direction: column; gap: 5px; align-items: flex-start;">
                                                                <!-- Bouton pour ouvrir le modal -->
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="openModal({{ $demande->id }})">
                                                                    <i class="la la-trash-o"></i> Retirer
                                                                </button>

                                                                <!-- Lien voir la demande -->
                                                                <a href="{{ route('demandes.show', $demande->id) }}" class="btn btn-sm btn-secondary">
                                                                    <i class="la la-eye"></i> Voir la demande
                                                                </a>
                                                            </div>

                                                            <!-- Modal -->
                                                            <div id="modal-{{ $demande->id }}" class="custom-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
                                                                <div style="background:#fff; padding:20px; max-width:400px; margin:10% auto; border-radius:8px; position:relative;">
                                                                    <h5>Confirmation</h5>
                                                                    <p>Es-tu sûr de vouloir retirer cette demande ?</p>

                                                                    <form action="{{ route('soit-transmis.dropdemandes') }}" method="POST" style="margin-top: 15px;">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="hidden" name="soit_transmis_id" value="{{ $soit_transmis->id }}">
                                                                        <input type="hidden" name="demande_id" value="{{ $demande->id }}">

                                                                        <button type="submit" class="btn btn-danger btn-sm">Confirmer</button>
                                                                        <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal({{ $demande->id }})">Annuler</button>
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
                                                <th>Photo</th>
                                                <th>Uuid</th>
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


    function openModal(id) {
        document.getElementById('modal-' + id).style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).style.display = 'none';
    }

</script>
@endsection
