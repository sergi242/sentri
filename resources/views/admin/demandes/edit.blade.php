@extends('admin.layouts.app')
@section('title')
    Modifier une demande
<div class="modal-body" id="modal-passeport-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="la la-times"></i> Fermer et corriger
                </button>
                <a href="#" id="btn-voir-demande" class="btn btn-primary" target="_blank">
                    <i class="la la-eye"></i> Voir la demande existante
                </a>
                <a href="#" id="btn-renouveler" class="btn btn-success">
                    <i class="la la-refresh"></i> Renouveler ce titre
                </a>
            </div>
        </div>
    </div>
</div>

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
                                    <form class="form form-horizontal" method="POST" action="{{route('demandes.update',$demande->id)}}" enctype="multipart/form-data">
                                        @csrf
                                        @method("PUT")
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Information de l'Impétrant</h4>
                                            <!-- LECTEUR PASSEPORT -->
                                            <div class="form-group row" id="passport-reader-section">
                                                <label class="col-md-3 label-control"></label>
                                                <div class="col-md-9">
                                                    <button type="button" id="btn-lire-passeport" class="btn btn-primary">
                                                        <i class="la la-id-card"></i> &nbsp; Lire le passeport
                                                    </button>
                                                    &nbsp;
                                                    <button type="button" id="btn-restart-lecteur" class="btn btn-warning btn-sm" title="Réinitialiser le lecteur">
                                                        <i class="la la-refresh"></i> Réinitialiser
                                                    </button>
                                                    &nbsp; <span id="passport-status"></span>
                                                    <div id="passport-photo-preview" style="display:none;margin-top:8px;">
                                                        <img id="passport-photo-img" src="" style="height:150px;border-radius:6px;border:3px solid #28D094;">
                                                        <br><small class="text-success"><i class="la la-check-circle"></i> <strong>Photo biométrique depuis la puce</strong></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- FIN LECTEUR PASSEPORT -->

                                             <input type="hidden" name="force_quittance" id="force_quittance" value="0">
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nom"></label>
                                                <div class="col-md-9 mx-auto">
                                                    <img src="{{asset("app/$demande->photo")}}" alt="" width="150" height="150" class="img-fluid">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nom">Nom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" placeholder="Nom" value="{{$demande->impetrant?->nom}}" name="nom" required>
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
                                                    <input type="text" id="prenom" class="form-control @error('prenom') is-invalid @enderror" placeholder="Prénom" value="{{$demande->impetrant?->prenom}}" name="prenom" required>
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
                                                        <option value="Masculin" {{"Masculin"==$demande->impetrant?->sexe ? "selected":""}}>Masculin</option>
                                                        <option value="Féminin" {{"Féminin"==$demande->impetrant?->sexe ? "selected":""}}>Féminin</option>
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
                                                    <input type="date" id="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror"  value="{{$demande->impetrant?->date_naissance}}" name="date_naissance" required>
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
                                                    <input type="text" id="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror"  value="{{$demande->impetrant?->lieu_naissance}}" name="lieu_naissance" placeholder="Lieu de naissance" required>
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
                                                    <select class="select2-theme form-control" id="select2-theme" name="etat_civil">

                                                        @forelse ($etatsCivils as $etatsCivil)
                                                                <option value="{{$etatsCivil}}" {{$etatsCivil==$demande->etat_civil ? "selected":""}}>{{ $etatsCivil}}</option>
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
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nationalites_id">Nationalité *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select class="select2-theme form-control" id="select2-theme" name="nationalites_id">
                                                                <option value="">Selectionner</option>
                                                        @forelse ($pays as $p)
                                                                <option value="{{$p->id}}" {{$p->id==$demande->impetrant?->nationalites_id ? "selected":""}}>{{ $p->lib_pays }}</option>
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
                                                                <option value="{{$d->id}}" {{ $d->id==$demande->quartier?->arrondissement?->departement?->id ? "selected":"" }}>{{ $d->lib_departement }}</option>
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
                                                        <option value="{{$demande->quartier?->arrondissement?->id}}">{{ $demande->quartier?->arrondissement?->lib_arrondissement }}</option>
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
                                                        <option value="{{$demande->quartier?->id}}">{{ $demande->quartier?->lib_quartier }}</option>
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
                                                    <input type="text" id="avenue_rue" class="form-control @error('avenue_rue') is-invalid @enderror"  value="{{$demande->avenue_rue}}" name="avenue_rue" placeholder="Avenue / rue" required>
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
                                                    <input type="text" id="numero_adresse" class="form-control @error('numero_adresse') is-invalid @enderror"  value="{{$demande->numero_adresse}}" name="numero_adresse" placeholder="Numéro" required>
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
                                                    <input type="text" id="telephone" class="form-control @error('telephone') is-invalid @enderror"  value="{{$demande->telephone}}" name="telephone" placeholder="Numéro de téléphone" required>
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
                                                    <input type="text" id="email" class="form-control @error('email') is-invalid @enderror"  value="{{$demande->email}}" name="email" placeholder="Email" required>
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
                                                    <input type="text" id="nom_pere" class="form-control @error('nom_pere') is-invalid @enderror"  value="{{$demande->impetrant?->nom_pere}}" name="nom_pere" placeholder="Nom du père" required>
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
                                                    <input type="text" id="prenom_pere" class="form-control @error('prenom_pere') is-invalid @enderror"  value="{{$demande->impetrant?->prenom_pere}}" name="prenom_pere" placeholder="Prénom du père" required>
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
                                                    <input type="text" id="nom_mere" class="form-control @error('nom_mere') is-invalid @enderror"  value="{{$demande->impetrant?->nom_mere}}" name="nom_mere" placeholder="Nom de la mère" required>
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
                                                    <input type="text" id="prenom_mere" class="form-control @error('prenom_mere') is-invalid @enderror"  value="{{$demande->impetrant?->prenom_mere}}" name="prenom_mere" placeholder="Prénom du mère" required>
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
                                                    <input type="text" id="profession" class="form-control @error('profession') is-invalid @enderror"  value="{{$demande->profession}}" name="profession" placeholder="Votre profession" required>
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
                                                    <input type="text" id="employeur" class="form-control @error('employeur') is-invalid @enderror"  value="{{$demande->employeur}}" name="employeur" placeholder="Nom de l'employeur" required>
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
                                                    <input type="text" id="adresse_employeur" class="form-control @error('adresse_employeur') is-invalid @enderror"  value="{{$demande->adresse_employeur}}" name="adresse_employeur" placeholder="Adresse de l'employeur" required>
                                                    @error('adresse_employeur')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            @include('admin.demandes.partials._bloc_passeport', ['colLabel'=>'col-md-3','colField'=>'col-md-9'])
{{-- Carte Consulaire --}}
                                            <h4 class="form-section"><i class="ft-file"></i> Information de la carte Consulaire</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="numero_carte_consulaire">Numéro de la carte *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="numero_carte_consulaire" class="form-control @error('numero_carte_consulaire') is-invalid @enderror"  value="{{$demande->carteconsulaire()?->numero_document}}" name="numero_carte_consulaire" placeholder="Numéro de la carte consulaire" required>
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
                                                    <input type="date" id="date_emission_carte_consulaire" class="form-control @error('date_emission_carte_consulaire') is-invalid @enderror"  value="{{$demande->carteconsulaire()?->date_emission}}" name="date_emission_carte_consulaire" placeholder="Date émission de la carte" required>
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
                                                    <input type="date" id="date_expiration_carte_consulaire" class="form-control @error('date_expiration_carte_consulaire') is-invalid @enderror"  value="{{$demande->carteconsulaire()?->date_expiration}}" name="date_expiration_carte_consulaire" placeholder="Date d'expiration du passeport" required>
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
                                                    <input type="text" id="carte_delivre_par" class="form-control @error('carte_delivre_par') is-invalid @enderror"  value="{{$demande->carteconsulaire()?->emis_par}}" name="carte_delivre_par" placeholder="Délivré par" required>
                                                    @error('carte_delivre_par')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Section demande --}}
                                            <h4 class="form-section"><i class="ft-file"></i> Information sur la demande</h4>
                                                    @include('admin.demandes.partials._bloc_quittance', ['colLabel'=>'col-md-3','colField'=>'col-md-9'])

                    </div>
                </div>
            </section>
            <!-- // Basic form layout section end -->
        </div>
    </div>
