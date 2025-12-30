@extends('admin.layouts.app')
@section('title')
    Modifier l'employeur
@section('styles')
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <form class="form form-horizontal" method="POST" action="{{route('employeur.update',$employeur->id)}}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-edit"></i> Information de l'employeur</h4>

                                            <div class="form-group row">
                                                <div class="col-md-12 mx-auto">
                                                    <label for="type">Nom de l'employeur</label>
                                                    <input type="text" id="nom_employeur" class="form-control @error('nom_employeur') is-invalid @enderror" placeholder="Nom" name="nom_employeur" value="{{$employeur->nom_employeur}}">
                                                    @error('nom_employeur')
                                                        <div class="invalid-feedback">
                                                            {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-12 mx-auto">
                                                    <label for="type">Type</label>
                                                    <select id="type" class="form-control @error('type') is-invalid @enderror" name="type">
                                                        <option value="Personne morale" {{ $employeur->type == 'Personne morale' ? 'selected' : '' }}>Personne morale</option>
                                                        <option value="Personne physique" {{ $employeur->type == 'Personne physique' ? 'selected' : '' }}>Personne physique</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback">
                                                            {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-12 mx-auto">
                                                    <label for="type">Adresse de l'employeur</label>
                                                    <input type="text" id="employeur" class="form-control @error('employeur') is-invalid @enderror" placeholder="Adresse" name="adresse_physique" value="{{$employeur->adresse_physique}}">
                                                    @error('adresse_physique')
                                                        <div class="invalid-feedback">
                                                            {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12 mx-auto">
                                                <label for="email">Email</label>
                                                <input type="email" value="{{ $employeur->email }}" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" required>
                                                @error('email')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                    </div>
                                                @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12 mx-auto">
                                                <label  for="telephone">Téléphone</label>
                                                <input type="text" value="{{ $employeur->telephone }}" id="telephone" class="form-control @error('telephone') is-invalid @enderror" placeholder="Téléphone" name="telephone" required>
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
                                            <a href="{{route('employeur.index')}}" class="btn btn-danger">
                                                <i class="la la-back"></i> Retour
                                            </a>
                                        </div>
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
@endsection
