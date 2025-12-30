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


                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Information de l'Impétrant | Statut de la demande : <strong>{{ $demande->statut_demande }}</strong> </h4>
                                            <div class="form-group row">
    @php
    use App\Models\Pays;

    $pays = null;

    if ($demande->impetrant && $demande->impetrant->nationalites_id) {
        $pays = Pays::find($demande->impetrant->nationalites_id);
    }

    $flagPath = $pays && $pays->code
        ? 'res/flags/' . strtolower(trim($pays->code)) . '.png'
        : null;
    @endphp

<div class="col-md-3 d-flex justify-content-center">
    <div class="d-flex align-items-center">

        <div class="photo-box">
            <img src="{{ asset('app/'.$demande->photo) }}" alt="Photo de l'impétrant">
        </div>

        @if($flagPath && file_exists(public_path($flagPath)))
            <div class="flag-box">
                <img src="{{ asset($flagPath) }}" alt="Drapeau">
                <div class="flag-label">
                    {{ $pays->lib_pays }}
                </div>
            </div>
        @endif

    </div>
</div>

<style>
.photo-box {
    width: 180px;
    height: 180px;
    border: 2px solid #e5e5e5;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.photo-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.flag-box {
    margin-left: 20px; /* ← ESPACE RÉEL */
    padding: 8px 10px;
    background: #f5f5f5;
    border-radius: 6px;
    text-align: center;
    min-width: 90px;
}