</div>
<!-- END: Content-->
<div class="modal fade" id="quittanceModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-warning">
    <h5 class="modal-title">⚠️ Quittance déjà utilisée</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


      <div class="modal-body">
            <div class="row">
                 <div class="col-md-4 text-center">
            <img id="modalPhoto" class="img-fluid rounded border" />
                 </div>
                  <div class="col-md-8">
                     <table class="table table-sm">
                       <tr><th>Nom</th><td id="modalNom"></td></tr>
                      <tr><th>Prénom</th><td id="modalPrenom"></td></tr>
                      <tr><th>Date naissance</th><td id="modalNaissance"></td></tr>
                     <tr><th>Nationalité</th><td id="modalNationalite"></td></tr>
                     <tr><th>N° quittance</th><td id="modalQuittance"></td></tr>
                     <tr><th>Date</th><td id="modalDate"></td></tr>
                    </table>
                    </div>
                 </div>
     </div>

         <div class="modal-footer">
     <button type="button" class="btn btn-secondary" data-dismiss="modal">
        Annuler
        </button>
     <button type="button" class="btn btn-success" id="confirmQuittance">
        Confirmer
        </button>
        </div>


    </div>
  </div>
</div>

<!-- Modal Passeport Existant -->
<div class="modal fade" id="modal-passeport-existant" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#FF4961;color:white;">
                <h5 class="modal-title">
                    <i class="la la-exclamation-triangle"></i>
                    Passeport déjà enregistré dans le système
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color:white;">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-passeport-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="la la-times"></i> Fermer et corriger
                </button>
                <a href="#" id="btn-voir-demande" class="btn btn-primary" target="_blank">
                    <i class="la la-eye"></i> Voir la demande existante
                </a>
                <a href="#" id="btn-renouveler" class="btn btn-success">
                    <i class="la la-refresh"></i> Renouveler ce titre
                </a>
            </div>
        </div>
    </div>
