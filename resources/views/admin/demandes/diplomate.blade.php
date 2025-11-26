@extends('admin.layouts.app')

@section('title')
    Nouvelle demande CRT – Diplomate
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('res/app-assets/vendors/css/forms/selects/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('img/editorial.css') }}" type="text/css">

    <style>
        /* Style "diplomatique" léger */
        .diplomatic-page {
    background-color: #112769;   /* Beige élégant */
    padding: 20px;
}



        .diplomatic-header-top h1#quittance {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .diplomatic-header-top h4,
        .diplomatic-header-top h6 {
            margin: 0;
            line-height: 1.3;
        }

        .diplomatic-logo {
            max-height: 80px;
        }

        .diplomatic-banner {
            background-color: #000;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .diplomatic-banner h1 {
            font-size: 30px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
        }

        .diplomatic-banner h4 {
            font-size: 18px;
            margin: 0;
        }

        .diplomatic-ministry {
            font-size: 12px;
            text-transform: uppercase;
            color: #f1f1f1;
            display: block;
            margin-top: 5px;
        }

        .diplomatic-card {
            border: 1px solid #000;
            border-radius: 10px;
            padding: 10px 15px;
            background-color: #fff;
        }

        .form-section {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 600;
        }

        .form-actions {
            margin-top: 15px;
        }

        .tb {
            text-align: center;
        }

        #carte {
            max-height: 70px;
        }
    </style>
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
                            <div class="card-content collapse show">
                                <div class="card-body diplomatic-page">

                                   

                                    <div class="row diplomatic-banner">
                                        <div class="col-md-1 text-center">
                                            <img src="{{ asset('img/armoi_2.png') }}" class="diplomatic-logo" alt="Armoiries">
                                        </div>
                                        <div class="col-md-10 text-center">
                                            <h1>Demande de délivrance</h1>
                                            <h1>de carte de résident et Visa – Corps diplomatique</h1>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <img src="{{ asset('img/armoi_2.png') }}" class="diplomatic-logo" alt="Armoiries">
                                        </div>
                                        
                                        <div class="col-md-12 mt-1">
                                            <span class="tb diplomatic-ministry">
                                                Ministère de l'Intérieur et de la Décentralisation –
                                                Centrale d'Intelligence et de Documentation –
                                                Département des Migrations et du Contrôle des Étrangers – Section Diplomatico-consulaire
                                            </span>
                                        </div>
                                    </div>

                                    <form class="form form-horizontal"
                                          method="POST"
                                          action="{{ route('demandes.store') }}"
                                          enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">
                                            {{-- Colonne gauche : pièces & infos demande --}}
                                            <div class="col-md-5 mt-2 mr-1 diplomatic-card">
                                                <h4 class="form-section"><i class="ft-file"></i> Pièces jointes</h4>
                                                <div class="row">
                                                    <div class="col">
                                                        @forelse ($pieces as $p)
                                                            <div class="form-group row mb-1">
                                                                <label for="{{ $p->id }}" class="col-md-8 col-form-label">
                                                                    {{ $p->piece }}
                                                                </label>
                                                                <div class="col-md-4">
                                                                    <input type="checkbox"
                                                                           name="justificatifs[]"
                                                                           value="{{ $p->id }}"
                                                                           id="{{ $p->id }}"
                                                                           checked>
                                                                </div>
                                                            </div>
                                                        @empty
                                                        @endforelse
                                                    </div>
                                                </div>

                                                <h4 class="form-section"><i class="ft-file"></i> Information du passeport</h4>

                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="numero_passeport">
                                                        Numéro du passeport *
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="text"
                                                               id="numero_passeport"
                                                               class="form-control @error('numero_passeport') is-invalid @enderror"
                                                               value="{{ old('numero_passeport') }}"
                                                               name="numero_passeport"
                                                               placeholder="Numéro du passeport"
                                                               required>
                                                        @error('numero_passeport')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="date_emission_passeport">
                                                        Date d'émission *
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="date"
                                                               id="date_emission_passeport"
                                                               class="form-control @error('date_emission_passeport') is-invalid @enderror"
                                                               value="{{ old('date_emission_passeport') }}"
                                                               name="date_emission_passeport"
                                                               required>
                                                        @error('date_emission_passeport')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="date_expiration_passeport">
                                                        Date d'expiration *
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="date"
                                                               id="date_expiration_passeport"
                                                               class="form-control @error('date_expiration_passeport') is-invalid @enderror"
                                                               value="{{ old('date_expiration_passeport') }}"
                                                               name="date_expiration_passeport"
                                                               required>
                                                        @error('date_expiration_passeport')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="passeport_delivre_par">
                                                        Délivré par *
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <select id="passeport_delivre_par"
                                                                class="form-control @error('passeport_delivre_par') is-invalid @enderror"
                                                                name="passeport_delivre_par"
                                                                required>
                                                            <option value="République du Congo" selected>République du Congo</option>
                                                            <option value="Pays d'origine">Pays d'origine</option>
                                                        </select>
                                                        @error('passeport_delivre_par')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <h4 class="form-section"><i class="ft-file"></i> Information sur la demande</h4>

                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="uuid">
                                                        N° fiche demande *
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="text"
                                                               name="uuid"
                                                               id="uuid"
                                                               class="form-control @error('uuid') is-invalid @enderror"
                                                               value="{{ old('uuid') }}">
                                                        @error('uuid')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="type_demande">
                                                        Type de demande *
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <select name="type_demande"
                                                                id="type_demande"
                                                                class="form-control @error('type_demande') is-invalid @enderror">
                                                            <option value="Carte de résident temporaire">
                                                                Carte de résident temporaire
                                                            </option>
                                                            <option value="Visa">Visa</option>
                                                        </select>
                                                        @error('type_demande')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="validite">
                                                        Validité *
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <select name="validite"
                                                                id="validite"
                                                                class="form-control @error('validite') is-invalid @enderror">
                                                            @forelse ($validites as $validite)
                                                                <option value="{{ $validite }}"
                                                                    {{ $validite == old('validite') ? 'selected' : '' }}>
                                                                    {{ $validite }} (an)
                                                                </option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                        @error('validite')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="date_demande">
                                                        Date de la demande *
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="date"
                                                               name="date_demande"
                                                               id="date_demande"
                                                               class="form-control @error('date_demande') is-invalid @enderror"
                                                               value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                                        @error('date_demande')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Section finalisation --}}
                                                <h4 class="form-section"><i class="ft-check-square"></i> Opération de finalisation *</h4>

                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="tag_demande">
                                                        Libellé des données *
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <select name="tag_demande"
                                                                id="tag_demande"
                                                                class="form-control @error('tag_demande') is-invalid @enderror">
                                                            <option value="IMPRESSION">IMPRESSION</option>
                                                            <option value="REPRISE">REPRISE</option>
                                                        </select>
                                                        @error('tag_demande')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row attributed">
                                                    <label class="col-md-4 col-form-label" for="numero_document">
                                                        Numéro attribué
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="text"
                                                               name="numero_document"
                                                               id="numero_document"
                                                               placeholder="Numéro du document"
                                                               class="form-control @error('numero_document') is-invalid @enderror"
                                                               value="{{ old('numero_document') }}">
                                                        @error('numero_document')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row attributed">
                                                    <label class="col-md-4 col-form-label" for="date_attribution">
                                                        Date d'émission
                                                    </label>
                                                    <div class="col-md-8 mx-auto">
                                                        <input type="date"
                                                               name="date_attribution"
                                                               id="date_attribution"
                                                               placeholder="Date d'émission"
                                                               class="form-control @error('date_attribution') is-invalid @enderror"
                                                               value="{{ old('date_attribution') }}">
                                                        @error('date_attribution')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-12 mx-auto">
                                                        <label for="preview">
                                                            <input type="radio"
                                                                   name="operation"
                                                                   id="preview"
                                                                   value="preview">
                                                            &nbsp;Aperçu avant validation
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <label for="validate">
                                                            <input type="radio"
                                                                   name="operation"
                                                                   id="validate"
                                                                   value="validate"
                                                                   checked>
                                                            &nbsp;Validation des données
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Colonne droite : diplomate & contact --}}
                                            <div class="col-md-6 mt-2 diplomatic-card">
                                                <div class="form-body">
                                                    <h4 class="form-section">
                                                        <i class="ft-user"></i> Information du diplomate
                                                    </h4>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="nom">
                                                            Nom *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="nom"
                                                                   class="form-control @error('nom') is-invalid @enderror"
                                                                   placeholder="Nom"
                                                                   value="{{ old('nom') }}"
                                                                   name="nom"
                                                                   required>
                                                            @error('nom')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="prenom">
                                                            Prénom *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="prenom"
                                                                   class="form-control @error('prenom') is-invalid @enderror"
                                                                   placeholder="Prénom"
                                                                   value="{{ old('prenom') }}"
                                                                   name="prenom"
                                                                   required>
                                                            @error('prenom')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="sexe">
                                                            Sexe *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select id="sexe"
                                                                    class="form-control @error('sexe') is-invalid @enderror"
                                                                    name="sexe"
                                                                    required>
                                                                <option value="Masculin" {{ 'Masculin' == old('sexe') ? 'selected' : '' }}>Masculin</option>
                                                                <option value="Féminin" {{ 'Féminin' == old('sexe') ? 'selected' : '' }}>Féminin</option>
                                                            </select>
                                                            @error('sexe')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="date_naissance">
                                                            Date de naissance *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="date"
                                                                   id="date_naissance"
                                                                   class="form-control @error('date_naissance') is-invalid @enderror"
                                                                   value="{{ old('date_naissance') }}"
                                                                   name="date_naissance"
                                                                   required>
                                                            @error('date_naissance')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="lieu_naissance">
                                                            Lieu de naissance *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="lieu_naissance"
                                                                   class="form-control @error('lieu_naissance') is-invalid @enderror"
                                                                   value="{{ old('lieu_naissance') }}"
                                                                   name="lieu_naissance"
                                                                   placeholder="Lieu de naissance"
                                                                   required>
                                                            @error('lieu_naissance')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="etat_civil">
                                                            État civil *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control"
                                                                    id="etat_civil"
                                                                    name="etat_civil">
                                                                @forelse ($etatsCivils as $etatsCivil)
                                                                    <option value="{{ $etatsCivil }}"
                                                                        {{ $etatsCivil == old('etat_civil') ? 'selected' : '' }}>
                                                                        {{ $etatsCivil }}
                                                                    </option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                            @error('etat_civil')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row nc">
                                                        <label class="col-md-4 col-form-label" for="nom_conjoint">
                                                            Nom du conjoint *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   id="nom_conjoint"
                                                                   name="nom_conjoint"
                                                                   placeholder="Nom du conjoint">
                                                            @error('nom_conjoint')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="nationalites_id">
                                                            Nationalité 1 *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control"
                                                                    id="nationalites_id"
                                                                    name="nationalites_id">
                                                                <option value="">Sélectionner</option>
                                                                @forelse ($pays as $p)
                                                                    <option value="{{ $p->id }}"
                                                                        {{ $p->id == old('nationalites_id') ? 'selected' : '' }}>
                                                                        {{ $p->lib_pays }}
                                                                    </option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                            @error('nationalites_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="nationalite_2">
                                                            Nationalité 2
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control"
                                                                    id="nationalite_2"
                                                                    name="nationalite_2">
                                                                <option value="">Sélectionner</option>
                                                                @forelse ($pays as $p)
                                                                    <option value="{{ $p->id }}"
                                                                        {{ $p->id == old('nationalite_2') ? 'selected' : '' }}>
                                                                        {{ $p->lib_pays }}
                                                                    </option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                            @error('nationalite_2')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <h4 class="form-section">
                                                        <i class="ft-home"></i> Coordonnées au Congo
                                                    </h4>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="departements_id">
                                                            Département *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control"
                                                                    id="departements_id"
                                                                    name="departements_id">
                                                                <option value="">Sélectionner</option>
                                                                @forelse ($departements as $d)
                                                                    <option value="{{ $d->id }}">
                                                                        {{ $d->lib_departement }}
                                                                    </option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                            @error('departements_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="arrondissements_id">
                                                            Arrondissement / Commune *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select id="arrondissements_id"
                                                                    class="form-control @error('arrondissements_id') is-invalid @enderror"
                                                                    name="arrondissements_id"
                                                                    required>
                                                            </select>
                                                            @error('arrondissements_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="quartiers_id">
                                                            Quartier / Village *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select id="quartiers_id"
                                                                    class="form-control @error('quartiers_id') is-invalid @enderror"
                                                                    name="quartiers_id"
                                                                    required>
                                                            </select>
                                                            @error('quartiers_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="avenue_rue">
                                                            Avenue / Rue *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="avenue_rue"
                                                                   class="form-control @error('avenue_rue') is-invalid @enderror"
                                                                   value="{{ old('avenue_rue') }}"
                                                                   name="avenue_rue"
                                                                   placeholder="Avenue / rue"
                                                                   required>
                                                            @error('avenue_rue')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="numero_adresse">
                                                            Numéro domicile *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="numero_adresse"
                                                                   class="form-control @error('numero_adresse') is-invalid @enderror"
                                                                   value="{{ old('numero_adresse') }}"
                                                                   name="numero_adresse"
                                                                   placeholder="Numéro"
                                                                   required>
                                                            @error('numero_adresse')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="telephone">
                                                            Téléphone *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="telephone"
                                                                   class="form-control @error('telephone') is-invalid @enderror"
                                                                   value="{{ old('telephone') }}"
                                                                   name="telephone"
                                                                   placeholder="Numéro de téléphone"
                                                                   required>
                                                            @error('telephone')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="email">
                                                            Email
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="email"
                                                                   class="form-control @error('email') is-invalid @enderror"
                                                                   value="{{ old('email') }}"
                                                                   name="email"
                                                                   placeholder="Email">
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="photo">
                                                            Photo *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="file"
                                                                   id="photo"
                                                                   class="form-control @error('photo') is-invalid @enderror"
                                                                   name="photo"
                                                                   accept="image/*"
                                                                   required>
                                                            @error('photo')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    {{-- Information des parents --}}
                                                    <h4 class="form-section">
                                                        <i class="ft-users"></i> Information des parents
                                                    </h4>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="nom_pere">
                                                            Nom père *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="nom_pere"
                                                                   class="form-control @error('nom_pere') is-invalid @enderror"
                                                                   value="{{ old('nom_pere') }}"
                                                                   name="nom_pere"
                                                                   placeholder="Nom du père"
                                                                   required>
                                                            @error('nom_pere')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="prenom_pere">
                                                            Prénom père *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="prenom_pere"
                                                                   class="form-control @error('prenom_pere') is-invalid @enderror"
                                                                   value="{{ old('prenom_pere') }}"
                                                                   name="prenom_pere"
                                                                   placeholder="Prénom du père"
                                                                   required>
                                                            @error('prenom_pere')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="nom_mere">
                                                            Nom de la mère *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="nom_mere"
                                                                   class="form-control @error('nom_mere') is-invalid @enderror"
                                                                   value="{{ old('nom_mere') }}"
                                                                   name="nom_mere"
                                                                   placeholder="Nom de la mère"
                                                                   required>
                                                            @error('nom_mere')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="prenom_mere">
                                                            Prénom mère *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="prenom_mere"
                                                                   class="form-control @error('prenom_mere') is-invalid @enderror"
                                                                   value="{{ old('prenom_mere') }}"
                                                                   name="prenom_mere"
                                                                   placeholder="Prénom de la mère"
                                                                   required>
                                                            @error('prenom_mere')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    {{-- Information de la mission / employeur --}}
                                                    <h4 class="form-section">
                                                        <i class="ft-briefcase"></i> Information de la mission diplomatique
                                                    </h4>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="categorie_socioprofessionnelle_id">
                                                            Catégorie socio-professionnelle *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select id="categorie_socioprofessionnelle_id"
                                                                    class="form-control @error('categorie_socioprofessionnelle_id') is-invalid @enderror"
                                                                    name="categorie_socioprofessionnelle_id"
                                                                    required>
                                                                @forelse ($categories as $item)
                                                                    <option value="{{ $item->id }}" {{ $item->id == '6' ? 'selected' : '' }}>
                                                                        {{ $item->categorie }}
                                                                    </option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                            @error('categorie_socioprofessionnelle_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="profession">
                                                            Fonction / Titre *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <input type="text"
                                                                   id="profession"
                                                                   class="form-control @error('profession') is-invalid @enderror"
                                                                   value="{{ old('profession') }}"
                                                                   name="profession"
                                                                   placeholder="Fonction diplomatique"
                                                                   required>
                                                            @error('profession')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4 col-form-label" for="employeur_id">
                                                            Mission / Représentation *
                                                        </label>
                                                        <div class="col-md-8 mx-auto">
                                                            <select class="select2-theme form-control"
                                                                    id="employeur_id"
                                                                    name="employeur_id">
                                                                <option value="">Sélectionner</option>
                                                                @forelse ($employeurs as $employeur)
                                                                    <option value="{{ $employeur->id }}"
                                                                        {{ $employeur->id == old('employeur_id') ? 'selected' : '' }}>
                                                                        {{ $employeur->nom_employeur }}
                                                                    </option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                            @error('employeur_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                            <a href="{{ route('demandes.index') }}" class="btn btn-warning">
                                                Retour
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
    <script src="{{ asset('res/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('res/app-assets/js/scripts/forms/select/form-select2.min.js') }}"></script>
    <script>
        $(function () {
            $('#departements_id').on('change', function () {
                var id = $(this).val();
                if (id !== '') {
                    arrondissements(id, '#arrondissements_id');
                }
                return false;
            });

            $('#arrondissements_id').on('change', function () {
                var id = $(this).val();
                if (id !== '') {
                    quartiers(id, '#quartiers_id');
                }
                return false;
            });

            // Conjoint, caché par défaut
            $('.nc').hide();

            $('#etat_civil').on('change', function () {
                var me = $(this).val();
                var sexe = $('#sexe').val();
                if (me === 'Marié(e)' && sexe === 'Féminin') {
                    $('.nc').fadeIn(500);
                } else {
                    $('.nc').fadeOut(500);
                    $('#nom_conjoint').val('');
                }
            });

            // Recopie du nom vers nom du père (si souhaité)
            $('#nom').on('input', function () {
                $('#nom_pere').val($(this).val());
            });

            // Champs attribués (Numéro / Date émission), cachés par défaut
            $('.attributed').hide();
            $('#tag_demande').on('change', function () {
                var tag = $(this).val();
                if (tag === 'REPRISE') {
                    $('.attributed').slideDown(500);
                } else {
                    $('#numero_document').val('');
                    $('#date_attribution').val('');
                    $('.attributed').slideUp(500);
                }
            });

            // Calcul automatique de la date d'expiration du passeport
            $('#date_emission_passeport').on('change keyup', function () {
                getDateExpiration('#date_emission_passeport', '#date_expiration_passeport');
            });

            // Nom du diplomate toujours en majuscules
            $('#nom').on('input', function () {
                $(this).val(function (_, val) {
                    return val.toUpperCase();
                });
            });
        });

        function arrondissements(id, div) {
            var route = "{{ route('departements.arrondissements', 'id') }}";
            var out = "<option value=''>Sélectionner</option>";
            route = route.replace('id', id);
            $.get(route, function (data) {
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        out += "<option value='" + data[i].id + "'>" + data[i].lib_arrondissement + "</option>";
                    }
                    $(div).empty().append(out);
                }
            });
        }

        function quartiers(id, div) {
            var route = "{{ route('arrondissements.quartiers', 'id') }}";
            var out = "<option value=''>Sélectionner</option>";
            route = route.replace('id', id);
            $.get(route, function (data) {
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        out += "<option value='" + data[i].id + "'>" + data[i].lib_quartier + "</option>";
                    }
                    $(div).empty().append(out);
                }
            });
        }

        function getDateExpiration(fd, sd) {
            var startDate = new Date($(fd).val());
            var yearsToAdd = 5; // Nombre d'années de validité

            if (isNaN(startDate.getTime())) {
                return;
            }

            startDate.setFullYear(startDate.getFullYear() + yearsToAdd);

            var dd = String(startDate.getDate() - 1).padStart(2, '0');
            var mm = String(startDate.getMonth() + 1).padStart(2, '0');
            var yyyy = startDate.getFullYear();

            var resultDate = yyyy + '-' + mm + '-' + dd;
            $(sd).val(resultDate);
        }
    </script>
@endsection