.flag-box img {
    width: 64px;
    height: auto;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.flag-label {
    margin-top: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #333;
}
</style>






                                                <div class="col-md-6 mx-auto">
                                                        @if ($demande->statut_demande == "En attente d'approbation")
                                                       <!-- <form action="{{route('demandes.changestate',$demande->id)}}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="statut_demande" value="Approuvée">
                                                            <button type="submit" class="btn btn-success btn-block mb-1">Approuver</button>
                                                        </form> -->

                                                        <!-- Bouton qui ouvre la confirmation -->
<button type="button" class="btn btn-success btn-block mb-1" data-toggle="modal" data-target="#confirmApprovalModal">
    Approuver
</button>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmApprovalModal" tabindex="-1" role="dialog" aria-labelledby="confirmApprovalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="confirmApprovalLabel">
                    ⚠️ Confirmation d’approbation
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>
                    Vous êtes sur le point <strong>d’approuver définitivement</strong> cette demande.
                </p>
                <p class="text-danger">
                    Cette action est <strong>irréversible</strong>.
                </p>
                <p>
                    Confirmez-vous cette décision ?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Annuler
                </button>

                <form action="{{route('demandes.changestate',$demande->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="statut_demande" value="Approuvée">
                    <button type="submit" class="btn btn-success">
                        ✅ Confirmer l’approbation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

                                                        <a href="{{route("demandes.create.contentieux",$demande->id)}}" class="btn btn-warning btn-block mb-1">Envoyer au contentieux</a>
                                                        @endif

                                                        @if ($demande->statut_demande == "Envoyée au contentieux")
                                                        <form action="{{route('demandes.changestate',$demande->id)}}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="statut_demande" value="Approuvée">
                                                            <button type="submit" class="btn btn-success btn-block mb-1">Approuver</button>
                                                        </form>
                                                        @endif


                                                        @if ($demande->statut_demande == "Approuvée" && $demande->numero_document == null)
                                                        <a href="{{ route("demandes.remplirformation",$demande->id) }}" class="btn btn-secondary btn-block mb-1">Renseigner les information de {{ $demande->type_demande }}</a>
                                                        @endif
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td><a href="{{ route("demandes.edit",$demande->id) }}" class="btn btn-primary">Modifier</a></td>

                                                                @if($demande->fiches->count() > 0)
                                                                    @if(\Carbon\Carbon::parse($demande->fiches->last()->date_valite_fiche)->gt(\Carbon\Carbon::now()))
                                                                        <td><a href="{{ route("demandes.fiche",$demande->id) }}" class="btn btn-secondary">Exporter la fiche <i class="la la-folder-open"></i></a></td>
                                                                    @else
                                                                        <td>
                                                                            <form action="{{ route("demandes.renouveler.fiche",$demande->id) }}" method="POST">
                                                                                @csrf
                                                                                <button type="submit" class="btn btn-danger">Renouveller la fiche</button>
                                                                            </form>
                                                                        </td>
                                                                    @endif
                                                                @endif
                                                            </tr>
                                                        </table>
                                                        @if($demande->createur)
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <th>Operateur</th>
                                                                    <td>
                                                                        <a href="{{ route('users.show', $demande->createur->id) }}" style="color: black; text-decoration: none;" onmouseover="this.style.color='blue'" onmouseout="this.style.color='black'">
                                                                            {{$demande->createur->getNomPrenom()}}
                                                                        </a>
                                                                                                                                            
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        @endif
                                                        @if ($demande->numero_document != "")
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <th>Numéro du document</th>
                                                                    <td>{{ $demande->numero_document }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Date emission</th>
                                                                    <td>{{ date("d/m/Y",strtotime($demande->date_emission)) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Date d'expiration</th>
                                                                    <td>{{ date("d/m/Y",strtotime($demande->date_expiration)) }}</td>
                                                                </tr>
                                                            </table>
                                                        @endif
                                                        @if ($sims->count() > 0)
                                                            <p class="text-danger">Cet impétrant a {{$sims->count()}} forte(s) similarité(s) avec d'autres impétrant(s)</p>
                                                            <p>Veuillez cliquer ici pour <a href="{{ route("demandes.similarities",$demande->id) }}">Voir les similarités</a></p>
                                                        @endif

                                                        @if ($demande->contentieux->count() > 0)
                                                            <div class="card mt-4 border-danger">
                                                                <div class="card-header bg-danger text-white">
                                                                    <strong>Contentieux associé(s) a cette demande</strong>
                                                                </div>
                                                                <div class="card-body">
                                                                    @foreach ($demande->contentieux as $contentieux)
                                                                        <div class="mb-3 p-3 border rounded shadow-sm bg-light">
                                                                            <h5 class="text-danger mb-2">Motif : {{ $contentieux->motif->lib_motif ?? 'Motif non défini' }}</h5>
                                                                            <p class="mb-1"><strong>Description :</strong> {{ $contentieux->description ?? 'Aucune description fournie' }}</p>
                                                                            <small class="text-muted">Ajouté le : {{ \Carbon\Carbon::parse($contentieux->created_at)->format('d/m/Y - H:i') }}</small>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                </div>
                                            </div>
                                            @if($demande?->soit_transmis_id != "" || $demande?->soit_transmis_id != null)
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="nom">Numéro soit-transmis </label>
                                                    <div class="col-md-9 mx-auto">
                                                        <a  href="{{route('soit-transmis.show',$demande?->soitTransmis->id)}}" class="">{{$demande?->soitTransmis->numero}} <i class="la la-folder-open"></i></a>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nom">Nom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" placeholder="Nom" value="{{$demande->impetrant?->nom}}" name="nom" disabled>

                                                </div>

                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="prenom">Prénom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="prenom" class="form-control @error('prenom') is-invalid @enderror" placeholder="Prénom" value="{{$demande->impetrant?->prenom}}" name="prenom" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="sexe">Sexe *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select  id="sexe" class="form-control @error('sexe') is-invalid @enderror"  name="sexe" disabled>
                                                        <option value="Masculin" {{"Masculin"==$demande->impetrant?->sexe ? "selected":""}}>Masculin</option>
                                                        <option value="Féminin" {{"Féminin"==$demande->impetrant?->sexe ? "selected":""}}>Féminin</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_naissance">Date de naissance *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror"  value="{{$demande->impetrant?->date_naissance}}" name="date_naissance" disabled>

                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="lieu_naissance">Lieu de naissance *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror"  value="{{$demande->impetrant?->lieu_naissance}}" name="lieu_naissance" placeholder="Lieu de naissance" disabled>

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
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nationalites_id">Nationalité(s) *</label>
                                                <div class="col-md-9 mx-auto">
@php

    $badgeNationalite = null;

    if ($demande->impetrant && $demande->impetrant->nationalites_id) {
        $paysNat = Pays::find($demande->impetrant->nationalites_id);

        if ($paysNat) {
            $badgeNationalite = $paysNat->nationalite ?: $paysNat->lib_pays;
        }
    }
@endphp

@if($badgeNationalite)
    <span class="badge badge-primary">{{ $badgeNationalite }}</span>
@endif


                                                </div>
                                            </div>
                                            <h4 class="form-section"><i class="ft-home"></i> Information de contact</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="departements_id">Département *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select class="select2-theme form-control" id="departements_id" name="departements_id" disabled>
                                                                <option value="">Selectionner</option>
                                                        @forelse ($departements as $d)
                                                                <option value="{{$d->id}}" {{ $d->id==$demande->quartier?->arrondissement?->departement?->id ? "selected":"" }}>{{ $d->lib_departement }}</option>
                                                        @empty

                                                        @endforelse

                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="arrondissements_id">Arrondissement / Commune *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select  id="arrondissements_id" class="form-control @error('arrondissements_id') is-invalid @enderror"  name="arrondissements_id" disabled>
                                                        <option value="{{$demande->quartier?->arrondissement?->id}}">{{ $demande->quartier?->arrondissement?->lib_arrondissement }}</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="quartiers_id">Quartier / Village *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select  id="quartiers_id" class="form-control @error('quartiers_id') is-invalid @enderror"  name="quartiers_id" disabled>
                                                        <option value="{{$demande->quartier?->id}}">{{ $demande->quartier?->lib_quartier }}</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="avenue_rue">Avenue / Rue *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="avenue_rue" class="form-control @error('avenue_rue') is-invalid @enderror"  value="{{$demande->avenue_rue}}" name="avenue_rue" placeholder="Avenue / rue" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="numero_adresse">Numéro domicile *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="numero_adresse" class="form-control @error('numero_adresse') is-invalid @enderror"  value="{{$demande->numero_adresse}}" name="numero_adresse" placeholder="Numéro" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="telephone">Numéro de téléphone *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="telephone" class="form-control @error('telephone') is-invalid @enderror"  value="{{$demande->telephone}}" name="telephone" placeholder="Numéro de téléphone" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="email">Email *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="email" class="form-control @error('email') is-invalid @enderror"  value="{{$demande->email}}" name="email" placeholder="Email" disabled>

                                                </div>
                                            </div>
                                            {{-- Information des parents --}}
                                            <h4 class="form-section"><i class="ft-users"></i> Information des parents</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nom_pere">Nom père *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="nom_pere" class="form-control @error('nom_pere') is-invalid @enderror"  value="{{$demande->impetrant?->nom_pere}}" name="nom_pere" placeholder="Nom du père" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="prenom_pere">Prénom père *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="prenom_pere" class="form-control @error('prenom_pere') is-invalid @enderror"  value="{{$demande->impetrant?->prenom_pere}}" name="prenom_pere" placeholder="Prénom du père" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nom_mere">Nom de la mère *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="nom_mere" class="form-control @error('nom_mere') is-invalid @enderror"  value="{{$demande->impetrant?->nom_mere}}" name="nom_mere" placeholder="Nom de la mère" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="prenom_mere">Prénom mère *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="prenom_mere" class="form-control @error('prenom_mere') is-invalid @enderror"  value="{{$demande->impetrant?->prenom_mere}}" name="prenom_mere" placeholder="Prénom du mère" disabled>

                                                </div>
                                            </div>
                                            {{-- Information de l'employeur --}}
                                            <h4 class="form-section"><i class="ft-users"></i> Information de la profession</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3" for="categorie_socioprofessionnelle_id">Catégorie socio-professionelle *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="categorie_socioprofessionnelle_id" class="form-control @error('categorie_socioprofessionnelle_id') is-invalid @enderror"  name="categorie_socioprofessionnelle_id"  disabled>
                                                        @forelse ($categories as $item)
                                                            <option value="{{$item->id}}" {{$item->id==$demande->categorie_socioprof_id ? "selected":""}}>{{ $item->categorie }}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="profession">Profession *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="profession" class="form-control @error('profession') is-invalid @enderror"  value="{{$demande->profession}}" name="profession" placeholder="Votre profession" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="employeur">Nom de l'Employeur </label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="employeur" class="form-control @error('employeur') is-invalid @enderror"  value="{{$demande->employeur?->nom_employeur}}" name="employeur" placeholder="Nom de l'employeur" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="adresse_employeur">Adresse de l'Employeur </label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="adresse_employeur" class="form-control @error('adresse_employeur') is-invalid @enderror"  value="{{$demande->employeur?->adresse_physique}}" name="adresse_employeur" placeholder="Adresse de l'employeur" disabled>

                                                </div>
                                            </div>
                                            <h4 class="form-section"><i class="ft-file"></i> Information du passeport </h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="numero_passeport">Numéro du passeport *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="numero_passeport" class="form-control @error('numero_passeport') is-invalid @enderror"  value="{{$demande->passeport()?->numero_document}}" name="numero_passeport" placeholder="Numéro du passeport" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_emission_passeport">Date d'émission *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_emission_passeport" class="form-control @error('date_emission_passeport') is-invalid @enderror"  value="{{$demande->passeport()?->date_emission}}" name="date_emission_passeport" placeholder="Date émission du passeport" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_expiration_passeport">Date d'expiration*</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_expiration_passeport" class="form-control @error('date_expiration_passeport') is-invalid @enderror"  value="{{$demande->passeport()?->date_expiration}}" name="date_expiration_passeport" placeholder="Date d'expiration du passeport" disabled>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="passeport_delivre_par">Délivré par*</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="passeport_delivre_par" class="form-control @error('passeport_delivre_par') is-invalid @enderror"  value="{{$demande->passeport()?->emis_par}}" name="passeport_delivre_par" placeholder="Délivré par" disabled>

                                                </div>
                                            </div>
                                            {{-- Section demande --}}
                                            <h4 class="form-section"><i class="ft-file"></i> Information sur la demande</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="type_demande">Type demande *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="type_demande" id="type_demande" class="form-control @error('type_demande') is-invalid @enderror" disabled>
                                                        <option value="">Selectionner</option>
                                                        <option value="Carte de résident temporaire" {{"Carte de résident temporaire"==$demande->type_demande ? "selected":""}}>Carte de résident temporaire</option>
                                                        <option value="Visa" {{"Visa"==$demande->type_demande ? "selected":""}}>Visa</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="validite">Validité *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select name="validite" id="validite" class="form-control @error('validite') is-invalid @enderror" disabled>
                                                        <option value="">Selectionner</option>
                                                        @forelse ($validites as $validite)
                                                            <option value="{{$validite}}" {{ $validite==$demande->validite ? "selected":"" }}>{{$validite}}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_demande">Date de la demande *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" name="date_demande" id="date_demande" class="form-control @error('date_demande') is-invalid @enderror" value="{{$demande->date_demande}}" disabled>


                                                </div>
                                            </div>

                                        <div class="form-actions">
                                            <a href="{{route('demandes.index')}}" class="btn btn-warning">Retour</a>
                                        </div>

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