</div>

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
<script>
function clearForm() {
  document.getElementById('formDemande').reset();

  // Pour les champs non standards (Select2, etc.)
  document.querySelectorAll('#formDemande input, #formDemande select, #formDemande textarea')
    .forEach(el => {
      el.value = '';
      el.checked = false;
      el.selectedIndex = 0;
    });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    let quittanceDetectee = false;
    let quittanceConfirmee = false;

    const input = document.getElementById('numero_quittance');
    const form = document.getElementById('formDemande');
    const btnConfirm = document.getElementById('confirmQuittance');
    const forceInput = document.getElementById('force_quittance');

    const msgValid = document.getElementById('quittanceValidMsg');
    const msgLocked = document.getElementById('quittanceLockedMsg');

    function resetMessages() {
        msgValid.classList.add('d-none');
        msgLocked.classList.add('d-none');
    }

    // 🔍 Vérification quittance
    input.addEventListener('blur', function () {

        if (!this.value) return;

        resetMessages();
        quittanceDetectee = false;
        quittanceConfirmee = false;
        forceInput.value = 0;

        fetch("{{ route('demandes.checkQuittance') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                numero_quittance: this.value
            })
        })
        .then(res => res.json())
        .then(data => {

            // ✅ CAS 1 : quittance inexistante → OK
            if (!data.warning) {
                msgValid.classList.remove('d-none');
                return;
            }

            // ❌ CAS 2 : quittance déjà utilisée
            quittanceDetectee = true;

            document.getElementById('modalNom').innerText = data.demande.nom;
            document.getElementById('modalPrenom').innerText = data.demande.prenom;
            document.getElementById('modalNaissance').innerText = data.demande.date_naissance;
            document.getElementById('modalNationalite').innerText = data.demande.nationalite;
            document.getElementById('modalQuittance').innerText = data.demande.numero_quittance;
            document.getElementById('modalDate').innerText = data.demande.date;
            document.getElementById('modalPhoto').src = data.demande.photo;

            $('#quittanceModal').modal('show');
        });
    });

    // ✅ Confirmation popup
    btnConfirm.addEventListener('click', function () {

        quittanceConfirmee = true;
        forceInput.value = 1;

        input.setAttribute('readonly', true);
        input.classList.add('bg-light');

        msgLocked.classList.remove('d-none');

        $('#quittanceModal').modal('hide');
    });

    // 🚫 Protection soumission
    form.addEventListener('submit', function (e) {
        if (quittanceDetectee && !quittanceConfirmee) {
            e.preventDefault();
            toastr.warning("Veuillez confirmer la quittance avant l’enregistrement.");
        }
    });

});

</script>

