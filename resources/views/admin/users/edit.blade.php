@extends('admin.layouts.app')
@section('title')
    Modifier un équipement
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/selectivity-full.min.css')}}">
    <link rel="stylesheet" href="{{asset('res/app-assets/css/plugins/forms/selectivity/selectivity.css')}}">

@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <form class="form form-horizontal" method="POST" action="{{route('users.update',$user->id)}}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Information de l'Utilisateur</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="projectinput1">Prénom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="projectinput1" class="form-control @error('prenom') is-invalid @enderror" placeholder="Prénom" value="{{$user->prenom}}" name="prenom" required>
                                                    @error('prenom')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="projectinput1">Nom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="projectinput1" class="form-control @error('nom') is-invalid @enderror" placeholder="Nom" value="{{$user->nom}}" name="nom" required>
                                                    @error('nom')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="projectinput1">Email *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="email" value="{{$user->email}}" id="projectinput1" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="projectinput6">Rôle *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="projectinput6" name="roles_id" class="form-control @error('roles_id') is-invalid @enderror" required>
                                                        <option value="">Choisir le rôle</option>
                                                        @forelse ($roles as $role)
                                                            <option value="{{$role->id}}" {{$role->id==$user->roles_id ? "selected":""}}>{{$role->lib_role}}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>
                                                    @error('roles_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="projectinput6">Grade *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="projectinput6" name="grades_id" class="form-control @error('grades_id') is-invalid @enderror" required>
                                                        <option value="">Choisir le grade</option>
                                                        @forelse ($grades as $grade)
                                                            <option value="{{$grade->id}}" {{$grade->id==$user->grades_id ? "selected":""}}>{{$grade->grade}}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>
                                                    @error('grades_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="projectinput6">Etat *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="projectinput6" name="active" class="form-control @error('active') is-invalid @enderror" required>
                                                        <option value="">Choisir</option>
                                                        <option value="1" {{"1"==$user->active ? "selected":""}}>Actif</option>
                                                        <option value="0" {{"0"==$user->active ? "selected":""}}>Inactif</option>
                                                    </select>
                                                    @error('active')
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
                                            <a href="{{route('users.index')}}" class="btn btn-warning">Retour</a>
                                        </div>
                                    </form>
                                    <!-- Nouveau bouton pour la réinitialisation du mot de passe -->
                                    <form method="POST" action="{{ route('users.reset-password', $user->id) }}" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger mt-2">
                                            <i class="la la-refresh"></i> Réinitialiser le mot de passe
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- // Basic form layout section end -->
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection
@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/forms/select/selectivity-full.min.js')}}"></script>
<script>
    $(function(){

    });
</script>
@endsection
