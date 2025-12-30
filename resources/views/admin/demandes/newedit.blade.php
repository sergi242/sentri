@extends('admin.layouts.app')
@section('title')
    Nouvelle demande CRT
@endsection
@section('styles')

    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('img/editorial.css')}}" type="text/css">
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
                        <div class="card animated fadeIn">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                   @if($demande->type_demande == "Visa")
                                    <div class="p-5 bg-visa" style="color: #03658C; border-radius: 12px; width: 100%;">
                                        <div class="text-center">
                                            <!-- Titre "VISA" -->
                                            <h1 class="font-weight-bold mb-4" style="font-size: 4em; letter-spacing: 0.1em; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); color:#51818C;">VISA</h1>
                                    
                                            <!-- Texte secondaire -->
                                            <div class="mt-4" style="font-size: 1.1em; line-height: 1.5;">
                                                MINISTÈRE DE L'INTERIEUR ET DE LA DÉCENTRALISATION -
                                                CENTRALE D'INTELLIGENCE ET DE DOCUMENTATION -
                                                DÉPARTEMENT DES MIGRATIONS ET DU CONTRÔLE DES ÉTRANGERS
                                            </div>
                                        </div>
                                    </div>
                                    <style>
                                        .bg-visa{
                                            background-color:#E9ECF2;
                                        }
                                        .text_red{
                                            color: #dc3545;
                                        }
                                        .gras{
                                            text-decoration: underline !important;
                                        }
                                        br{
                                            line-height: 0px;
                                            font-weight: 500;
                                        }
                                        label{
                                            font-weight: normal;
                                        }
                                        .navbar-semi-dark {
                                            background: none;
                                        }
                                        a {
                                            text-decoration: none;
                                        }
                                        input[type="text"], [type="search"]:focus {
                                            border: none;
                                            border-radius: 4px;
                                        }
                                    </style>
                                   @else
                                    <div class="row">
                                        <div class="col-md-1">
                                        <img src="{{asset('img/armoi_.png')}}" id="carte" alt="">
                                        </div>
                                        <div class="col-md-4">
                                        <p>
                                            <h1 id="quitance">QUITANCE</h1>
                                            <h4>République du Congo</h4>
                                            <h6>Unité * Travail * Progrès</h6>
                                        </p>
                                        </div>
                                        <div class="col-md-1">
                                        <img src="{{asset('img/congo.svg')}}" id="carte" alt="">
                                        </div>
                                    </div>
                                    <div class="row" style="background-color: #000">
                                        <div class="col-md-1">
                                            <img src="{{asset('img/armoi_2.png')}}" id="carte" alt="">
                                        </div>
                                        <div class="col-md-7" >
                                            <h1 style="color: #fff; font-size:45px">DEMANDE DE LIVRANCE</h1>
                                            <h1 style="color: #fff; font-size:45px">DE CARTE DE RESIDENT</h1>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <img src="{{asset('img/armoi_2.png')}}" id="carte" alt="">
                                                </div>
                                                <div class="col-md-9">
                                                    <h4 style="color: #fff; font-size:22px">REPUBLIQUE DU CONGO</h4>
                                                    <h6 class="center tb">Unité * Travail * Progrès</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        <span class="tb" style="font-size:12px;">MINISTERE DE L'INTERIEUR ET DE LA DECENTRALISATION - CENTRALE D'INTELIGENCE ET DE DOCUMENTATION-DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS</span>
                                        </div>
                                        <!-- <div class="col-md-2 form-group cadre_carte">
                                        <img src="assets/img/congo.svg" id="carte" alt="">
                                        </div> -->
                                    </div>
                                   @endif
                                    <form class="form form-horizontal" method="POST" action="{{route('demandes.update',$demande->id)}}" enctype="multipart/form-data">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">
                                            <div class="col-md-5 mt-2 mr-1 p2" style="border: #000 solid 1px; border-radius:10px 10px 10px 10px">
                                                <h4 class="form-section"><i class="ft-file"></i> Pièces jointes</h4>
                                                <div class="row">
                                                    <div class="col">
                                                        @forelse ($pieces as $p)
                                                            <div class="form-group row">
                                                               <label for="{{$p->id}}" class="col-md-6">{{$p->piece}} </label> <input type="checkbox" name="justificatifs[]" value="{{$p->id}}" id="{{$p->id}}" class="col-md-6" {{ $demande->pieces->contains($p->id) ? "checked":"" }}>
                                                            </div>
                                                        @empty

                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-4 label-control" for="nom"></label>
                                                    <div class="col-md-8 mx-auto">
                                                        <img src="{{asset("app/$demande->photo")}}" alt="" width="200" height="200" class="img-thumbnail" >
                                                    </div>
                                                </div>
                                                <h4 class="form-section"><i class="ft-file"></i> Information du passeport </h4>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="numero_passeport">Numéro du passeport *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="numero_passeport" class="form-control @error('numero_passeport') is-invalid @enderror"  value="{{$demande->passeport()?->numero_document}}" name="numero_passeport" placeholder="Numéro du passeport" required>
                                                    @error('numero_passeport')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="date_emission_passeport">Date d'émission *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="date" id="date_emission_passeport" class="form-control @error('date_emission_passeport') is-invalid @enderror"  value="{{$demande->passeport()?->date_emission}}" name="date_emission_passeport" placeholder="Date émission du passeport" required>
                                                    @error('date_emission_passeport')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="date_expiration_passeport">Date d'expiration*</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="date" id="date_expiration_passeport" class="form-control @error('date_expiration_passeport') is-invalid @enderror"  value="{{$demande->passeport()?->date_expiration}}" name="date_expiration_passeport" placeholder="Date d'expiration du passeport" required>
                                                    @error('date_expiration_passeport')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="passeport_delivre_par">Délivré par*</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="passeport_delivre_par" class="form-control @error('passeport_delivre_par') is-invalid @enderror"  value="{{$demande->passeport()?->emis_par}}" name="passeport_delivre_par" placeholder="Délivré par" required>
                                                    @error('passeport_delivre_par')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>



                                            {{-- Section demande --}}
                                            <h4 class="form-section"><i class="ft-file"></i> Information sur la demande</h4>
                                            <div class="form-group row">
                                                <label class="col-md-4" for="uuid">Numéro fiche demande *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" name="uuid" id="uuid" class="form-control @error('uuid') is-invalid @enderror" value="{{$demande->uuid}}">

                                                    @error('uuid')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="type_demande">Type demande *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <select name="type_demande" id="type_demande" class="form-control @error('type_demande') is-invalid @enderror">
                                                        <option value="">Selectionner</option>
                                                        <option value="Carte de résident temporaire" {{"Carte de résident temporaire"==$demande->type_demande ? "selected":""}}>Carte de résident temporaire</option>
                                                        <option value="Visa" {{"Visa"==$demande->type_demande ? "selected":""}}>Visa</option>
                                                    </select>
                                                    @error('type_demande')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="validite">Validité *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <select name="validite" id="validite" class="form-control @error('validite') is-invalid @enderror">
                                                        <option value="">Selectionner</option>
                                                        @forelse ($validites as $validite)
                                                            <option value="{{$validite}}" {{ $validite==$demande->validite ? "selected":"" }}>{{$validite}}</option>
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
                                                <label class="col-md-4 label-control" for="date_demande">Date de la demande *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="date" name="date_demande" id="date_demande" class="form-control @error('date_demande') is-invalid @enderror" value="{{$demande->date_demande}}">

                                                    @error('date_demande')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>


                                            </div>
                                            <div class="col-md-6 mt-2 p2" style="border: #000 solid 1px; border-radius:10px 10px 10px 10px">
                                                <div class="form-body">
                                                    <h4 class="form-section"><i class="ft-user"></i> Information de l'Impétrant</h4>

                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="nom">Nom *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" placeholder="Nom" value="{{$demande->impetrant?->nom}}" name="nom" required>
                                                    @error('nom')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="prenom">Prénom *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="prenom" class="form-control @error('prenom') is-invalid @enderror" placeholder="Prénom" value="{{$demande->impetrant?->prenom}}" name="prenom" required>
                                                    @error('prenom')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="sexe">Sexe *</label>
                                                <div class="col-md-8 mx-auto">
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
                                                <label class="col-md-4 label-control" for="date_naissance">Date de naissance *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="date" id="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror"  value="{{$demande->impetrant?->date_naissance}}" name="date_naissance" required>
                                                    @error('date_naissance')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="lieu_naissance">Lieu de naissance *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror"  value="{{$demande->impetrant?->lieu_naissance}}" name="lieu_naissance" placeholder="Lieu de naissance" required>
                                                    @error('lieu_naissance')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="etat_civil">Etat civil *</label>
                                                <div class="col-md-8 mx-auto">
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
                                                <label class="col-md-4 label-control" for="nationalites_id">Nationalité *</label>
                                                <div class="col-md-8 mx-auto">
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
                                                <label class="col-md-4 label-control" for="departements_id">Département *</label>
                                                <div class="col-md-8 mx-auto">
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
                                                <label class="col-md-4 label-control" for="arrondissements_id">Arrondissement / Commune *</label>
                                                <div class="col-md-8 mx-auto">
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
                                                <label class="col-md-4 label-control" for="quartiers_id">Quartier / Village *</label>
                                                <div class="col-md-8 mx-auto">
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
                                                <label class="col-md-4 label-control" for="avenue_rue">Avenue / Rue *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="avenue_rue" class="form-control @error('avenue_rue') is-invalid @enderror"  value="{{$demande->avenue_rue}}" name="avenue_rue" placeholder="Avenue / rue" required>
                                                    @error('avenue_rue')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="numero_adresse">Numéro domicile *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="numero_adresse" class="form-control @error('numero_adresse') is-invalid @enderror"  value="{{$demande->numero_adresse}}" name="numero_adresse" placeholder="Numéro" required>
                                                    @error('numero_adresse')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="telephone">Numéro de téléphone *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="telephone" class="form-control @error('telephone') is-invalid @enderror"  value="{{$demande->telephone}}" name="telephone" placeholder="Numéro de téléphone" required>
                                                    @error('telephone')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="email">Email *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="email" class="form-control @error('email') is-invalid @enderror"  value="{{$demande->email}}" name="email" placeholder="Email" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="photo">Photo *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="file" id="photo" class="form-control @error('photo') is-invalid @enderror"  name="photo" accept="image/*">
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
                                                <label class="col-md-4 label-control" for="nom_pere">Nom père *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="nom_pere" class="form-control @error('nom_pere') is-invalid @enderror"  value="{{$demande->impetrant?->nom_pere}}" name="nom_pere" placeholder="Nom du père" required>
                                                    @error('nom_pere')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="prenom_pere">Prénom père *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="prenom_pere" class="form-control @error('prenom_pere') is-invalid @enderror"  value="{{$demande->impetrant?->prenom_pere}}" name="prenom_pere" placeholder="Prénom du père" required>
                                                    @error('prenom_pere')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="nom_mere">Nom de la mère *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="nom_mere" class="form-control @error('nom_mere') is-invalid @enderror"  value="{{$demande->impetrant?->nom_mere}}" name="nom_mere" placeholder="Nom de la mère" required>
                                                    @error('nom_mere')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="prenom_mere">Prénom mère *</label>
                                                <div class="col-md-8 mx-auto">
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
                                                <label class="col-md-3" for="categorie_socioprofessionnelle_id">Catégorie socio-professionelle *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="categorie_socioprofessionnelle_id" class="form-control @error('categorie_socioprofessionnelle_id') is-invalid @enderror"  value="{{old('categorie_socioprofessionnelle_id')}}" name="categorie_socioprofessionnelle_id"  required>
                                                        @forelse ($categories as $item)
                                                            <option value="{{$item->id}}" {{ $item->id == $demande->categorie_socioprof_id ? "selected":"" }}>{{ $item->categorie }}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>
                                                    @error('categorie_socioprofessionnelle_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 label-control" for="profession">Profession *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <input type="text" id="profession" class="form-control @error('profession') is-invalid @enderror"  value="{{$demande->profession}}" name="profession" placeholder="Votre profession" required>
                                                    @error('profession')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-4" for="employeur_id">Employeur *</label>
                                                <div class="col-md-8 mx-auto">
                                                    <select class="select2-theme form-control" id="employeur_id" name="employeur_id">
                                                                <option value="">Selectionner</option>
                                                        @forelse ($employeurs as $employeur)
                                                                <option value="{{$employeur->id}}" {{ $employeur->id==$demande->employeur_id ? "selected":"" }}>{{ $employeur->nom_employeur }}</option>
                                                        @empty

                                                        @endforelse

                                                    </select>
                                                    @error('employeur_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                                </div>
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