<script>
// ===== LECTEUR PASSEPORT DMCE =====
$(document).ready(function() {
    var READER_URL = 'http://127.0.0.1:8085';

    // Vérifier statut au chargement
    $.ajax({
        url: READER_URL + '/status',
        method: 'GET',
        timeout: 2000,
        success: function() {
            $('#btn-lire-passeport').prop('disabled', false);
            setStatus('success', '<i class="la la-check-circle"></i> Lecteur connecté');
        },
        error: function() {
            $('#btn-lire-passeport').prop('disabled', false);
            setStatus('warning', '<i class="la la-exclamation-triangle"></i> Service lecteur non démarré');
        }
    });

    // Clic sur le bouton
    $('#btn-lire-passeport').on('click', function() {
        $('#btn-lire-passeport').prop('disabled', true);
        setStatus('info', '<i class="la la-spinner la-spin"></i> Lecture en cours... Posez le passeport sur le lecteur');
        $('#passport-photo-preview').hide();

        $.ajax({
            url: READER_URL + '/read',
            method: 'GET',
            timeout: 120000,
            success: function(data) {
                if (data.status === 'success') {
                    remplirFormulaire(data);
                } else if (data.status === 'timeout2') {
                    setStatus('warning', '<i class="la la-exclamation-triangle"></i> ' + data.message);
                    $('#btn-lire-passeport').prop('disabled', false);
                } else if (data.status === 'timeout') {
                    setStatus('warning', '<i class="la la-clock-o"></i> ' + data.message);
                    $('#btn-lire-passeport').prop('disabled', false);
                } else {
                    setStatus('danger', '<i class="la la-times-circle"></i> Erreur : ' + (data.message || 'Inconnue'));
                    $('#btn-lire-passeport').prop('disabled', false);
                }
            },
            error: function() {
                setStatus('danger', '<i class="la la-times-circle"></i> Service non disponible. Vérifiez que le programme Java tourne.');
                $('#btn-lire-passeport').prop('disabled', false);
            }
        });
    });

    function remplirFormulaire(data) {
        // Identité
        if (data.nom)      $('#nom').val(data.nom);
        if (data.prenoms)  $('#prenom').val(data.prenoms);
        if (data.sexe) {
            var sexe = data.sexe === 'M' ? 'Masculin' : 'Féminin';
            $('#sexe').val(sexe);
        }
        if (data.naissance)     $('#date_naissance').val(data.naissance);
        if (data.lieu_naissance && data.lieu_naissance !== '')
            $('#lieu_naissance').val(data.lieu_naissance);
        if (data.profession && data.profession !== '')
            $('#profession').val(data.profession);
        if (data.telephone && data.telephone !== '')
            $('#telephone').val(data.telephone);

        // Passeport
        if (data.num_doc)    $('#numero_passeport').val(data.num_doc);
        // ── Champs cachés pour sauvegarde impetrant_documents ─────────────
        if (data.mrz)       $('#h_mrz').val(data.mrz);
        $('#h_source_doc').val(data.num_doc ? 'lecteur' : 'manuel');
        // Déclencher la vérification doublon document existant
        if (data.num_doc)   $('#numero_passeport').trigger('input');
        // ──────────────────────────────────────────────────────────────────
        if (data.expiration) $('#date_expiration_passeport').val(data.expiration);
        if (data.lieu_emission && data.lieu_emission !== '')
            $('#passeport_delivre_par').val(data.lieu_emission);

        // Date émission depuis Java (déjà calculée)
        if (data.date_emission && data.date_emission !== '') {
            $('#date_emission_passeport').val(data.date_emission);
        }

        // Nationalité via API Laravel (code_iso 3 lettres)
        if (data.nationalite) {
            $.get('/api/passport/pays', function(pays) {
                var code = data.nationalite.toUpperCase();
                if (pays[code]) {
                    $('#nationalites_id').val(pays[code].id);
                    if ($.fn.select2) $('#nationalites_id').trigger('change.select2');
                    else $('#nationalites_id').trigger('change');
                }
            });
        }

        // Photo biométrique
        if (data.photo_base64 && data.photo_base64.length > 100) {
            var imgSrc = 'data:image/jpeg;base64,' + data.photo_base64;
            $('#passport-photo-img').attr('src', imgSrc);
            $('#passport-photo-preview').show();
            try {
                var byteString = atob(data.photo_base64);
                var ab = new ArrayBuffer(byteString.length);
                var ia = new Uint8Array(ab);
                for (var i = 0; i < byteString.length; i++) ia[i] = byteString.charCodeAt(i);
                var blob = new Blob([ab], {type:'image/jpeg'});
                var file = new File([blob], 'passport_photo.jpg', {type:'image/jpeg'});
                var dt = new DataTransfer();
                dt.items.add(file);
                document.getElementById('photo').files = dt.files;
            } catch(e) { console.log('Photo: ' + e); }

            setStatus('success',
                '<i class="la la-check-circle"></i> <strong>Passeport lu !</strong> ' +
                '<small class="text-muted">Source: ' + data.source_photo + '</small>');
        } else {
            setStatus('success', '<i class="la la-check-circle"></i> Données lues.');
        }

        $('#btn-lire-passeport').prop('disabled', false);
        if (typeof toastr !== 'undefined')
            toastr.success('Formulaire rempli automatiquement !', 'Lecteur passeport');
    }


    // Bouton réinitialiser lecteur
    $('#btn-restart-lecteur').on('click', function() {
        $('#btn-restart-lecteur').prop('disabled', true);
        setStatus('info', '<i class="la la-refresh la-spin"></i> Reinitialisation...');
        $.ajax({
            url: READER_URL + '/restart',
            method: 'GET',
            timeout: 10000,
            success: function() {
                setTimeout(function() {
                    setStatus('success', '<i class="la la-check-circle"></i> Lecteur reinitialise !');
                    $('#btn-restart-lecteur').prop('disabled', false);
                    $('#btn-lire-passeport').prop('disabled', false);
                }, 3000);
            },
            error: function() {
                setStatus('danger', '<i class="la la-times-circle"></i> Erreur reinitialisation');
                $('#btn-restart-lecteur').prop('disabled', false);
            }
        });
    });

    function setStatus(type, html) {
        var colors = {'success':'#28D094','warning':'#FF9149','danger':'#FF4961','info':'#1E9FF2'};
        $('#passport-status').html('<span style="color:' + (colors[type]||'#333') + '">' + html + '</span>');
    }
});
// ===== FIN LECTEUR PASSEPORT =====
</script>


