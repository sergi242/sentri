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
                                    <form class="form form-horizontal" 
                                        method="POST" 
                                        action="{{ route('users.update', $user->id) }}" 
                                        enctype="multipart/form-data">

                                        @csrf
                                        @method('PUT')

                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Information de l'Utilisateur</h4>

                                            <!-- Photo -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Photo</label>
                                                <div class="col-md-9">
                                                    <input type="file" name="photo" class="form-control">
                                                    @if($user->photo)
                                                        <div class="mt-2">
                                                            <img src="{{ asset('uploads/users/'.$user->photo) }}" width="120" class="img-thumbnail">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Prénom -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Prénom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                                        name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
                                                    @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>

                                            <!-- Nom -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Nom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                                        name="nom" value="{{ old('nom', $user->nom) }}" required>
                                                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Email *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email', $user->email) }}" required>
                                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>

                                            <!-- Rôle -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Rôle *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="roles_id" class="form-control @error('roles_id') is-invalid @enderror" required>
                                                        <option value="">Choisir le rôle</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}" {{ $role->id == $user->roles_id ? 'selected' : '' }}>
                                                                {{ $role->lib_role }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('roles_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>

                                            <!-- Grade -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Grade *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="grades_id" class="form-control @error('grades_id') is-invalid @enderror" required>
                                                        <option value="">Choisir le grade</option>
                                                        @foreach ($grades as $grade)
                                                            <option value="{{ $grade->id }}" {{ $grade->id == $user->grades_id ? 'selected' : '' }}>
                                                                {{ $grade->grade }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('grades_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>

                                            <!-- Etat -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Etat *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="active" class="form-control @error('active') is-invalid @enderror" required>
                                                        <option value="">Choisir</option>
                                                        <option value="1" {{ $user->active == 1 ? 'selected' : '' }}>Actif</option>
                                                        <option value="0" {{ $user->active == 0 ? 'selected' : '' }}>Inactif</option>
                                                    </select>
                                                    @error('active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Sauvegarder
                                            </button>
                                            <a href="{{ route('users.index') }}" class="btn btn-warning">Retour</a>
                                        </div>
                                    </form>

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
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/forms/select/selectivity-full.min.js')}}"></script>
<script>
    $(function(){});
</script>
@endsection
