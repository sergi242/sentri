@extends('admin.layouts.app')
@section('title') Nouvelle demande Visa @endsection
@section('styles')
<link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('img/editorial.css')}}" type="text/css">
<style>
.bg-visa { background-color: #E9ECF2; }
label { font-weight: normal; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card animated fadeIn">
                            <div class="card-content collpase show">
                                <div class="card-body">

                                    {{-- En-tête VISA --}}
                                    <div class="p-4 bg-visa mb-3" style="color:#03658C; border-radius:12px;">
                                        <div class="text-center">
                                            <h1 class="font-weight-bold mb-3" style="font-size:4em; letter-spacing:.1em; color:#51818C;">VISA</h1>
                                            <div style="font-size:1.1em; line-height:1.5;">
                                                MINISTÈRE DE L'INTERIEUR ET DE LA DÉCENTRALISATION —
                                                CENTRALE D'INTELLIGENCE ET DE DOCUMENTATION —
                                                DÉPARTEMENT DES MIGRATIONS ET DU CONTRÔLE DES ÉTRANGERS
                                            </div>
                                        </div>
                                    </div>

                                    <form id="formDemande" class="form form-horizontal"
                                          method="POST" action="{{ route('demandes.store') }}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="force_quittance" id="force_quittance" value="0">

                                        <div class="row">

                                            {{-- ══ COLONNE GAUCHE ══ --}}
                                            <div class="col-md-5 mt-2 mr-1 p-3 bg-visa" style="border:1px solid #000; border-radius:10px; margin-left:20px;">

                                                <h4 class="form-section"><i class="ft-file"></i> Pièces jointes</h4>
                                                <div class="row">
                                                    <div class="col">
                                                        @forelse($pieces as $p)
                                                        <div class="form-group row">
                                                            <label for="{{ $p->id }}" class="col-md-6">{{ $p->piece }}</label>
                                                            <input type="checkbox" name="justificatifs[]" value="{{ $p->id }}" id="{{ $p->id }}" class="col-md-6" checked>
                                                        </div>
                                                        @empty
                                                        @endforelse
                                                    </div>
                                                </div>

                                                @include('admin.demandes.partials._bloc_passeport', ['colLabel'=>'col-md-4','colField'=>'col-md-8'])

                                                <h4 class="form-section"><i class="ft-file"></i> Information sur la demande</h4>

                                                @include('admin.demandes.partials._bloc_quittance', ['colLabel'=>'col-md-4','colField'=>'col-md-8'])

                                                <div class="form-group row">
                                                    <label class="col-md-4" for="uuid">Numéro fiche demande *</label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="text" name="uuid" id="uuid"
                                                               class="form-control @error('uuid') is-invalid @enderror"
                                                               value="{{ old('uuid') }}">
                                                        @error('uuid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-4" for="type_demande">Type demande *</label>
                                                    <div class="col-md-8 mx-auto">
                                                        <select name="type_demande" id="type_demande"
                                                                class="form-control @error('type_demande') is-invalid @enderror">
                                                            <option value="Visa" selected>Visa</option>
                                                        </select>
                                                        @error('type_demande')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-4" for="validite">Validité *</label>
                                                    <div class="col-md-8 mx-auto">
                                                        <select name="validite" id="validite"
                                                                class="form-control @error('validite') is-invalid @enderror">
                                                            @foreach($validites as $v)
                                                            <option value="{{ $v }}" {{ $v==old('validite') ? 'selected':'' }}>{{ $v }} (an)</option>
                                                            @endforeach
                                                        </select>
                                                        @error('validite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-4" for="date_demande">Date de la demande *</label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="date" name="date_demande" id="date_demande"
                                                               class="form-control @error('date_demande') is-invalid @enderror"
                                                               value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                                        @error('date_demande')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <h4 class="form-section"><i class="la la-user-tie"></i> Agent commanditaire</h4>
                                                <div class="form-group row">
                                                    <label class="col-md-4" for="commanditaire_id">Agent commanditaire</label>
                                                    <div class="col-md-8 mx-auto">
                                                        <select name="commanditaire_id" id="commanditaire_id"
                                                                class="select2-theme form-control @error('commanditaire_id') is-invalid @enderror">
                                                            <option value="">— Aucun agent désigné —</option>
                                                            @foreach($usersActifs as $user)
                                                            <option value="{{ $user->id }}" {{ old('commanditaire_id')==$user->id ? 'selected':'' }}>
                                                                {{ $user->nom }} {{ $user->prenom }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        @error('commanditaire_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <h4 class="form-section"><i class="ft-file"></i> Opération de finalisation *</h4>
                                                <div class="form-group row">
                                                    <label class="col-md-4" for="tag_demande">Libellé des données *</label>
                                                    <div class="col-md-8 mx-auto">
                                                        <select name="tag_demande" id="tag_demande"
                                                                class="form-control @error('tag_demande') is-invalid @enderror">
                                                            <option value="IMPRESSION">IMPRESSION</option>
                                                            <option value="REPRISE">REPRISE</option>
                                                        </select>
                                                        @error('tag_demande')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row attributed">
                                                    <label class="col-md-4" for="numero_document">Numéro attribué</label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="text" name="numero_document" id="numero_document"
                                                               class="form-control" value="{{ old('numero_document') }}"
                                                               placeholder="Numéro du document">
                                                    </div>
                                                </div>
                                                <div class="form-group row attributed">
                                                    <label class="col-md-4" for="date_attribution">Date d'émission</label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="date" name="date_attribution" id="date_attribution"
                                                               class="form-control" value="{{ old('date_attribution') }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <label><input type="radio" name="operation" value="preview"> &nbsp;Aperçu avant validation</label>
                                                        &nbsp;&nbsp;
                                                        <label><input type="radio" name="operation" value="validate" checked> &nbsp;Validation des données</label>
                                                    </div>
                                                </div>

                                            </div>{{-- fin col gauche --}}

                                            {{-- ══ COLONNE DROITE ══ --}}
                                            <div class="col-md-6 mt-2 p-3 bg-visa" style="border:1px solid #000; border-radius:10px;">
                                                <div class="form-body">

                                                    <h4 class="form-section"><i class="ft-user"></i> Information de l'Impétrant</h4>

                                                    {{-- Lecteur passeport --}}
                                                    <div class="form-group row" id="passport-reader-section">
                                                        <label class="col-md-4"></label>
                                                        <div class="col-md-8">
                                                            <button type="button" id="btn-lire-passeport" class="btn btn-primary">
                                                                <i class="la la-id-card"></i> Lire le passeport
                                                            </button>
                                                            &nbsp;
                                                            <button type="button" id="btn-restart-lecteur" class="btn btn-warning btn-sm">
                                                                <i class="la la-refresh"></i> Réinitialiser
                                                            </button>
                                                            &nbsp;<span id="passport-status"></span>
                                                            <div id="passport-photo-preview" style="display:none; margin-top:8px;">
                                                                <img id="passport-photo-img" src="" style="height:150px; border-radius:6px; border:3px solid #28D094;">
                                                                <br><small class="text-success"><i class="la la-check-circle"></i> <strong>Photo biométrique depuis la puce</strong></small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="nom">Nom *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror" placeholder="Nom" value="{{ old('nom') }}" required>
                                                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="prenom">Prénom *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="prenom" name="prenom" class="form-control @error('prenom') is-invalid @enderror" placeholder="Prénom" value="{{ old('prenom') }}" required>
                                                            @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="sexe">Sexe *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select id="sexe" name="sexe" class="form-control @error('sexe') is-invalid @enderror" required>
                                                                <option value="Masculin" {{ old('sexe')=='Masculin' ? 'selected':'' }}>Masculin</option>
                                                                <option value="Féminin"  {{ old('sexe')=='Féminin'  ? 'selected':'' }}>Féminin</option>
                                                            </select>
                                                            @error('sexe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="date_naissance">Date de naissance *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="date" id="date_naissance" name="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" value="{{ old('date_naissance') }}" required>
                                                            @error('date_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="lieu_naissance">Lieu de naissance *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="lieu_naissance" name="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror" value="{{ old('lieu_naissance') }}" placeholder="Lieu de naissance" required>
                                                            @error('lieu_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="etat_civil">État civil *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control" id="etat_civil" name="etat_civil">
                                                                @foreach($etatsCivils as $ec)
                                                                <option value="{{ $ec }}" {{ $ec==old('etat_civil') ? 'selected':'' }}>{{ $ec }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('etat_civil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row nc">
                                                        <label class="col-md-4" for="nom_conjoint">Nom du conjoint</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" class="form-control" id="nom_conjoint" name="nom_conjoint" placeholder="Nom du conjoint">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="nationalites_id">Nationalité 1 *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control" id="nationalites_id" name="nationalites_id">
                                                                <option value="">Sélectionner</option>
                                                                @foreach($pays as $p)
                                                                <option value="{{ $p->id }}" {{ $p->id==old('nationalites_id') ? 'selected':'' }}>{{ $p->lib_pays }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('nationalites_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4">Nationalité 2</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control" id="nationalite_2" name="nationalite_2">
                                                                <option value="">Sélectionner</option>
                                                                @foreach($pays as $p)
                                                                <option value="{{ $p->id }}" {{ $p->id==old('nationalite_2') ? 'selected':'' }}>{{ $p->lib_pays }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <h4 class="form-section"><i class="ft-home"></i> Information de contact</h4>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="departements_id">Département *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control" id="departements_id" name="departements_id">
                                                                <option value="">Sélectionner</option>
                                                                @foreach($departements as $d)
                                                                <option value="{{ $d->id }}">{{ $d->lib_departement }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('departements_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="arrondissements_id">Arrondissement *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select id="arrondissements_id" name="arrondissements_id" class="form-control @error('arrondissements_id') is-invalid @enderror" required></select>
                                                            @error('arrondissements_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="quartiers_id">Quartier *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select id="quartiers_id" name="quartiers_id" class="form-control @error('quartiers_id') is-invalid @enderror" required></select>
                                                            @error('quartiers_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="avenue_rue">Avenue / Rue *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="avenue_rue" name="avenue_rue" class="form-control @error('avenue_rue') is-invalid @enderror" value="{{ old('avenue_rue') }}" placeholder="Avenue / rue" required>
                                                            @error('avenue_rue')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="numero_adresse">N° domicile *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="numero_adresse" name="numero_adresse" class="form-control @error('numero_adresse') is-invalid @enderror" value="{{ old('numero_adresse') }}" placeholder="Numéro" required>
                                                            @error('numero_adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="telephone">Téléphone *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="telephone" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}" placeholder="+242..." required>
                                                            @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="email">Email</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email">
                                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="photo">Photo *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="file" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*" required>
                                                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>

                                                    <h4 class="form-section"><i class="ft-users"></i> Information des parents</h4>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="nom_pere">Nom père *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="nom_pere" name="nom_pere" class="form-control @error('nom_pere') is-invalid @enderror" value="{{ old('nom_pere') }}" placeholder="Nom du père" required>
                                                            @error('nom_pere')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="prenom_pere">Prénom père *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="prenom_pere" name="prenom_pere" class="form-control @error('prenom_pere') is-invalid @enderror" value="{{ old('prenom_pere') }}" placeholder="Prénom du père" required>
                                                            @error('prenom_pere')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="nom_mere">Nom mère *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="nom_mere" name="nom_mere" class="form-control @error('nom_mere') is-invalid @enderror" value="{{ old('nom_mere') }}" placeholder="Nom de la mère" required>
                                                            @error('nom_mere')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="prenom_mere">Prénom mère *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="prenom_mere" name="prenom_mere" class="form-control @error('prenom_mere') is-invalid @enderror" value="{{ old('prenom_mere') }}" placeholder="Prénom de la mère" required>
                                                            @error('prenom_mere')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>

                                                    <h4 class="form-section"><i class="ft-users"></i> Information de la profession</h4>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="categorie_socioprofessionnelle_id">Catégorie socio-prof. *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select id="categorie_socioprofessionnelle_id" name="categorie_socioprofessionnelle_id" class="form-control @error('categorie_socioprofessionnelle_id') is-invalid @enderror" required>
                                                                @foreach($categories as $item)
                                                                <option value="{{ $item->id }}">{{ $item->categorie }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('categorie_socioprofessionnelle_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="profession">Profession *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text" id="profession" name="profession" class="form-control @error('profession') is-invalid @enderror" value="{{ old('profession') }}" placeholder="Votre profession" required>
                                                            @error('profession')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-4" for="employeur_id">Employeur *</label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control" id="employeur_id" name="employeur_id">
                                                                <option value="">Sélectionner</option>
                                                                @foreach($employeurs as $emp)
                                                                <option value="{{ $emp->id }}" {{ $emp->id==old('employeur_id') ? 'selected':'' }}>{{ $emp->nom_employeur }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('employeur_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>{{-- fin col droite --}}

                                        </div>{{-- fin row --}}

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                                <i class="la la-check-square-o"></i> Sauvegarder
                                            </button>
                                            <a href="{{ route('demandes.index') }}" class="btn btn-warning">Retour</a>
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

{{-- Modal quittance --}}
<div class="modal fade" id="quittanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">⚠️ Quittance déjà utilisée</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center"><img id="modalPhoto" class="img-fluid rounded border"></div>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="confirmQuittance">Confirmer</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal passeport existant --}}
<div class="modal fade" id="modal-passeport-existant" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:#FF4961; color:white;">
                <h5 class="modal-title"><i class="la la-exclamation-triangle"></i> Passeport déjà enregistré dans le système</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="modal-passeport-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="la la-times"></i> Fermer et corriger</button>
                <a href="#" id="btn-voir-demande" class="btn btn-primary" target="_blank"><i class="la la-eye"></i> Voir la demande existante</a>
                <a href="#" id="btn-renouveler" class="btn btn-success"><i class="la la-refresh"></i> Renouveler ce titre</a>
            </div>
        </div>
    </div>
</div>

@include('admin.demandes._precheck_modal')

@endsection

@section('scripts')
<script src="{{ asset('res/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('res/app-assets/js/scripts/forms/select/form-select2.min.js') }}"></script>
<script>
$(function() {
    $('#departements_id').on('change', function() {
        var id = $(this).val(); if (!id) return;
        var r = "{{ route('departements.arrondissements','id') }}".replace('id',id);
        $.get(r, function(data) {
            var o = "<option value=''>Sélectionner</option>";
            data.forEach(function(a){ o += "<option value='"+a.id+"'>"+a.lib_arrondissement+"</option>"; });
            $('#arrondissements_id').html(o);
            $('#quartiers_id').html("<option value=''>Sélectionner</option>");
        });
    });
    $('#arrondissements_id').on('change', function() {
        var id = $(this).val(); if (!id) return;
        var r = "{{ route('arrondissements.quartiers','id') }}".replace('id',id);
        $.get(r, function(data) {
            var o = "<option value=''>Sélectionner</option>";
            data.forEach(function(q){ o += "<option value='"+q.id+"'>"+q.lib_quartier+"</option>"; });
            $('#quartiers_id').html(o);
        });
    });
    $('.nc').hide();
    $('#etat_civil').on('change', function() {
        if ($(this).val()==='Marié(e)' && $('#sexe').val()==='Féminin') { $('.nc').fadeIn(500); }
        else { $('.nc').fadeOut(500); $('#nom_conjoint').val(''); }
    });
    $('#nom').on('input', function() {
        $(this).val(function(_,v){ return v.toUpperCase(); });
        $('#nom_pere').val($(this).val());
    });
    $('.attributed').hide();
    $('#tag_demande').on('change', function() {
        $(this).val()==='REPRISE' ? $('.attributed').slideDown(500) : $('.attributed').slideUp(500);
    });
    $('#date_emission_passeport').on('change keyup', function() {
        var d = new Date($(this).val()); if (isNaN(d)) return;
        d.setFullYear(d.getFullYear()+5);
        var dd=String(d.getDate()-1).padStart(2,'0'), mm=String(d.getMonth()+1).padStart(2,'0');
        $('#date_expiration_passeport').val(d.getFullYear()+'-'+mm+'-'+dd);
    });
});

function togglePasseport(mode) {
    var bloc=document.getElementById('bloc_passeport'), sans=document.getElementById('bloc_sans_passeport'), hidden=document.getElementById('hidden_sans_passeport');
    var fields=['numero_passeport','date_emission_passeport','date_expiration_passeport'];
    if (mode==='sans') {
        bloc.style.display='none'; sans.style.display=''; hidden.value='1';
        fields.forEach(function(id){ var el=document.getElementById(id); if(el){el.removeAttribute('required');el.value='';} });
    } else {
        bloc.style.display=''; sans.style.display='none'; hidden.value='0';
        fields.forEach(function(id){ var el=document.getElementById(id); if(el) el.setAttribute('required','required'); });
    }
}

function toggleQuittance(mode) {
    var bloc=document.getElementById('bloc_quittance'), gratis=document.getElementById('bloc_gratis');
    var hidden=document.getElementById('hidden_sans_quittance'), input=document.getElementById('numero_quittance');
    var force=document.getElementById('force_quittance');
    if (mode==='sans') {
        if(bloc){bloc.style.opacity='0.5';bloc.style.pointerEvents='none';}
        if(gratis) gratis.style.display='';
        if(hidden) hidden.value='1';
        if(input){input.removeAttribute('required');input.value='GRATIS';}
        if(force) force.value='1';
        window.quittanceDetectee=false; window.quittanceConfirmee=true;
    } else {
        if(bloc){bloc.style.opacity='1';bloc.style.pointerEvents='auto';}
        if(gratis) gratis.style.display='none';
        if(hidden) hidden.value='0';
        if(input && input.value==='GRATIS') input.value='';
        if(force) force.value='0';
        window.quittanceDetectee=false; window.quittanceConfirmee=false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.quittanceDetectee=false; window.quittanceConfirmee=false;
    var input=document.getElementById('numero_quittance'), form=document.getElementById('formDemande');
    var btnConfirm=document.getElementById('confirmQuittance'), forceInput=document.getElementById('force_quittance');
    var msgValid=document.getElementById('quittanceValidMsg'), msgLocked=document.getElementById('quittanceLockedMsg');
    if (!input||!form) return;

    input.addEventListener('blur', function() {
        if (!this.value||this.value==='GRATIS') return;
        if (document.getElementById('hidden_sans_quittance')?.value==='1') return;
        window.quittanceDetectee=false; window.quittanceConfirmee=false; forceInput.value=0;
        if(msgValid) msgValid.classList.add('d-none');
        if(msgLocked) msgLocked.classList.add('d-none');
        fetch("{{ route('demandes.checkQuittance') }}", {
            method:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},
            body:JSON.stringify({numero_quittance:this.value})
        }).then(r=>r.json()).then(data=>{
            if (!data.warning) { if(msgValid) msgValid.classList.remove('d-none'); return; }
            window.quittanceDetectee=true;
            document.getElementById('modalNom').innerText=data.demande.nom??'-';
            document.getElementById('modalPrenom').innerText=data.demande.prenom??'-';
            document.getElementById('modalNaissance').innerText=data.demande.date_naissance??'-';
            document.getElementById('modalNationalite').innerText=data.demande.nationalite??'-';
            document.getElementById('modalQuittance').innerText=data.demande.numero_quittance??'-';
            document.getElementById('modalDate').innerText=data.demande.date??'-';
            document.getElementById('modalPhoto').src=data.demande.photo??'';
            $('#quittanceModal').modal('show');
        });
    });
    if(btnConfirm) btnConfirm.addEventListener('click', function() {
        window.quittanceConfirmee=true; forceInput.value=1;
        input.setAttribute('readonly',true); input.classList.add('bg-light');
        if(msgLocked) msgLocked.classList.remove('d-none');
        $('#quittanceModal').modal('hide');
    });
    form.addEventListener('submit', function(e) {
        if (window.quittanceDetectee&&!window.quittanceConfirmee) {
            e.preventDefault();
            if(typeof toastr!=='undefined') toastr.warning("Veuillez confirmer la quittance avant l'enregistrement.");
        }
    });
});

$(document).ready(function() {
    var READER_URL='http://127.0.0.1:8085';
    $.ajax({url:READER_URL+'/status',method:'GET',timeout:2000,
        success:function(){ setStatus('success','<i class="la la-check-circle"></i> Lecteur connecté'); },
        error:function(){ setStatus('warning','<i class="la la-exclamation-triangle"></i> Service lecteur non démarré'); }
    });
    $('#btn-lire-passeport').on('click', function() {
        $(this).prop('disabled',true);
        setStatus('info','<i class="la la-spinner la-spin"></i> Lecture en cours...');
        $('#passport-photo-preview').hide();
        $.ajax({url:READER_URL+'/read',method:'GET',timeout:120000,
            success:function(data){ if(data.status==='success'){remplirFormulaire(data);}else{setStatus('warning',data.message||'Erreur');$('#btn-lire-passeport').prop('disabled',false);} },
            error:function(){ setStatus('danger','Service non disponible'); $('#btn-lire-passeport').prop('disabled',false); }
        });
    });
    $('#btn-restart-lecteur').on('click', function() {
        $(this).prop('disabled',true);
        $.ajax({url:READER_URL+'/restart',method:'GET',timeout:10000,
            success:function(){ setTimeout(function(){ setStatus('success','Réinitialisé'); $('#btn-restart-lecteur,#btn-lire-passeport').prop('disabled',false); },3000); },
            error:function(){ setStatus('danger','Erreur réinitialisation'); $('#btn-restart-lecteur').prop('disabled',false); }
        });
    });
    function remplirFormulaire(data) {
        if(data.nom) $('#nom').val(data.nom);
        if(data.prenoms) $('#prenom').val(data.prenoms);
        if(data.sexe) $('#sexe').val(data.sexe==='M'?'Masculin':'Féminin');
        if(data.naissance) $('#date_naissance').val(data.naissance);
        if(data.lieu_naissance) $('#lieu_naissance').val(data.lieu_naissance);
        if(data.num_doc) $('#numero_passeport').val(data.num_doc);
        if(data.expiration) $('#date_expiration_passeport').val(data.expiration);
        if(data.date_emission) $('#date_emission_passeport').val(data.date_emission);
        if(data.nationalite) {
            $.get('/api/passport/pays',function(pays){
                var code=data.nationalite.toUpperCase();
                if(pays[code]){ $('#nationalites_id').val(pays[code].id).trigger('change.select2'); }
            });
        }
        if(data.photo_base64&&data.photo_base64.length>100) {
            $('#passport-photo-img').attr('src','data:image/jpeg;base64,'+data.photo_base64);
            $('#passport-photo-preview').show();
            try {
                var b=atob(data.photo_base64),ab=new ArrayBuffer(b.length),ia=new Uint8Array(ab);
                for(var i=0;i<b.length;i++) ia[i]=b.charCodeAt(i);
                var file=new File([new Blob([ab],{type:'image/jpeg'})],'passport_photo.jpg',{type:'image/jpeg'});
                var dt=new DataTransfer(); dt.items.add(file);
                document.getElementById('photo').files=dt.files;
            } catch(e){}
        }
        setStatus('success','<i class="la la-check-circle"></i> Passeport lu !');
        $('#btn-lire-passeport').prop('disabled',false);
        if(typeof toastr!=='undefined') toastr.success('Formulaire rempli !','Lecteur passeport');
    }
    function setStatus(type,html) {
        var c={'success':'#28D094','warning':'#FF9149','danger':'#FF4961','info':'#1E9FF2'};
        $('#passport-status').html('<span style="color:'+(c[type]||'#333')+'">'+html+'</span>');
    }
    var checkTimer=null;
    $('#numero_passeport').on('input blur',function(){
        var n=$(this).val().trim(); if(n.length<5) return;
        clearTimeout(checkTimer);
        checkTimer=setTimeout(function(){
            $.get('/api/passport/check/'+encodeURIComponent(n),function(data){
                if(data.found) afficherModalPasseport(data.demande);
            });
        },600);
    });
    function afficherModalPasseport(d) {
        var sc={'En attente d\'approbation':'#FF9149','Approuvée':'#28D094','Rejetée':'#FF4961','Livrée':'#1E9FF2'}[d.statut_demande]||'#666';
        var photo=d.photo?'<img src="/app/'+d.photo+'" style="height:100px;border-radius:6px;">':'<div style="height:100px;width:80px;background:#eee;border-radius:6px;display:flex;align-items:center;justify-content:center;"><i class="la la-user" style="font-size:2em;color:#999;"></i></div>';
        var html='<div class="row"><div class="col-md-2 text-center">'+photo+'</div><div class="col-md-10"><table class="table table-bordered table-sm">'+
            '<tr><th>Impétrant</th><td><strong>'+(d.nom||'')+' '+(d.prenom||'')+'</strong></td></tr>'+
            '<tr><th>N° passeport</th><td><strong>'+(d.numero_document||'')+'</strong></td></tr>'+
            '<tr><th>Statut</th><td><span style="color:'+sc+';font-weight:bold;">'+(d.statut_demande||'')+'</span></td></tr>'+
            '<tr><th>UUID</th><td><code>'+(d.uuid||'')+'</code></td></tr>'+
            '</table></div></div>'+
            '<div class="alert alert-warning mt-1 mb-0"><i class="la la-exclamation-triangle"></i> Ce passeport est déjà enregistré. Renouveler ou corriger ?</div>';
        $('#modal-passeport-body').html(html);
        $('#btn-voir-demande').attr('href','/demandes/'+d.demande_id);
        $('#btn-renouveler').attr('href','/demandes/'+d.impetrant_id+'/renouveler');
        $('#modal-passeport-existant').modal('show');
    }
});
</script>
@endsection