<script>
// ===== VERIFICATION PASSEPORT EXISTANT =====
$(document).ready(function() {

    var checkTimer = null;

    $('#numero_passeport').on('input blur', function() {
        var numero = $(this).val().trim();
        if (numero.length < 5) return;

        clearTimeout(checkTimer);
        checkTimer = setTimeout(function() {
            checkPasseportExistant(numero);
        }, 600);
    });

    function checkPasseportExistant(numero) {
        $.get('/api/passport/check/' + encodeURIComponent(numero), function(data) {
            if (data.found) {
                afficherModalPasseport(data.demande);
            }
        });
    }

    function afficherModalPasseport(d) {
        var statut_color = {
            "En attente d'approbation": '#FF9149',
            "Approuvée": '#28D094',
            "Rejetée": '#FF4961',
            "Livrée": '#1E9FF2',
            "Envoyée au contentieux": '#FF4961'
        }[d.statut_demande] || '#666';

        var photo = d.photo ?
            '<img src="/app/' + d.photo + '" style="height:100px;border-radius:6px;border:2px solid #ddd;">' :
            '<div style="height:100px;width:80px;background:#eee;display:flex;align-items:center;justify-content:center;border-radius:6px;"><i class="la la-user" style="font-size:2em;color:#999;"></i></div>';

        var html = '<div class="row">' +
            '<div class="col-md-2 text-center">' + photo + '</div>' +
            '<div class="col-md-10">' +
            '<table class="table table-bordered table-sm">' +
            '<tr><th width="35%">Impétrant</th><td><strong>' + (d.nom||'') + ' ' + (d.prenom||'') + '</strong></td></tr>' +
            '<tr><th>Date naissance</th><td>' + (d.date_naissance||'-') + '</td></tr>' +
            '<tr><th>Nationalité</th><td>' + (d.nationalite||'-') + '</td></tr>' +
            '<tr><th>N° passeport</th><td><strong>' + (d.numero_document||'') + '</strong></td></tr>' +
            '<tr><th>Type document</th><td>' + (d.type_document||'-') + '</td></tr>' +
            '<tr><th>Émission</th><td>' + (d.date_emission||'-') + '</td></tr>' +
            '<tr><th>Expiration</th><td>' + (d.date_expiration||'-') + '</td></tr>' +
            '<tr><th>Type demande</th><td>' + (d.type_demande||'-') + '</td></tr>' +
            '<tr><th>Date demande</th><td>' + (d.date_demande||'-') + '</td></tr>' +
            '<tr><th>Statut</th><td><span style="color:' + statut_color + ';font-weight:bold;">' + (d.statut_demande||'-') + '</span></td></tr>' +
            '<tr><th>N° UUID</th><td><code>' + (d.uuid||'-') + '</code></td></tr>' +
            '</table>' +
            '</div></div>' +
            '<div class="alert alert-warning mt-1 mb-0">' +
            '<i class="la la-exclamation-triangle"></i> ' +
            'Ce numéro de passeport est déjà associé à une demande. ' +
            'Voulez-vous renouveler ce titre ou corriger le numéro saisi ?' +
            '</div>';

        $('#modal-passeport-body').html(html);
        $('#btn-voir-demande').attr('href', '/demandes/' + d.demande_id);
        $('#btn-renouveler').attr('href', '/demandes/' + d.demande_id + '/renouvellement');
        $('#modal-passeport-existant').modal('show');
    }
});
// ===== FIN VERIFICATION PASSEPORT =====
</script>

@endsection
