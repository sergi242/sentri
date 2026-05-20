@extends('admin.layouts.app')
@section('title')
    Gestion des employeurs
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

                <div class="col-xl-5 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Ajouter un employeur</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <form class="form form-horizontal" action="{{route('employeur.store')}}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <h4 class="form-section"><i class="ft-folder"></i> Information de l'employeur</h4>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="employeur">Nom *</label>
                                            <div class="col-md-9 mx-auto">
                                                <input type="text" value="{{old('nom_employeur')}}" id="employeur" class="form-control @error('employeur') is-invalid @enderror" placeholder="Nom" name="nom_employeur" required>
                                                @error('employeur')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="employeur">Type *</label>
                                            <div class="col-md-9 mx-auto">
                                                <select name="type" class="form-control" id="subject" class="form-select">
                                                    <option value="Personne morale">Personne morale</option>
                                                    <option value="Personne physique">Personne physique</option>
                                                    <option value="diplomate">Diplomate</option>

                                                </select>
                                                @error('employeur')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="employeur">Adresse *</label>
                                            <div class="col-md-9 mx-auto">
                                                <input type="text" value="{{old('adresse_physique')}}" id="employeur" class="form-control @error('employeur') is-invalid @enderror" placeholder="Adresse" name="adresse_physique" required>
                                                @error('employeur')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="email">Email</label>
                                            <div class="col-md-9 mx-auto">
                                                <input type="email" value="{{old('email')}}" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" required>
                                                @error('email')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="telephone">Téléphone</label>
                                            <div class="col-md-9 mx-auto">
                                                <input type="text" value="{{old('telephone')}}" id="telephone" class="form-control @error('telephone') is-invalid @enderror" placeholder="Téléphone" name="telephone" required>
                                                @error('telephone')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="la la-check-square-o"></i> Sauvegarder
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Affichage des employeurs</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Type</th>
                                                <th>Adresse</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @forelse ($employeurs as $employeur)
                                                        <tr>
                                                            <td>{{$employeur->nom_employeur}}</td>
                                                            <td>{{$employeur->type}}</td>
                                                            <td>{{$employeur->adresse_physique}}</td>
                                                            <td>
                                                                <div class="btn-group btn-block">
                                                                    <button type="button" class="btn btn-dark btn-sm">Action</button>
                                                                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                                                    </button>
                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">

                                                                      <a class="dropdown-item" href="{{route('employeur.edit',$employeur->id)}}">Modifier</a>
                                                                    <form action="{{route('employeur.destroy',$employeur->id)}}" method="post">
                                                                        @csrf
                                                                        @method("DELETE")
                                                                        <input class="dropdown-item" value="Supprimer"/>
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
                                                <th>Type</th>
                                                <th>Adresse</th>
                                                <th>Action</th>
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
