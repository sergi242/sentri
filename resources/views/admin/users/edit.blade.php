@extends('admin.layouts.app')
@section('title')
    Modifier un utilisateur
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

                                    {{-- ══════════════════════════════════════════════════════
                                         Bannière d'avertissement : utilisateur SuperAdmin
                                         visible uniquement pour un Admin qui consulterait
                                         la page (le controller bloque déjà l'accès, mais
                                         cette bannière s'affiche si on est SuperAdmin et qu'on
                                         édite un autre SuperAdmin — à titre informatif)
                                    ══════════════════════════════════════════════════════ --}}
                                    @if($user->role->lib_role === 'SuperAdmin' && auth()->user()->role->lib_role === 'SuperAdmin' && auth()->id() !== $user->id)
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <i class="la la-exclamation-triangle"></i>
                                            <strong>Attention :</strong> Vous modifiez un compte SuperAdmin.
                                            <button type="button" class="close" data-dismiss="alert">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                    @endif

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
                                                        name="nom" value="{{ old('nom', $user->nom) }}" required>
                                                    @error('nom')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Email *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email', $user->email) }}" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Rôle -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Rôle *</label>
                                                <div class="col-md-9 mx-auto">

                                                    @php
                                                        $isSuperAdmin    = auth()->user()->role->lib_role === 'SuperAdmin';
                                                        $cibleSuperAdmin = $user->role->lib_role === 'SuperAdmin';
                                                    @endphp

                                                    @if(!$isSuperAdmin && $cibleSuperAdmin)
                                                        {{--
                                                            Ce cas ne devrait pas arriver (le controller redirige),
                                                            mais par sécurité on affiche le rôle en lecture seule.
                                                        --}}
                                                        <input type="hidden" name="roles_id" value="{{ $user->roles_id }}">
                                                        <input type="text" class="form-control" value="{{ $user->role->lib_role }}" readonly>
                                                    @else
                                                        <select name="roles_id" class="form-control @error('roles_id') is-invalid @enderror" required>
                                                            <option value="">Choisir le rôle</option>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ $role->id == old('roles_id', $user->roles_id) ? 'selected' : '' }}>
                                                                    {{ $role->lib_role }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('roles_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror

                                                        {{-- Note informative pour les non-SuperAdmin --}}
                                                        @if(!$isSuperAdmin)
                                                            <small class="text-muted">
                                                                <i class="la la-info-circle"></i>
                                                                Le rôle SuperAdmin ne peut être attribué que par un SuperAdmin.
                                                            </small>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Grade -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Grade *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="grades_id" class="form-control @error('grades_id') is-invalid @enderror" required>
                                                        <option value="">Choisir le grade</option>
                                                        @foreach ($grades as $grade)
                                                            <option value="{{ $grade->id }}"
                                                                {{ $grade->id == old('grades_id', $user->grades_id) ? 'selected' : '' }}>
                                                                {{ $grade->grade }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('grades_id')
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
                                                        <option value="1" {{ $user->active == 1 ? 'selected' : '' }}>Actif</option>
                                                        <option value="0" {{ $user->active == 0 ? 'selected' : '' }}>Inactif</option>
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

                                    <!-- Réinitialisation du mot de passe -->
                                    <form method="POST" action="{{ route('users.reset-password', $user->id) }}" style="display:inline;">
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
@push('scripts')
<script src="{{asset('res/app-assets/vendors/js/forms/select/selectivity-full.min.js')}}"></script>
<script>
    $(function () {});
</script>
@endpush