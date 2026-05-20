@extends('admin.layouts.app')
@section('title')
    Ajouter un utilisateur
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
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <form class="form form-horizontal" method="POST" action="{{route('users.store')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Information de l'Utilisateur</h4>

                                            <!-- Photo -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Photo</label>
                                                <div class="col-md-9">
                                                    <input type="file" name="photo" class="form-control">
                                                    @error('photo')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Prénom -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Prénom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                                        placeholder="Prénom" value="{{ old('prenom') }}" name="prenom" required>
                                                    @error('prenom')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Nom -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Nom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                                        placeholder="Nom" value="{{ old('nom') }}" name="nom" required>
                                                    @error('nom')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Email *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="email" value="{{ old('email') }}"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        placeholder="Email" name="email" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Rôle -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Rôle *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="roles_id" class="form-control @error('roles_id') is-invalid @enderror" required>
                                                        <option value="">Choisir le rôle</option>
                                                        @forelse ($roles as $role)
                                                            <option value="{{ $role->id }}" {{ $role->id == old('roles_id') ? 'selected' : '' }}>
                                                                {{ $role->lib_role }}
                                                            </option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                    @error('roles_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    {{-- Note informative pour les non-SuperAdmin --}}
                                                    @if(auth()->user()->role->lib_role !== 'SuperAdmin')
                                                        <small class="text-muted">
                                                            <i class="la la-info-circle"></i>
                                                            Le rôle SuperAdmin ne peut être attribué que par un SuperAdmin.
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Grade -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Grade *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="grades_id" class="form-control @error('grades_id') is-invalid @enderror" required>
                                                        <option value="">Choisir le grade</option>
                                                        @forelse ($grades as $grade)
                                                            <option value="{{ $grade->id }}" {{ $grade->id == old('grades_id') ? 'selected' : '' }}>
                                                                {{ $grade->grade }}
                                                            </option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                    @error('grades_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Mot de passe -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Mot de passe *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="password" id="password" value="{{ old('password') }}"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        placeholder="**********" name="password" required>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Confirmation mot de passe -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Confirmation Mot de passe *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="password" id="password-confirm" value="{{ old('password_confirmation') }}"
                                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                                        placeholder="**********" name="password_confirmation" required>
                                                    @error('password_confirmation')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- État -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">État *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="active" class="form-control @error('active') is-invalid @enderror" required>
                                                        <option value="">Choisir</option>
                                                        <option value="1" {{ "1" == old("active") ? "selected" : "" }}>Actif</option>
                                                        <option value="0" {{ "0" == old("active") ? "selected" : "" }}>Inactif</option>
                                                    </select>
                                                    @error('active')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
@push('scripts')
<script src="{{asset('res/app-assets/vendors/js/forms/select/selectivity-full.min.js')}}"></script>
<script>
    $(function () {});
</script>
@endpush