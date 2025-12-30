@extends('admin.layouts.app')
@section('title')
    Ajouter une demande
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
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
                                    <form class="form form-horizontal" method="POST" action="{{route('demandes.store')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Information de l'Impétrant</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nom">Nom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" placeholder="Nom" value="{{old('nom')}}" name="nom" required>
                                                    @error('nom')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="prenom">Prénom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="prenom" class="form-control @error('prenom') is-invalid @enderror" placeholder="Prénom" value="{{old('prenom')}}" name="prenom" required>
                                                    @error('prenom')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="sexe">Sexe *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select  id="sexe" class="form-control @error('sexe') is-invalid @enderror"  name="sexe" required>
                                                        <option value="Masculin" {{"Masculin"==old("sexe") ? "selected":""}}>Masculin</option>
                                                        <option value="Féminin" {{"Féminin"==old("sexe") ? "selected":""}}>Féminin</option>
                                                    </select>
                                                    @error('sexe')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_naissance">Date de naissance *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror"  value="{{old('date_naissance')}}" name="date_naissance" required>
                                                    @error('date_naissance')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="lieu_naissance">Lieu de naissance *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror"  value="{{old('lieu_naissance')}}" name="lieu_naissance" placeholder="Lieu de naissance" required>
                                                    @error('lieu_naissance')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="etat_civil">Etat civil *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select class="select2-theme form-control" id="etat_civil" name="etat_civil">

                                                        @forelse ($etatsCivils as $etatsCivil)
                                                                <option value="{{$etatsCivil}}" {{$etatsCivil==old("etat_civil") ? "selected":""}}>{{ $etatsCivil}}</option>
                                                        @empty

                                                        @endforelse

                                                    </select>

                                                    @error('etat_civil')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row nc">
                                                <label class="col-md-3 label-control" for="nom_conjoint">Nom du conjoint *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" class="form-control" id="nom_conjoint" name="nom_conjoint" placeholder="Nom du conjoit">
                                                    @error('nom_conjoint')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nationalites_id">Nationalité *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select class="select2-theme form-control" id="nationalites_id" name="nationalites_id">
                                                                <option value="">Selectionner</option>
                                                        @forelse ($pays as $p)
                                                                <option value="{{$p->id}}" {{$p->id==old("nationalites_id") ? "selected":""}}>{{ $p->lib_pays }}</option>
                                                        @empty

                                                        @endforelse

                                                    </select>

                                                    @error('nationalites_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <h4 class="form-section"><i class="ft-home"></i> Information de contact</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="departements_id">Département *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select class="select2-theme form-control" id="departements_id" name="departements_id">
                                                                <option value="">Selectionner</option>
                                                        @forelse ($departements as $d)
                                                                <option value="{{$d->id}}">{{ $d->lib_departement }}</option>
                                                        @empty

                                                        @endforelse

                                                    </select>
                                                    @error('departements_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="arrondissements_id">Arrondissement / Commune *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select  id="arrondissements_id" class="form-control @error('arrondissements_id') is-invalid @enderror"  name="arrondissements_id" required>

                                                    </select>
                                                    @error('arrondissements_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="quartiers_id">Quartier / Village *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select  id="quartiers_id" class="form-control @error('quartiers_id') is-invalid @enderror"  name="quartiers_id" required>
                                                            {{-- <option value="{{old("quartiers_id")}}"></option> --}}
                                                    </select>
                                                    @error('quartiers_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="avenue_rue">Avenue / Rue *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="avenue_rue" class="form-control @error('avenue_rue') is-invalid @enderror"  value="{{old('avenue_rue')}}" name="avenue_rue" placeholder="Avenue / rue" required>
                                                    @error('avenue_rue')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="numero_adresse">Numéro domicile *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="numero_adresse" class="form-control @error('numero_adresse') is-invalid @enderror"  value="{{old('numero_adresse')}}" name="numero_adresse" placeholder="Numéro" required>
                                                    @error('numero_adresse')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="telephone">Numéro de téléphone *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="telephone" class="form-control @error('telephone') is-invalid @enderror"  value="{{old('telephone')}}" name="telephone" placeholder="Numéro de téléphone" required>
                                                    @error('telephone')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="email">Email *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="email" class="form-control @error('email') is-invalid @enderror"  value="{{old('email')}}" name="email" placeholder="Email" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="photo">Photo *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="file" id="photo" class="form-control @error('photo') is-invalid @enderror"  name="photo" accept="image/*" required>
                                                    @error('photo')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- Information des parents --}}
                                            <h4 class="form-section"><i class="ft-users"></i> Information des parents</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nom_pere">Nom père *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="nom_pere" class="form-control @error('nom_pere') is-invalid @enderror"  value="{{old('nom_pere')}}" name="nom_pere" placeholder="Nom du père" required>
                                                    @error('nom_pere')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="prenom_pere">Prénom père *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="prenom_pere" class="form-control @error('prenom_pere') is-invalid @enderror"  value="{{old('prenom_pere')}}" name="prenom_pere" placeholder="Prénom du père" required>
                                                    @error('prenom_pere')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nom_mere">Nom de la mère *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="nom_mere" class="form-control @error('nom_mere') is-invalid @enderror"  value="{{old('nom_mere')}}" name="nom_mere" placeholder="Nom de la mère" required>
                                                    @error('nom_mere')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="prenom_mere">Prénom mère *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="prenom_mere" class="form-control @error('prenom_mere') is-invalid @enderror"  value="{{old('prenom_mere')}}" name="prenom_mere" placeholder="Prénom du mère" required>
                                                    @error('prenom_mere')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- Information de l'employeur --}}
                                            <h4 class="form-section"><i class="ft-users"></i> Information de la profession</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="profession">Profession *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="profession" class="form-control @error('profession') is-invalid @enderror"  value="{{old('profession')}}" name="profession" placeholder="Votre profession" required>
                                                    @error('profession')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="employeur">Nom de l'Employeur </label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="employeur" class="form-control @error('employeur') is-invalid @enderror"  value="{{old('employeur')}}" name="employeur" placeholder="Nom de l'employeur" required>
                                                    @error('employeur')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="adresse_employeur">Adresse de l'Employeur </label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="adresse_employeur" class="form-control @error('adresse_employeur') is-invalid @enderror"  value="{{old('adresse_employeur')}}" name="adresse_employeur" placeholder="Adresse de l'employeur" required>
                                                    @error('adresse_employeur')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <h4 class="form-section"><i class="ft-file"></i> Information du passeport </h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="numero_passeport">Numéro du passeport *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="numero_passeport" class="form-control @error('numero_passeport') is-invalid @enderror"  value="{{old('numero_passeport')}}" name="numero_passeport" placeholder="Numéro du passeport" required>
                                                    @error('numero_passeport')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_emission_passeport">Date d'émission *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_emission_passeport" class="form-control @error('date_emission_passeport') is-invalid @enderror"  value="{{old('date_emission_passeport')}}" name="date_emission_passeport" placeholder="Date émission du passeport" required>
                                                    @error('date_emission_passeport')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_expiration_passeport">Date d'expiration*</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_expiration_passeport" class="form-control @error('date_expiration_passeport') is-invalid @enderror"  value="{{old('date_expiration_passeport')}}" name="date_expiration_passeport" placeholder="Date d'expiration du passeport" required>
                                                    @error('date_expiration_passeport')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="passeport_delivre_par">Délivré par*</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="passeport_delivre_par" class="form-control @error('passeport_delivre_par') is-invalid @enderror"  value="{{old('passeport_delivre_par')}}" name="passeport_delivre_par" placeholder="Délivré par" required>
                                                    @error('passeport_delivre_par')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Carte Consulaire --}}
                                            <h4 class="form-section"><i class="ft-file"></i> Information de la carte Consulaire</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="numero_carte_consulaire">Numéro de la carte *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="numero_carte_consulaire" class="form-control @error('numero_carte_consulaire') is-invalid @enderror"  value="{{old('numero_carte_consulaire')}}" name="numero_carte_consulaire" placeholder="Numéro de la carte consulaire" required>
                                                    @error('numero_carte_consulaire')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_emission_carte_consulaire">Date d'émission *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_emission_carte_consulaire" class="form-control @error('date_emission_carte_consulaire') is-invalid @enderror"  value="{{old('date_emission_carte_consulaire')}}" name="date_emission_carte_consulaire" placeholder="Date émission de la carte" required>
                                                    @error('date_emission_carte_consulaire')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_expiration_carte_consulaire">Date d'expiration*</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_expiration_carte_consulaire" class="form-control @error('date_expiration_carte_consulaire') is-invalid @enderror"  value="{{old('date_expiration_carte_consulaire')}}" name="date_expiration_carte_consulaire" placeholder="Date d'expiration du passeport" required>
                                                    @error('date_expiration_carte_consulaire')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="carte_delivre_par">Délivré par*</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="carte_delivre_par" class="form-control @error('carte_delivre_par') is-invalid @enderror"  value="{{old('carte_delivre_par')}}" name="carte_delivre_par" placeholder="Délivré par" required>
                                                    @error('carte_delivre_par')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Section demande --}}
                                            <h4 class="form-section"><i class="ft-file"></i> Information sur la demande</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="type_demande">Type demande *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="type_demande" id="type_demande" class="form-control @error('type_demande') is-invalid @enderror">
                                                        <option value="">Selectionner</option>
                                                        <option value="Carte de résident temporaire" {{"Carte de résident temporaire"==old("type_demande") ? "selected":""}}>Carte de résident temporaire</option>
                                                        <option value="Visa" {{"Visa"==old("type_demande") ? "selected":""}}>Visa</option>
                                                    </select>
                                                    @error('type_demande')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="validite">Validité *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="validite" id="validite" class="form-control @error('validite') is-invalid @enderror">
                                                        <option value="">Selectionner</option>
                                                        @forelse ($validites as $validite)
                                                            <option value="{{$validite}}" {{ $validite==old("validite") ? "selected":"" }}>{{$validite}}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>
                                                    @error('validite')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_demande">Date de la demande *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" name="date_demande" id="date_demande" class="form-control @error('date_demande') is-invalid @enderror" value="{{old("date_demande")}}">

                                                    @error('date_demande')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <h4 class="form-section"><i class="ft-file"></i> Opération de finalisation *</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="type_demande"></label>
                                                <div class="col-md-9 mx-auto">
                                                    <label for="preview"><input type="radio" name="operation" id="preview" value="preview" checked>Apercu avant validation</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label for="validate"><input type="radio" name="operation" id="validate" value="validate">Validation des données</label>
                                                </div>
                                            </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Sauvegarder
                                            </button>
                                            <a href="{{route('demandes.index')}}" class="btn btn-warning">Retour</a>
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
<script src="{{asset('res/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('res/app-assets/js/scripts/forms/select/form-select2.min.js')}}"></script>
<script>
    $(function(){

        $("#departements_id").on("change",function(){
            var id = $(this).val();
            if(id != ""){
                arrondissements(id,"#arrondissements_id");
            }
            return false;
        });

        $("#arrondissements_id").on("change",function(){
            var id = $(this).val();
            if(id != ""){
                quartiers(id,"#quartiers_id");
            }
            return false;
        });

        $(".nc").hide();

        $("#etat_civil").on("change",function(){
            var me = $(this).val();
            var sexe = $("#sexe").val();
            if(me =="Marié(e)" && sexe =="Féminin"){
                $(".nc").fadeIn(500);
            }else{
                $(".nc").fadeOut(500);
                $("#nom_conjoint").val("");
            }
        });
    });

    function arrondissements(id,div){
        var route = "{{route("departements.arrondissements",'id')}}";
        var out = "<option value=''>Selectionner</option>"
        route = route.replace("id",id);
        $.get(route,function(data){
            if(data.length > 0){
                for(var i=0; i < data.length; i++){
                    out += "<option value="+data[i].id+">"+data[i].lib_arrondissement+"</option>";
                }
                $(div).empty().append(out);
            }
        });
    }

    function quartiers(id,div){
        var route = "{{route("arrondissements.quartiers",'id')}}";
        var out = "<option value=''>Selectionner</option>"
        route = route.replace("id",id);
        $.get(route,function(data){
            if(data.length > 0){
                for(var i=0; i < data.length; i++){
                    out += "<option value="+data[i].id+">"+data[i].lib_quartier+"</option>";
                }
                $(div).empty().append(out);
            }
        });
    }
</script>
@endsection
