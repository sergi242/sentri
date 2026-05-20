@extends('admin.layouts.app')
@section('title') Nouveau certificat d'hébergement @endsection

@section('styles')
<link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
<style>
.step-card { display: none; }
.step-card.active { display: block; }
.step-nav { display: flex; gap: 8px; margin-bottom: 20px; align-items: center; }
.step-badge {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px;
    background: #e9ecef; color: #6c757d;
    border: 2px solid #dee2e6; flex-shrink: 0;
}
.step-badge.active { background: #1E9FF2; color: #fff; border-color: #1E9FF2; }
.step-badge.done   { background: #28D094; color: #fff; border-color: #28D094; }
.step-label { font-size: 12px; color: #6c757d; margin-top: 4px; text-align: center; }
.step-item { display: flex; flex-direction: column; align-items: center; }
.step-line  { flex: 1; height: 2px; background: #dee2e6; margin-bottom: 16px; }
.step-line.done { background: #28D094; }
.hebergeur-found-card {
    background: #f0fff4; border: 1px solid #28D094;
    border-radius: 8px; padding: 16px; margin-bottom: 16px;
}
.type-btn {
    cursor: pointer; transition: all .2s;
    border: 2px solid #dee2e6 !important;
}
.type-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }
.type-btn.selected { border: 2px solid #1E9FF2 !important; background: #e8f4fd; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h3 class="content-header-title">
                    <i class="la la-building"></i> Nouveau certificat d'hébergement
                </h3>
            </div>
            <div class="content-header-right col-md-3 col-12 text-right">
                <a href="{{ route('certificats-hebergement.index') }}" class="btn btn-secondary btn-sm">
                    <i class="la la-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-md-10 offset-md-1">

                    {{-- Navigation étapes --}}
                    <div class="step-nav">
                        <div class="step-item">
                            <div class="step-badge active" id="badge-1">1</div>
                            <div class="step-label">Hébergeur</div>
                        </div>
                        <div class="step-line" id="line-1-2"></div>
                        <div class="step-item">
                            <div class="step-badge" id="badge-2">2</div>
                            <div class="step-label">Hébergé</div>
                        </div>
                        <div class="step-line" id="line-2-3"></div>
                        <div class="step-item">
                            <div class="step-badge" id="badge-3">3</div>
                            <div class="step-label">Certificat</div>
                        </div>
                        <div class="step-line" id="line-3-4"></div>
                        <div class="step-item">
                            <div class="step-badge" id="badge-4">4</div>
                            <div class="step-label">Confirmation</div>
                        </div>
                    </div>

                    <form action="{{ route('certificats-hebergement.store') }}" method="POST" id="form-certificat" enctype="multipart/form-data">
                    @csrf

                    {{-- ════════ ÉTAPE 1 — HÉBERGEUR ════════ --}}
                    <div class="step-card active card" id="step-1">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="la la-home"></i> Étape 1 — Identification de l'hébergeur</h4>
                        </div>
                        <div class="card-body">

                            <div class="alert alert-info">
                                <strong><i class="la la-question-circle"></i> L'hébergeur a-t-il déjà un code hébergeur ?</strong>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8 offset-md-2">
                                    <div class="input-group">
                                        <input type="text" id="input-code-hebergeur"
                                               class="form-control form-control-lg"
                                               placeholder="SAISIR LE CODE HÉBERGEUR (EX: HEB-241225-00001)"
                                               style="text-transform:uppercase;">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="btn-verifier-code">
                                                <i class="la la-search"></i> Vérifier
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Si l'hébergeur a oublié son code, utilisez la recherche ci-dessous</small>
                                </div>
                            </div>

                            <div id="hebergeur-found-result" class="d-none">
                                <div class="hebergeur-found-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="text-success mb-1"><i class="la la-check-circle"></i> Hébergeur trouvé</h5>
                                            <strong id="heb-found-nom" class="d-block" style="font-size:1.1rem;"></strong>
                                            <span id="heb-found-code" class="badge badge-primary"></span>
                                            <span id="heb-found-type" class="badge badge-secondary ml-1"></span>
                                            <span id="heb-found-tel" class="ml-2 text-muted small"></span>
                                            <small id="heb-found-certs" class="d-block text-muted mt-1"></small>
                                        </div>
                                        <button type="button" class="btn btn-success" id="btn-confirmer-hebergeur">
                                            <i class="la la-check"></i> Utiliser cet hébergeur
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div id="hebergeur-not-found" class="d-none">
                                <div class="alert alert-warning">
                                    <i class="la la-exclamation-triangle"></i> Code non trouvé. Recherchez ou créez un nouvel hébergeur.
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Rechercher ou créer un hébergeur</h5>

                            {{-- Sélection du type --}}
                            <div class="row mb-4" id="type-hebergeur-selector">
                                <div class="col-md-4">
                                    <div class="card type-btn text-center p-3" id="btn-type-Congolais" onclick="selectType('Congolais')">
                                        <i class="la la-flag" style="font-size:2rem; color:#28D094;"></i>
                                        <strong class="d-block mt-2">Congolais</strong>
                                        <small class="text-muted">Citoyen de la République du Congo</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card type-btn text-center p-3" id="btn-type-Etranger" onclick="selectType('Etranger')">
                                        <i class="la la-globe" style="font-size:2rem; color:#1E9FF2;"></i>
                                        <strong class="d-block mt-2">Étranger</strong>
                                        <small class="text-muted">Impétrant dans le système</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card type-btn text-center p-3" id="btn-type-Societe" onclick="selectType('Societe')">
                                        <i class="la la-building" style="font-size:2rem; color:#FF9149;"></i>
                                        <strong class="d-block mt-2">Société</strong>
                                        <small class="text-muted">Employeur enregistré</small>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="hebergeur_type" id="hebergeur_type" value="">
                            <input type="hidden" name="hebergeur_existant_id" id="hebergeur_existant_id">
                            <input type="hidden" name="hebergeur_impetrant_id" id="hebergeur_impetrant_id">
                            <input type="hidden" name="hebergeur_employeur_id" id="hebergeur_employeur_id">

                            {{-- FORMULAIRE CONGOLAIS --}}
                            <div id="form-Congolais" class="d-none">
                                <h6 class="text-muted mb-3">Rechercher un hébergeur congolais existant :</h6>
                                <div class="input-group mb-2">
                                    <input type="text" id="search-congolais" class="form-control" placeholder="Nom, prénom ou téléphone...">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary" id="btn-search-congolais">
                                            <i class="la la-search"></i> Chercher
                                        </button>
                                    </div>
                                </div>
                                <div id="result-search-Congolais" class="mb-3"></div>
                                <button type="button" class="btn btn-outline-success btn-sm mb-3" id="btn-nouveau-congolais" onclick="toggleNouveauForm('congolais')">
                                    <i class="la la-plus"></i> Nouveau hébergeur congolais
                                </button>
                                <div id="nouveau-congolais-form" class="d-none">
                                    <div class="card card-body bg-light">
                                        {{-- Lecteur CNI/Passeport Congolais --}}
                                        <div class="mb-3">
                                            <div class="card card-body" style="background:#f0fff4; border:1px solid #28D094;">
                                                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                                                    <strong><i class="la la-id-card text-success"></i> Lecteur CNI / Passeport :</strong>
                                                    <button type="button" id="btn-lire-passeport-cong" class="btn btn-success btn-sm">
                                                        <i class="la la-id-card"></i> Lire la pi&#232;ce d'identit&#233;
                                                    </button>
                                                    <button type="button" id="btn-restart-lecteur-cong" class="btn btn-warning btn-sm">
                                                        <i class="la la-refresh"></i> R&#233;initialiser
                                                    </button>
                                                    <span id="passport-status-cong"></span>
                                                </div>
                                                <div id="passport-photo-preview-cong" style="display:none; margin-top:8px;">
                                                    <img id="passport-photo-img-cong" src="" style="height:120px; border-radius:6px; border:3px solid #28D094;">
                                                    <br><small class="text-success"><i class="la la-check-circle"></i> <strong>Photo biom&#233;trique depuis la puce</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nom *</label>
                                                    <input type="text" name="heb_nom" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Prénom *</label>
                                                    <input type="text" name="heb_prenom" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Sexe *</label>
                                                    <select name="heb_sexe" class="form-control">
                                                        <option value="">Sélectionner</option>
                                                        <option>Masculin</option>
                                                        <option>Féminin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date de naissance</label>
                                                    <input type="date" name="heb_date_naissance" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Lieu de naissance</label>
                                                    <input type="text" name="heb_lieu_naissance" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Téléphone *</label>
                                                    <input type="text" name="heb_telephone" class="form-control" placeholder="+242...">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="heb_email" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Profession</label>
                                                    <input type="text" name="heb_profession" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Photo de l'hébergeur *</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="heb_photo" id="heb_photo"
                                                               class="custom-file-input" accept="image/*"
                                                               onchange="previewPhoto(this, 'preview-heb-photo')">
                                                        <label class="custom-file-label" for="heb_photo">Choisir une photo...</label>
                                                    </div>
                                                    <div id="preview-heb-photo" class="mt-2 d-none">
                                                        <img src="" alt="Aperçu" style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:2px solid #28D094;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Type de pièce</label>
                                                    <select name="heb_type_piece" class="form-control">
                                                        <option value="">Sélectionner</option>
                                                        <option value="CNI">CNI</option>
                                                        <option value="Passeport">Passeport</option>
                                                        <option value="Permis">Permis de conduire</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Numéro de pièce</label>
                                                    <input type="text" name="heb_numero_piece" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Émission pièce</label>
                                                    <input type="date" name="heb_date_emission_piece" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Expiration pièce</label>
                                                    <input type="date" name="heb_date_expiration_piece" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-12"><hr><h6 class="text-muted">Adresse</h6></div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Département *</label>
                                                    <select id="heb_departements_id" class="form-control">
                                                        <option value="">Sélectionner</option>
                                                        @foreach($departements as $d)
                                                            <option value="{{ $d->id }}">{{ $d->lib_departement }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Arrondissement *</label>
                                                    <select id="heb_arrondissements_id" class="form-control">
                                                        <option value="">Sélectionner</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Quartier *</label>
                                                    <select name="heb_quartiers_id" id="heb_quartiers_id" class="form-control">
                                                        <option value="">Sélectionner</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Avenue / Rue *</label>
                                                    <input type="text" name="heb_avenue_rue" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>N° *</label>
                                                    <input type="text" name="heb_numero_adresse" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- FORMULAIRE ÉTRANGER --}}
                            <div id="form-Etranger" class="d-none">
                                <h6 class="text-muted mb-3">Rechercher l'impétrant hébergeur :</h6>
                                <div class="card card-body bg-light mb-3" style="border-left:3px solid #1E9FF2;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-2">
                                                <label style="font-size:12px;" class="text-muted">Nom</label>
                                                <input type="text" id="etr-search-nom" class="form-control form-control-sm" placeholder="DIALLO...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-2">
                                                <label style="font-size:12px;" class="text-muted">Prénom</label>
                                                <input type="text" id="etr-search-prenom" class="form-control form-control-sm" placeholder="Mamadou...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-2">
                                                <label style="font-size:12px;" class="text-muted">Date de naissance</label>
                                                <input type="date" id="etr-search-dn" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-0">
                                                <label style="font-size:12px;" class="text-muted">Nationalité</label>
                                                <select id="etr-search-nat" class="form-control form-control-sm">
                                                    <option value="">Toutes nationalités</option>
                                                    @foreach($pays as $p)
                                                        <option value="{{ $p->id }}">{{ $p->lib_pays }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <button type="button" class="btn btn-info btn-sm btn-block" id="btn-search-etranger">
                                                <i class="la la-search"></i> Rechercher
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="result-search-Etranger" class="mb-3"></div>
                                <button type="button" class="btn btn-outline-success btn-sm mb-3" id="btn-nouveau-etranger" onclick="toggleNouveauForm('etranger')">
                                    <i class="la la-plus"></i> Créer un nouvel impétrant hébergeur
                                </button>

                                {{-- Zone lecteur passeport HÉBERGEUR ÉTRANGER --}}
                                <div id="lecteur-passeport-etr-zone" class="mb-3 d-none">
                                    <div class="card card-body" style="background:#f0f8ff; border:1px solid #1E9FF2;">
                                        <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                                            <strong><i class="la la-id-card text-primary"></i> Lecteur passeport :</strong>
                                            <button type="button" id="btn-lire-passeport-etr" class="btn btn-primary btn-sm">
                                                <i class="la la-id-card"></i> Lire le passeport
                                            </button>
                                            <button type="button" id="btn-restart-lecteur-etr" class="btn btn-warning btn-sm">
                                                <i class="la la-refresh"></i> Réinitialiser
                                            </button>
                                            <span id="passport-status-etr"></span>
                                        </div>
                                        <div id="passport-photo-preview-etr" style="display:none; margin-top:8px;">
                                            <img id="passport-photo-img-etr" src="" style="height:120px; border-radius:6px; border:3px solid #28D094;">
                                            <br><small class="text-success"><i class="la la-check-circle"></i> <strong>Photo biométrique depuis la puce</strong></small>
                                        </div>
                                    </div>
                                </div>

                                <div id="nouveau-etranger-form" class="d-none">
                                    <div class="card card-body bg-light">
                                        <p class="text-info small mb-3">
                                            <i class="la la-info-circle"></i>
                                            Cet impétrant sera créé dans le système et recevra automatiquement un code hébergeur.
                                        </p>
                                        <div class="row">
                                            <div class="col-12"><h6 class="text-muted border-bottom pb-1 mb-2">Identité</h6></div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Nom *</label>
                                                    <input type="text" name="heb_etr_nom" class="form-control"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Prénom *</label>
                                                    <input type="text" name="heb_etr_prenom" class="form-control"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label>Sexe *</label>
                                                    <select name="heb_etr_sexe" class="form-control" id="heb_etr_sexe">
                                                        <option value="">Sélectionner</option>
                                                        <option>Masculin</option>
                                                        <option>Féminin</option>
                                                    </select></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label>Date de naissance *</label>
                                                    <input type="date" name="heb_etr_date_naissance" class="form-control"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label>Lieu de naissance</label>
                                                    <input type="text" name="heb_etr_lieu_naissance" class="form-control"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label>Nationalité *</label>
                                                    <select name="heb_etr_nationalites_id" class="form-control">
                                                        <option value="">Sélectionner</option>
                                                        @foreach($pays as $p)
                                                            <option value="{{ $p->id }}">{{ $p->lib_pays }}</option>
                                                        @endforeach
                                                    </select></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label>État civil</label>
                                                    <select name="heb_etr_etat_civil" class="form-control" id="heb_etr_etat_civil">
                                                        <option value="Célibataire">Célibataire</option>
                                                        <option value="Marié(e)">Marié(e)</option>
                                                        <option value="Divorcé(e)">Divorcé(e)</option>
                                                        <option value="Veuf(-ve)">Veuf(-ve)</option>
                                                    </select></div>
                                            </div>
                                            <div class="col-md-4" id="heb_etr_conjoint_wrap" style="display:none;">
                                                <div class="form-group"><label>Nom du conjoint</label>
                                                    <input type="text" name="heb_etr_nom_conjoint" class="form-control"></div>
                                            </div>

                                            <div class="col-12"><h6 class="text-muted border-bottom pb-1 mb-2 mt-2">Parents</h6></div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Nom du père</label>
                                                    <input type="text" name="heb_etr_nom_pere" class="form-control"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Prénom du père</label>
                                                    <input type="text" name="heb_etr_prenom_pere" class="form-control"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Nom de la mère</label>
                                                    <input type="text" name="heb_etr_nom_mere" class="form-control"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Prénom de la mère</label>
                                                    <input type="text" name="heb_etr_prenom_mere" class="form-control"></div>
                                            </div>

                                            <div class="col-12"><h6 class="text-muted border-bottom pb-1 mb-2 mt-2">Contact</h6></div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Téléphone</label>
                                                    <input type="text" name="heb_etr_telephone" class="form-control" placeholder="+242..."></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Email</label>
                                                    <input type="email" name="heb_etr_email" class="form-control"></div>
                                            </div>

                                            <div class="col-12"><h6 class="text-muted border-bottom pb-1 mb-2 mt-2">Profession</h6></div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Profession</label>
                                                    <input type="text" name="heb_etr_profession" class="form-control"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Employeur</label>
                                                    <input type="text" name="heb_etr_employeur" class="form-control"></div>
                                            </div>

                                            <div class="col-12"><h6 class="text-muted border-bottom pb-1 mb-2 mt-2">Passeport</h6></div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label>Numéro de passeport</label>
                                                    <input type="text" name="heb_etr_numero_passeport" class="form-control"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label>Date d'émission</label>
                                                    <input type="date" name="heb_etr_date_emission_passeport" class="form-control"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label>Date d'expiration</label>
                                                    <input type="date" name="heb_etr_date_expiration_passeport" class="form-control"></div>
                                            </div>

                                            <div class="col-12"><h6 class="text-muted border-bottom pb-1 mb-2 mt-2">Photo</h6></div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Photo de l'hébergeur *</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="heb_etr_photo" id="heb_etr_photo"
                                                               class="custom-file-input" accept="image/*"
                                                               onchange="previewPhoto(this, 'preview-heb-etr-photo')">
                                                        <label class="custom-file-label" for="heb_etr_photo">Choisir une photo...</label>
                                                    </div>
                                                    <div id="preview-heb-etr-photo" class="mt-2 d-none">
                                                        <img src="" alt="Aperçu" style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:2px solid #1E9FF2;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="passport-photo-preview-etr-form" style="display:none; margin-top:24px;">
                                                    <img id="passport-photo-img-etr-form" src="" style="height:100px; border-radius:6px; border:3px solid #28D094;">
                                                    <br><small class="text-success"><i class="la la-check-circle"></i> <strong>Photo biométrique depuis la puce</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- FORMULAIRE SOCIÉTÉ --}}
                            <div id="form-Societe" class="d-none">
                                <h6 class="text-muted mb-3">Rechercher la société hébergeuse :</h6>
                                <div class="input-group mb-2">
                                    <input type="text" id="search-societe" class="form-control" placeholder="Nom de la société...">
                                </div>
                                <div id="result-search-Societe" class="mb-3"></div>
                                <button type="button" class="btn btn-outline-success btn-sm mb-3" id="btn-nouveau-societe" onclick="toggleNouveauForm('societe')">
                                    <i class="la la-plus"></i> Créer une nouvelle société hébergeuse
                                </button>
                                <div id="nouveau-societe-form" class="d-none">
                                    <div class="card card-body bg-light">
                                        <p class="text-info small mb-3">
                                            <i class="la la-info-circle"></i>
                                            Cette société sera créée dans la table employeurs et recevra un code hébergeur.
                                        </p>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group"><label>Raison sociale / Nom *</label>
                                                    <input type="text" name="heb_soc_nom" class="form-control" placeholder="Nom de la société"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Téléphone *</label>
                                                    <input type="text" name="heb_soc_telephone" class="form-control" placeholder="+242..."></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Email</label>
                                                    <input type="email" name="heb_soc_email" class="form-control"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Type</label>
                                                    <select name="heb_soc_type" class="form-control">
                                                        <option value="Entreprise">Entreprise</option>
                                                        <option value="ONG">ONG</option>
                                                        <option value="Ambassade">Ambassade</option>
                                                        <option value="Diplomate">Diplomate</option>
                                                        <option value="Autre">Autre</option>
                                                    </select></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Registre de commerce / N° identifiant</label>
                                                    <input type="text" name="heb_soc_registre" class="form-control"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group"><label>Adresse physique *</label>
                                                    <input type="text" name="heb_soc_adresse" class="form-control" placeholder="Adresse complète"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Hébergeur sélectionné --}}
                            <div id="hebergeur-selectionne" class="d-none mt-3">
                                <div class="alert alert-success">
                                    <i class="la la-check-circle"></i>
                                    <strong>Hébergeur sélectionné :</strong>
                                    <span id="hebergeur-selectionne-nom"></span>
                                    <span id="hebergeur-selectionne-code" class="badge badge-primary ml-2"></span>
                                    <button type="button" class="btn btn-sm btn-outline-danger ml-2" id="btn-changer-hebergeur">Changer</button>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary btn-lg" id="btn-step1-next" disabled>
                                Suivant — Hébergé <i class="la la-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ════════ ÉTAPE 2 — HÉBERGÉ ════════ --}}
                    <div class="step-card card" id="step-2">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0"><i class="la la-user"></i> Étape 2 — Identification de l'hébergé</h4>
                        </div>
                        <div class="card-body">

                            <div class="alert alert-info">
                                <strong><i class="la la-question-circle"></i> L'hébergé est-il déjà dans le système ?</strong>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8 offset-md-2">
                                    <div class="input-group">
                                        <input type="text" id="search-heberge" class="form-control form-control-lg" placeholder="Rechercher par nom ou prénom...">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-info" id="btn-search-heberge">
                                                <i class="la la-search"></i> Rechercher
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="result-heberge" class="mb-3"></div>
                            <input type="hidden" name="heberge_impetrant_id" id="heberge_impetrant_id">

                            <div id="heberge-selectionne" class="d-none">
                                <div class="alert alert-success">
                                    <i class="la la-check-circle"></i>
                                    <strong>Hébergé sélectionné :</strong>
                                    <span id="heberge-selectionne-nom"></span>
                                    <button type="button" class="btn btn-sm btn-outline-danger ml-2" id="btn-changer-heberge">Changer</button>
                                </div>
                            </div>

                            <hr>

                            <button type="button" class="btn btn-outline-success btn-sm" id="btn-nouveau-heberge" onclick="toggleNouveauForm('heberge')">
                                <i class="la la-plus"></i> L'hébergé n'est pas dans le système
                            </button>

                            {{-- Zone lecteur passeport HÉBERGÉ --}}
                            <div id="lecteur-passeport-heb-zone" class="mt-3 d-none">
                                <div class="card card-body" style="background:#f0f8ff; border:1px solid #1E9FF2;">
                                    <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                                        <strong><i class="la la-id-card text-primary"></i> Lecteur passeport :</strong>
                                        <button type="button" id="btn-lire-passeport-heb" class="btn btn-primary btn-sm">
                                            <i class="la la-id-card"></i> Lire le passeport
                                        </button>
                                        <button type="button" id="btn-restart-lecteur-heb" class="btn btn-warning btn-sm">
                                            <i class="la la-refresh"></i> Réinitialiser
                                        </button>
                                        <span id="passport-status-heb"></span>
                                    </div>
                                    <div id="passport-photo-preview-heb" style="display:none; margin-top:8px;">
                                        <img id="passport-photo-img-heb" src="" style="height:120px; border-radius:6px; border:3px solid #28D094;">
                                        <br><small class="text-success"><i class="la la-check-circle"></i> <strong>Photo biométrique depuis la puce</strong></small>
                                    </div>
                                </div>
                            </div>

                            <div id="nouveau-heberge-form" class="d-none mt-3">
                                <div class="card card-body bg-light">
                                    <div class="row">
                                        <div class="col-12"><h6 class="text-muted border-bottom pb-1 mb-2">Identité</h6></div>
                                        <div class="col-md-6">
                                            <div class="form-group"><label>Nom *</label>
                                                <input type="text" id="heberge_nom" name="heberge_nom" class="form-control"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"><label>Prénom *</label>
                                                <input type="text" id="heberge_prenom" name="heberge_prenom" class="form-control"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label>Sexe *</label>
                                                <select id="heberge_sexe" name="heberge_sexe" class="form-control">
                                                    <option value="">Sélectionner</option>
                                                    <option>Masculin</option>
                                                    <option>Féminin</option>
                                                </select></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label>Date de naissance *</label>
                                                <input type="date" id="heberge_date_naissance" name="heberge_date_naissance" class="form-control"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label>Lieu de naissance</label>
                                                <input type="text" id="heberge_lieu_naissance" name="heberge_lieu_naissance" class="form-control"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"><label>Nationalité *</label>
                                                <select id="heberge_nationalites_id" name="heberge_nationalites_id" class="form-control">
                                                    <option value="">Sélectionner</option>
                                                    @foreach($pays as $p)
                                                        <option value="{{ $p->id }}">{{ $p->lib_pays }}</option>
                                                    @endforeach
                                                </select></div>
                                        </div>

                                        <div class="col-12"><h6 class="text-muted border-bottom pb-1 mb-2 mt-2">Passeport</h6></div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label>Numéro de passeport</label>
                                                <input type="text" id="heberge_num_passeport" name="heberge_numero_passeport" class="form-control"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label>Date d'émission</label>
                                                <input type="date" id="heberge_date_emission_passeport" name="heberge_date_emission_passeport" class="form-control"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label>Date d'expiration</label>
                                                <input type="date" id="heberge_date_expiration_passeport" name="heberge_date_expiration_passeport" class="form-control"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="passport-photo-preview-heb-form" style="display:none; margin-top:8px;">
                                                <img id="passport-photo-img-heb-form" src="" style="height:100px; border-radius:6px; border:3px solid #28D094;">
                                                <br><small class="text-success"><i class="la la-check-circle"></i> <strong>Photo biométrique depuis la puce</strong></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary btn-lg" id="btn-step2-prev">
                                <i class="la la-arrow-left"></i> Précédent
                            </button>
                            <button type="button" class="btn btn-info btn-lg" id="btn-step2-next">
                                Suivant — Certificat <i class="la la-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ════════ ÉTAPE 3 — CERTIFICAT ════════ --}}
                    <div class="step-card card" id="step-3">
                        <div class="card-header bg-warning text-white">
                            <h4 class="mb-0"><i class="la la-file-text"></i> Étape 3 — Informations du certificat</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date d'arrivée prévue *</label>
                                        <input type="date" name="date_arrivee_prevue" id="date_arrivee_prevue" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date de départ prévue *</label>
                                        <input type="date" name="date_depart_prevue" id="date_depart_prevue" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-secondary py-2" id="duree-display" style="display:none;">
                                        <i class="la la-clock-o"></i> Durée : <strong id="duree-valeur"></strong> jours
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type de relation *</label>
                                        <select name="type_relation" class="form-control">
                                            <option value="">Sélectionner</option>
                                            <option value="Famille">Famille</option>
                                            <option value="Ami">Ami</option>
                                            <option value="Professionnel">Professionnel</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Précision relation</label>
                                        <input type="text" name="precision_relation" class="form-control" placeholder="Ex: cousin, collègue...">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Motif du séjour</label>
                                        <textarea name="motif_sejour" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary btn-lg" id="btn-step3-prev">
                                <i class="la la-arrow-left"></i> Précédent
                            </button>
                            <button type="button" class="btn btn-warning btn-lg" id="btn-step3-next">
                                Confirmation <i class="la la-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ════════ ÉTAPE 4 — CONFIRMATION ════════ --}}
                    <div class="step-card card" id="step-4">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0"><i class="la la-check-circle"></i> Étape 4 — Confirmation</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white py-2">
                                            <strong><i class="la la-home"></i> Hébergeur</strong>
                                        </div>
                                        <div class="card-body py-3">
                                            <strong id="recap-hebergeur-nom" class="d-block" style="font-size:1.1rem;"></strong>
                                            <span id="recap-hebergeur-code" class="badge badge-primary mt-1"></span>
                                            <span id="recap-hebergeur-type" class="badge badge-secondary ml-1"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white py-2">
                                            <strong><i class="la la-user"></i> Hébergé</strong>
                                        </div>
                                        <div class="card-body py-3">
                                            <strong id="recap-heberge-nom" class="d-block" style="font-size:1.1rem;"></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-white py-2">
                                            <strong><i class="la la-calendar"></i> Séjour</strong>
                                        </div>
                                        <div class="card-body py-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <small class="text-muted">Arrivée</small>
                                                    <strong id="recap-arrivee" class="d-block"></strong>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted">Départ</small>
                                                    <strong id="recap-depart" class="d-block"></strong>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted">Durée</small>
                                                    <strong id="recap-duree" class="d-block"></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary btn-lg" id="btn-step4-prev">
                                <i class="la la-arrow-left"></i> Précédent
                            </button>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="la la-save"></i> Enregistrer le certificat
                            </button>
                        </div>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- !! Select2 DOIT être dans @section('content') pour être après jQuery !! --}}
<script src="{{asset('res/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>

{{-- Modal Passeport Existant --}}
<div class="modal fade" id="modal-passeport-existant" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#FF4961; color:white;">
                <h5 class="modal-title"><i class="la la-exclamation-triangle"></i> Passeport déjà enregistré</h5>
                <button type="button" class="close" data-dismiss="modal" style="color:white;"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="modal-passeport-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="la la-times"></i> Fermer</button>
                <a href="#" id="btn-voir-demande" class="btn btn-primary" target="_blank"><i class="la la-eye"></i> Voir la demande</a>
                <a href="#" id="btn-renouveler" class="btn btn-success"><i class="la la-refresh"></i> Renouveler</a>
            </div>
        </div>
    </div>
</div>


{{-- !! Toutes les fonctions globales déclarées ICI (hors push, hors ready) !! --}}
<script>

// ══════════════════════════════════════════════════════════════
// FONCTIONS GLOBALES — appelées via onclick="" dans le HTML
// Doivent absolument être HORS de $(document).ready()
// ══════════════════════════════════════════════════════════════

var hebergeurData = {};
var hebergeData   = {};
var READER_URL    = 'http://127.0.0.1:8085';

function previewPhoto(input, previewId) {
    var preview = document.getElementById(previewId);
    if (!preview || !input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        preview.classList.remove('d-none');
        preview.querySelector('img').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
    var label = input.nextElementSibling;
    if (label) label.textContent = input.files[0].name;
}

function goToStep(step) {
    document.querySelectorAll('.step-card').forEach(function(c) { c.classList.remove('active'); });
    document.getElementById('step-' + step).classList.add('active');
    for (var i = 1; i <= 4; i++) {
        var badge = document.getElementById('badge-' + i);
        badge.classList.remove('active', 'done');
        if (i < step)      badge.classList.add('done');
        else if (i === step) badge.classList.add('active');
    }
    for (var j = 1; j <= 3; j++) {
        var line = document.getElementById('line-' + j + '-' + (j + 1));
        if (line) line.classList.toggle('done', j < step);
    }
}

function selectType(type) {
    document.getElementById('hebergeur_type').value = type;

    ['Congolais', 'Etranger', 'Societe'].forEach(function(t) {
        var btn  = document.getElementById('btn-type-' + t);
        var form = document.getElementById('form-' + t);
        if (btn)  btn.classList.remove('selected');
        if (form) form.classList.add('d-none');
    });

    document.getElementById('btn-type-' + type).classList.add('selected');
    document.getElementById('form-' + type).classList.remove('d-none');

    hebergeurData = {};
    document.getElementById('hebergeur-selectionne').classList.add('d-none');
    document.getElementById('hebergeur_existant_id').value  = '';
    document.getElementById('hebergeur_impetrant_id').value = '';
    document.getElementById('hebergeur_employeur_id').value = '';
    document.getElementById('btn-step1-next').disabled = true;

    var lecteurZone = document.getElementById('lecteur-passeport-etr-zone');
    if (lecteurZone) {
        if (type === 'Etranger') {
            lecteurZone.classList.remove('d-none');
            checkLecteurStatus('etr');
        } else {
            lecteurZone.classList.add('d-none');
        }
    }
}

function confirmerHebergeur(id, nom, code, type) {
    hebergeurData = { id: id, nom: nom, code: code, type: type };
    document.getElementById('hebergeur-selectionne').classList.remove('d-none');
    document.getElementById('hebergeur-selectionne-nom').textContent  = nom;
    document.getElementById('hebergeur-selectionne-code').textContent = code || '';
    document.getElementById('btn-step1-next').disabled = false;
}

function renderResultList(data, containerId, sourceType) {
    var container = document.getElementById(containerId);
    if (!data || !data.length) {
        container.innerHTML = '<p class="text-muted small">Aucun résultat.</p>';
        return;
    }
    var html = '<div class="list-group">';
    data.forEach(function(item) {
        html += '<button type="button" class="list-group-item list-group-item-action"'
             + ' onclick="selectHebergeurFromList(' + item.id + ',\''
             + item.label.replace(/'/g, "\\'") + '\',\''
             + (item.code || '') + '\',\'' + item.type + '\',\'' + sourceType + '\')">'
             + '<strong>' + item.label + '</strong>'
             + (item.code ? '<span class="badge badge-primary ml-2">' + item.code + '</span>' : '')
             + '</button>';
    });
    html += '</div>';
    container.innerHTML = html;
}

function selectHebergeurFromList(id, label, code, type, source) {
    if (source === 'etranger')     document.getElementById('hebergeur_impetrant_id').value = id;
    else if (source === 'societe') document.getElementById('hebergeur_employeur_id').value = id;
    else                           document.getElementById('hebergeur_existant_id').value  = id;
    confirmerHebergeur(id, label, code, type);
    ['result-search-Congolais', 'result-search-Etranger', 'result-search-Societe'].forEach(function(rid) {
        var el = document.getElementById(rid); if (el) el.innerHTML = '';
    });
}

function selectHeberge(id, nom, dn, nat) {
    hebergeData = { id: id, nom: nom };
    document.getElementById('heberge_impetrant_id').value = id;
    document.getElementById('heberge-selectionne').classList.remove('d-none');
    document.getElementById('heberge-selectionne-nom').textContent = nom + ' — ' + dn + ' — ' + nat;
    document.getElementById('result-heberge').innerHTML = '';
    document.getElementById('nouveau-heberge-form').classList.add('d-none');
    document.getElementById('lecteur-passeport-heb-zone').classList.add('d-none');
}

function setStatus(spanId, type, html) {
    var el = document.getElementById(spanId);
    if (!el) return;
    el.className = 'text-' + type;
    el.innerHTML = html;
}

function checkLecteurStatus(who) {
    var spanId = 'passport-status-' + who;
    $.ajax({
        url: READER_URL + '/status', method: 'GET', timeout: 2000,
        success: function() { setStatus(spanId, 'success', '<i class="la la-check-circle"></i> Lecteur connecté'); },
        error:   function() { setStatus(spanId, 'warning', '<i class="la la-exclamation-triangle"></i> Service non démarré'); }
    });
}

function toggleNouveauForm(type) {
    var formIds = {
        'congolais': 'nouveau-congolais-form',
        'etranger':  'nouveau-etranger-form',
        'societe':   'nouveau-societe-form',
        'heberge':   'nouveau-heberge-form'
    };
    var formId = formIds[type];
    if (!formId) return;

    var form = document.getElementById(formId);
    var estFerme = form.classList.contains('d-none');

    // Toggle le formulaire
    form.classList.toggle('d-none');

    if (type === 'congolais') {
        if (estFerme) {
            checkLecteurStatus('cong');
            document.getElementById('hebergeur_existant_id').value = '';
            confirmerHebergeur(null, 'Nouvel hébergeur congolais', '', 'Congolais');
        }
    } else if (type === 'etranger') {
        var lz = document.getElementById('lecteur-passeport-etr-zone');
        if (lz) lz.classList.remove('d-none');
        checkLecteurStatus('etr');
        if (estFerme) {
            document.getElementById('hebergeur_impetrant_id').value = '';
            confirmerHebergeur(null, 'Nouvel impétrant hébergeur', '', 'Etranger');
        }
    } else if (type === 'societe') {
        if (estFerme) {
            document.getElementById('hebergeur_employeur_id').value = '';
            confirmerHebergeur(null, 'Nouvelle société hébergeuse', '', 'Societe');
        }
    } else if (type === 'heberge') {
        var lzh = document.getElementById('lecteur-passeport-heb-zone');
        var hebergeSelectionne = document.getElementById('heberge-selectionne');
        if (hebergeSelectionne) hebergeSelectionne.classList.add('d-none');
        document.getElementById('heberge_impetrant_id').value = '';
        if (lzh) {
            if (estFerme) lzh.classList.remove('d-none');
            else          lzh.classList.add('d-none');
        }
        if (estFerme) checkLecteurStatus('heb');
    }
}

function afficherModalPasseport(d) {
    var couleurs = {
        "En attente d'approbation": '#FF9149', "Approuvée": '#28D094',
        "Rejetée": '#FF4961', "Livrée": '#1E9FF2', "Envoyée au contentieux": '#FF4961'
    };
    var couleur = couleurs[d.statut_demande] || '#666';
    var photo = d.photo
        ? '<img src="/app/' + d.photo + '" style="height:100px;border-radius:6px;">'
        : '<div style="height:100px;width:80px;background:#eee;border-radius:6px;display:flex;align-items:center;justify-content:center;"><i class="la la-user" style="font-size:2em;color:#999;"></i></div>';
    $('#modal-passeport-body').html(
        '<div class="row"><div class="col-md-2 text-center">' + photo + '</div>'
        + '<div class="col-md-10"><table class="table table-bordered table-sm">'
        + '<tr><th>Impétrant</th><td><strong>' + (d.nom||'') + ' ' + (d.prenom||'') + '</strong></td></tr>'
        + '<tr><th>Nationalité</th><td>' + (d.nationalite||'-') + '</td></tr>'
        + '<tr><th>N° passeport</th><td><strong>' + (d.numero_document||'') + '</strong></td></tr>'
        + '<tr><th>Type demande</th><td>' + (d.type_demande||'-') + '</td></tr>'
        + '<tr><th>Statut</th><td><span style="color:' + couleur + ';font-weight:bold;">' + (d.statut_demande||'-') + '</span></td></tr>'
        + '<tr><th>UUID</th><td><code>' + (d.uuid||'-') + '</code></td></tr>'
        + '</table></div></div>'
        + '<div class="alert alert-warning mt-1 mb-0"><i class="la la-exclamation-triangle"></i> Ce passeport est déjà associé à une demande DMCE.</div>'
    );
    $('#btn-voir-demande').attr('href', '/demandes/' + d.demande_id);
    $('#btn-renouveler').attr('href', '/demandes/' + d.demande_id + '/renouvellement');
    $('#modal-passeport-existant').modal('show');
}

</script>

<script>
$(function() {

    // Vérifier statut lecteur au chargement
    checkLecteurStatus('etr');

    // ══════════════════════════════════════════════════════
    // LECTEUR PASSEPORT — HÉBERGEUR ÉTRANGER
    // ══════════════════════════════════════════════════════

    $(document).on('click', '#btn-lire-passeport-etr', function() {
        $(this).prop('disabled', true);
        setStatus('passport-status-etr', 'info', '<i class="la la-spinner la-spin"></i> Lecture en cours...');
        $('#passport-photo-preview-etr').hide();
        $.ajax({
            url: READER_URL + '/read', method: 'GET', timeout: 120000,
            success: function(data) {
                $('#btn-lire-passeport-etr').prop('disabled', false);
                if (data.status === 'success') remplirHebergeurEtranger(data);
                else setStatus('passport-status-etr', 'warning', '<i class="la la-exclamation-triangle"></i> ' + (data.message || 'Erreur'));
            },
            error: function() {
                setStatus('passport-status-etr', 'danger', '<i class="la la-times-circle"></i> Service non disponible');
                $('#btn-lire-passeport-etr').prop('disabled', false);
            }
        });
    });

    $(document).on('click', '#btn-restart-lecteur-etr', function() {
        $(this).prop('disabled', true);
        setStatus('passport-status-etr', 'info', '<i class="la la-refresh la-spin"></i> Réinitialisation...');
        $.ajax({
            url: READER_URL + '/restart', method: 'GET', timeout: 10000,
            success: function() {
                setTimeout(function() {
                    setStatus('passport-status-etr', 'success', '<i class="la la-check-circle"></i> Réinitialisé !');
                    $('#btn-restart-lecteur-etr, #btn-lire-passeport-etr').prop('disabled', false);
                }, 3000);
            },
            error: function() {
                setStatus('passport-status-etr', 'danger', '<i class="la la-times-circle"></i> Erreur');
                $('#btn-restart-lecteur-etr').prop('disabled', false);
            }
        });
    });

    function remplirHebergeurEtranger(data) {
        console.log("LECTEUR DATA:", JSON.stringify(data));
        if ($('#nouveau-etranger-form').hasClass('d-none')) $('#btn-nouveau-etranger').trigger('click');
        if (data.nom)            $('input[name="heb_etr_nom"]').val(data.nom);
        if (data.prenoms)        $('input[name="heb_etr_prenom"]').val(data.prenoms);
        if (data.naissance)      $('input[name="heb_etr_date_naissance"]').val(data.naissance);
        if (data.lieu_naissance) $('input[name="heb_etr_lieu_naissance"]').val(data.lieu_naissance);
        if (data.num_doc)        $('input[name="heb_etr_numero_passeport"]').val(data.num_doc);
        if (data.expiration)     $('input[name="heb_etr_date_expiration_passeport"]').val(data.expiration);
        if (data.date_emission)  $('input[name="heb_etr_date_emission_passeport"]').val(data.date_emission);
        if (data.sexe) $('select[name="heb_etr_sexe"]').val(data.sexe === 'M' || data.sexe === 'Male' ? 'Masculin' : 'Féminin').trigger('change');
        if (data.nationalite) {
            $.get('/api/passport/pays', function(pays) {
                var opt = pays[data.nationalite.toUpperCase()];
                if (opt) $('select[name="heb_etr_nationalites_id"]').val(opt.id).trigger('change');
            });
        }
        if (data.photo_base64 && data.photo_base64.length > 100) {
            var src = 'data:image/jpeg;base64,' + data.photo_base64;
            $('#passport-photo-img-etr').attr('src', src);
            $('#passport-photo-preview-etr').show();
            $('#passport-photo-img-etr-form').attr('src', src);
            $('#passport-photo-preview-etr-form').show();
            try {
                var b = atob(data.photo_base64), ab = new ArrayBuffer(b.length), ia = new Uint8Array(ab);
                for (var i = 0; i < b.length; i++) ia[i] = b.charCodeAt(i);
                var dt = new DataTransfer();
                dt.items.add(new File([new Blob([ab], {type:'image/jpeg'})], 'passport_photo.jpg', {type:'image/jpeg'}));
                var inp = document.getElementById('heb_etr_photo');
                if (inp) { inp.files = dt.files; previewPhoto(inp, 'preview-heb-etr-photo'); }
            } catch(e) {}
            setStatus('passport-status-etr', 'success', '<i class="la la-check-circle"></i> <strong>Passeport lu !</strong> <small>(' + (data.source_photo||'') + ')</small>');
        } else {
            setStatus('passport-status-etr', 'success', '<i class="la la-check-circle"></i> Données lues.');
        }
        toastr.success('Formulaire hébergeur rempli !', 'Lecteur passeport');
        // ── Sauvegarde impetrant_documents (hébergeur étranger) ──────────────
        var impetrantIdEtr = $('#hebergeur_impetrant_id').val();
        if (data.num_doc && impetrantIdEtr) {
            $.ajax({
                url: '/api/impetrants/store-document',
                method: 'POST',
                data: {
                    _token:           $('meta[name="csrf-token"]').attr('content'),
                    impetrants_id:    impetrantIdEtr,
                    type_document:    (data.type_doc && data.type_doc.toUpperCase() === 'P') ? 'Passeport' : 'Document de voyage',
                    numero_document:  data.num_doc,
                    date_delivrance:  data.date_emission  || '',
                    date_expiration:  data.expiration     || '',
                    mrz:              data.mrz            || '',
                    source:           'lecteur'
                },
                success: function(r) { if (r.saved) console.log('[DMCE] Doc étranger sauvegardé:', r.id); },
                error:   function()  { console.warn('[DMCE] storeDocument hébergeur étranger échoué'); }
            });
        }
        if (data.num_doc) {
            $.get('/api/passport/check/' + encodeURIComponent(data.num_doc), function(res) {
                if (res.found) afficherModalPasseport(res.demande);
            });
        }
    }

    // ══════════════════════════════════════════════════════
    // LECTEUR PASSEPORT — HÉBERGÉ (ÉTAPE 2)
    // ══════════════════════════════════════════════════════

    // btn-nouveau-heberge géré via onclick="toggleNouveauForm('heberge')"

    $(document).on('click', '#btn-lire-passeport-heb', function() {
        $(this).prop('disabled', true);
        setStatus('passport-status-heb', 'info', '<i class="la la-spinner la-spin"></i> Lecture en cours...');
        $('#passport-photo-preview-heb').hide();
        $.ajax({
            url: READER_URL + '/read', method: 'GET', timeout: 120000,
            success: function(data) {
                $('#btn-lire-passeport-heb').prop('disabled', false);
                if (data.status === 'success') remplirHeberge(data);
                else setStatus('passport-status-heb', 'warning', '<i class="la la-exclamation-triangle"></i> ' + (data.message || 'Erreur'));
            },
            error: function() {
                setStatus('passport-status-heb', 'danger', '<i class="la la-times-circle"></i> Service non disponible');
                $('#btn-lire-passeport-heb').prop('disabled', false);
            }
        });
    });

    $(document).on('click', '#btn-restart-lecteur-heb', function() {
        $(this).prop('disabled', true);
        setStatus('passport-status-heb', 'info', '<i class="la la-refresh la-spin"></i> Réinitialisation...');
        $.ajax({
            url: READER_URL + '/restart', method: 'GET', timeout: 10000,
            success: function() {
                setTimeout(function() {
                    setStatus('passport-status-heb', 'success', '<i class="la la-check-circle"></i> Réinitialisé !');
                    $('#btn-restart-lecteur-heb, #btn-lire-passeport-heb').prop('disabled', false);
                }, 3000);
            },
            error: function() {
                setStatus('passport-status-heb', 'danger', '<i class="la la-times-circle"></i> Erreur');
                $('#btn-restart-lecteur-heb').prop('disabled', false);
            }
        });
    });

    function remplirHeberge(data) {
        if (data.nom)            $('#heberge_nom').val(data.nom);
        if (data.prenoms)        $('#heberge_prenom').val(data.prenoms);
        if (data.naissance)      $('#heberge_date_naissance').val(data.naissance);
        if (data.lieu_naissance) $('#heberge_lieu_naissance').val(data.lieu_naissance);
        if (data.num_doc)        $('#heberge_num_passeport').val(data.num_doc);
        if (data.expiration)     $('#heberge_date_expiration_passeport').val(data.expiration);
        if (data.date_emission)  $('#heberge_date_emission_passeport').val(data.date_emission);
        if (data.sexe) $('#heberge_sexe').val(data.sexe === 'M' || data.sexe === 'Male' ? 'Masculin' : 'Féminin').trigger('change');
        if (data.nationalite) {
            $.get('/api/passport/pays', function(pays) {
                var opt = pays[data.nationalite.toUpperCase()];
                if (opt) $('#heberge_nationalites_id').val(opt.id).trigger('change');
            });
        }
        if (data.photo_base64 && data.photo_base64.length > 100) {
            var src = 'data:image/jpeg;base64,' + data.photo_base64;
            $('#passport-photo-img-heb').attr('src', src);
            $('#passport-photo-preview-heb').show();
            $('#passport-photo-img-heb-form').attr('src', src);
            $('#passport-photo-preview-heb-form').show();
            setStatus('passport-status-heb', 'success', '<i class="la la-check-circle"></i> <strong>Passeport lu !</strong> <small>(' + (data.source_photo||'') + ')</small>');
        } else {
            setStatus('passport-status-heb', 'success', '<i class="la la-check-circle"></i> Données lues.');
        }
        toastr.success('Formulaire hébergé rempli !', 'Lecteur passeport');
        // ── Sauvegarde impetrant_documents (hébergé) ─────────────────────────
        var impetrantIdHeb = $('#heberge_impetrant_id').val();
        if (data.num_doc && impetrantIdHeb) {
            $.ajax({
                url: '/api/impetrants/store-document',
                method: 'POST',
                data: {
                    _token:           $('meta[name="csrf-token"]').attr('content'),
                    impetrants_id:    impetrantIdHeb,
                    type_document:    (data.type_doc && data.type_doc.toUpperCase() === 'P') ? 'Passeport' : 'Document de voyage',
                    numero_document:  data.num_doc,
                    date_delivrance:  data.date_emission  || '',
                    date_expiration:  data.expiration     || '',
                    mrz:              data.mrz            || '',
                    source:           'lecteur'
                },
                success: function(r) { if (r.saved) console.log('[DMCE] Doc hébergé sauvegardé:', r.id); },
                error:   function()  { console.warn('[DMCE] storeDocument hébergé échoué'); }
            });
        }
    }

    // ══════════════════════════════════════════════════════
    // VÉRIFICATION MANUELLE NUMÉRO PASSEPORT
    // ══════════════════════════════════════════════════════

    var checkTimer = null;
    $(document).on('input blur', 'input[name="heb_etr_numero_passeport"]', function() {
        var numero = $(this).val().trim();
        if (numero.length < 5) return;
        clearTimeout(checkTimer);
        checkTimer = setTimeout(function() {
            $.get('/api/passport/check/' + encodeURIComponent(numero), function(data) {
                if (data.found) afficherModalPasseport(data.demande);
            });
        }, 600);
    });

    // ══════════════════════════════════════════════════════
    // NAVIGATION & FORMULAIRES
    // ══════════════════════════════════════════════════════

    $('#btn-verifier-code').on('click', function() {
        var code = $('#input-code-hebergeur').val().trim().toUpperCase();
        if (!code) return;
        $.get('{{ route("certificats-hebergement.api.code") }}', { code: code }, function(data) {
            if (data.found) {
                $('#hebergeur-found-result').removeClass('d-none');
                $('#hebergeur-not-found').addClass('d-none');
                $('#heb-found-nom').text(data.nom + ' ' + data.prenom);
                $('#heb-found-code').text(data.code);
                $('#heb-found-type').text(data.type);
                $('#heb-found-tel').text(data.telephone);
                $('#heb-found-certs').text(data.nb_certificats + ' certificat(s) précédent(s)');
                $('#btn-confirmer-hebergeur').data('hebergeur', data);
            } else {
                $('#hebergeur-found-result').addClass('d-none');
                $('#hebergeur-not-found').removeClass('d-none');
            }
        });
    });

    $('#input-code-hebergeur').on('input', function() { this.value = this.value.toUpperCase(); });

    $('#btn-confirmer-hebergeur').on('click', function() {
        var data = $(this).data('hebergeur');
        if (!data) return;
        document.getElementById('hebergeur_type').value = data.type;
        if (data.type === 'Etranger')     document.getElementById('hebergeur_impetrant_id').value = data.id;
        else if (data.type === 'Societe') document.getElementById('hebergeur_employeur_id').value = data.id;
        else                              document.getElementById('hebergeur_existant_id').value  = data.id;
        confirmerHebergeur(data.id, data.nom + ' ' + data.prenom, data.code, data.type);
        $('#hebergeur-found-result').addClass('d-none');
    });

    $('#btn-search-congolais').on('click', function() {
        var q = $('#search-congolais').val().trim();
        if (q.length < 2) return;
        $.get('{{ route("certificats-hebergement.api.hebergeur") }}', { q: q, type: 'Congolais' }, function(data) {
            renderResultList(data, 'result-search-Congolais', 'congolais');
        });
    });

    // btn-nouveau-congolais géré via onclick="toggleNouveauForm('congolais')"

    // ══ Lecteur CONGOLAIS ══
    $(document).on('click', '#btn-lire-passeport-cong', function() {
        $(this).prop('disabled', true);
        setStatus('passport-status-cong', 'info', '<i class="la la-spinner la-spin"></i> Lecture en cours...');
        $('#passport-photo-preview-cong').hide();
        $.ajax({ url: READER_URL + '/read', method: 'GET', timeout: 120000,
            success: function(data) {
                $('#btn-lire-passeport-cong').prop('disabled', false);
                if (data.status === 'success') {
                    if (data.nom)            $('input[name="heb_nom"]').val(data.nom);
                    if (data.prenoms)        $('input[name="heb_prenom"]').val(data.prenoms);
                    if (data.naissance)      $('input[name="heb_date_naissance"]').val(data.naissance);
                    if (data.lieu_naissance) $('input[name="heb_lieu_naissance"]').val(data.lieu_naissance);
                    if (data.num_doc) {
                        $('input[name="heb_numero_piece"]').val(data.num_doc);
                        var typePiece = (data.num_doc.match(/^[A-Z]{2}[0-9]/i) || (data.type_doc && data.type_doc.toUpperCase().indexOf('PASS') !== -1)) ? 'Passeport' : 'CNI';
                        $('select[name="heb_type_piece"]').val(typePiece);
                    }
                    if (data.expiration)     $('input[name="heb_date_expiration_piece"]').val(data.expiration);
                    if (data.date_emission)  $('input[name="heb_date_emission_piece"]').val(data.date_emission);
                    if (data.sexe)           $('select[name="heb_sexe"]').val(data.sexe === 'M' || data.sexe === 'Male' ? 'Masculin' : 'Féminin');
                    if (data.photo_base64 && data.photo_base64.length > 100) {
                        var src = 'data:image/jpeg;base64,' + data.photo_base64;
                        $('#passport-photo-img-cong').attr('src', src);
                        $('#passport-photo-preview-cong').show();
                        try {
                            var b=atob(data.photo_base64),ab=new ArrayBuffer(b.length),ia=new Uint8Array(ab);
                            for(var i=0;i<b.length;i++) ia[i]=b.charCodeAt(i);
                            var dt=new DataTransfer(); dt.items.add(new File([new Blob([ab],{type:'image/jpeg'})],'pp.jpg',{type:'image/jpeg'}));
                            var inp=document.getElementById('heb_photo'); if(inp){inp.files=dt.files; previewPhoto(inp,'preview-heb-photo');}
                        } catch(e) {}
                        setStatus('passport-status-cong','success','<i class="la la-check-circle"></i> <strong>Lu !</strong>');
                    } else { setStatus('passport-status-cong','success','<i class="la la-check-circle"></i> Données lues.'); }
                    toastr.success('Formulaire hébergeur congolais rempli !', 'Lecteur');
                    // ── Sauvegarde impetrant_documents (hébergeur congolais) ──
                    // Note : hébergeur congolais → pas d'impétrant DMCE.
                    // On enregistre uniquement si un impetrant_id est présent.
                    var impetrantIdCong = $('#hebergeur_impetrant_id').val();
                    if (data.num_doc && impetrantIdCong) {
                        var typePieceCong = (data.num_doc.match(/^[A-Z]{2}[0-9]/i) ||
                            (data.type_doc && data.type_doc.toUpperCase().indexOf('PASS') !== -1))
                            ? 'Passeport' : 'CNI';
                        $.ajax({
                            url: '/api/impetrants/store-document',
                            method: 'POST',
                            data: {
                                _token:           $('meta[name="csrf-token"]').attr('content'),
                                impetrants_id:    impetrantIdCong,
                                type_document:    typePieceCong,
                                numero_document:  data.num_doc,
                                date_delivrance:  data.date_emission  || '',
                                date_expiration:  data.expiration     || '',
                                mrz:              data.mrz            || '',
                                source:           'lecteur'
                            },
                            success: function(r) { if (r.saved) console.log('[DMCE] Doc congolais sauvegardé:', r.id); },
                            error:   function()  { console.warn('[DMCE] storeDocument hébergeur cong. échoué'); }
                        });
                    }
                } else { setStatus('passport-status-cong','warning','<i class="la la-exclamation-triangle"></i> '+(data.message||'Erreur')); }
            },
            error: function(){ setStatus('passport-status-cong','danger','<i class="la la-times-circle"></i> Service non disponible'); $('#btn-lire-passeport-cong').prop('disabled',false); }
        });
    });
    $(document).on('click', '#btn-restart-lecteur-cong', function() {
        $(this).prop('disabled', true);
        setStatus('passport-status-cong', 'info', '<i class="la la-refresh la-spin"></i> Réinitialisation...');
        $.ajax({ url: READER_URL + '/restart', method: 'GET', timeout: 10000,
            success: function(){ setTimeout(function(){ setStatus('passport-status-cong','success','<i class="la la-check-circle"></i> Réinitialisé !'); $('#btn-restart-lecteur-cong,#btn-lire-passeport-cong').prop('disabled',false); },3000); },
            error: function(){ setStatus('passport-status-cong','danger','<i class="la la-times-circle"></i> Erreur'); $('#btn-restart-lecteur-cong').prop('disabled',false); }
        });
    });

    // btn-nouveau-etranger géré via onclick="toggleNouveauForm('etranger')"

    $(document).on('change', '#heb_etr_etat_civil, #heb_etr_sexe', function() {
        var etat = $('#heb_etr_etat_civil').val();
        var sexe = $('#heb_etr_sexe').val();
        $('#heb_etr_conjoint_wrap').toggle(etat === 'Marié(e)' && sexe === 'Féminin');
    });

    // btn-nouveau-societe géré via onclick="toggleNouveauForm('societe')"

    $('#btn-search-etranger').on('click', function() {
        var nom = $('#etr-search-nom').val().trim(), prenom = $('#etr-search-prenom').val().trim();
        var dn = $('#etr-search-dn').val(), nat = $('#etr-search-nat').val();
        if (!nom && !prenom && !dn && !nat) { toastr.warning('Veuillez renseigner au moins un critère'); return; }
        $.get('{{ route("certificats-hebergement.api.hebergeur") }}', {
            q: nom || prenom || '', nom: nom, prenom: prenom, date_naissance: dn, nationalites_id: nat, type: 'Etranger'
        }, function(data) {
            if (!data.length) {
                $('#result-search-Etranger').html('<div class="alert alert-warning py-2"><i class="la la-exclamation-triangle"></i> Aucun impétrant trouvé.</div>');
                return;
            }
            var html = '<div class="list-group">';
            data.forEach(function(item) {
                var badge = item.est_heb ? '<span class="badge badge-success ml-1">Hébergeur ★</span>' : '';
                html += '<button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"'
                     + ' onclick="selectHebergeurFromList(' + item.id + ',\''
                     + (item.nom + ' ' + item.prenom).replace(/'/g, "\\'") + '\',\''
                     + (item.code || '') + '\',\'' + item.type + '\',\'etranger\')">'
                     + '<div><strong>' + item.nom + ' ' + item.prenom + '</strong>'
                     + '<small class="text-muted ml-2">' + item.dn + ' — ' + item.nationalite + '</small>'
                     + badge + '</div>'
                     + (item.code ? '<span class="badge badge-primary">' + item.code + '</span>' : '')
                     + '</button>';
            });
            $('#result-search-Etranger').html(html + '</div>');
        });
    });

    $('#etr-search-nom, #etr-search-prenom, #etr-search-dn, #etr-search-nat').on('keypress', function(e) {
        if (e.which === 13) { e.preventDefault(); $('#btn-search-etranger').trigger('click'); }
    });

    var timerSociete;
    $('#search-societe').on('input', function() {
        clearTimeout(timerSociete);
        var q = $(this).val().trim();
        if (q.length < 2) return;
        timerSociete = setTimeout(function() {
            $.get('{{ route("certificats-hebergement.api.hebergeur") }}', { q: q, type: 'Societe' }, function(data) {
                renderResultList(data, 'result-search-Societe', 'societe');
            });
        }, 300);
    });

    $('#heb_departements_id').on('change', function() {
        var id = $(this).val(); if (!id) return;
        $.get('{{ route("departements.arrondissements", "id") }}'.replace('id', id), function(data) {
            var out = '<option value="">Sélectionner</option>';
            data.forEach(function(a) { out += '<option value="' + a.id + '">' + a.lib_arrondissement + '</option>'; });
            $('#heb_arrondissements_id').html(out);
            $('#heb_quartiers_id').html('<option value="">Sélectionner</option>');
        });
    });

    $('#heb_arrondissements_id').on('change', function() {
        var id = $(this).val(); if (!id) return;
        $.get('{{ route("arrondissements.quartiers", "id") }}'.replace('id', id), function(data) {
            var out = '<option value="">Sélectionner</option>';
            data.forEach(function(q) { out += '<option value="' + q.id + '">' + q.lib_quartier + '</option>'; });
            $('#heb_quartiers_id').html(out);
        });
    });

    $('#btn-changer-hebergeur').on('click', function() {
        hebergeurData = {};
        $('#hebergeur-selectionne').addClass('d-none');
        document.getElementById('hebergeur_existant_id').value  = '';
        document.getElementById('hebergeur_impetrant_id').value = '';
        document.getElementById('hebergeur_employeur_id').value = '';
        document.getElementById('btn-step1-next').disabled = true;
    });

    $('#btn-step1-next').on('click', function() {
        if (!document.getElementById('hebergeur_type').value) {
            toastr.warning('Veuillez sélectionner et confirmer un hébergeur');
            return;
        }
        goToStep(2);
    });

    $('#btn-search-heberge').on('click', function() {
        var q = $('#search-heberge').val().trim();
        if (q.length < 2) return;
        $.get('{{ route("certificats-hebergement.api.heberge") }}', { q: q }, function(data) {
            if (!data.length) { $('#result-heberge').html('<p class="text-muted small">Aucun impétrant trouvé.</p>'); return; }
            var html = '<div class="list-group">';
            data.forEach(function(item) {
                html += '<button type="button" class="list-group-item list-group-item-action"'
                     + ' onclick="selectHeberge(' + item.id + ',\'' + (item.nom + ' ' + item.prenom).replace(/'/g,"\\'")
                     + '\',\'' + item.date_naissance + '\',\'' + item.nationalite + '\')">'
                     + '<strong>' + item.nom + ' ' + item.prenom + '</strong>'
                     + '<small class="text-muted ml-2">' + item.date_naissance + ' — ' + item.nationalite + '</small>'
                     + '</button>';
            });
            $('#result-heberge').html(html + '</div>');
        });
    });

    $('#search-heberge').on('keypress', function(e) {
        if (e.which === 13) { e.preventDefault(); $('#btn-search-heberge').trigger('click'); }
    });

    $('#btn-changer-heberge').on('click', function() {
        hebergeData = {};
        document.getElementById('heberge_impetrant_id').value = '';
        $('#heberge-selectionne').addClass('d-none');
    });

    $('#btn-step2-prev').on('click', function() { goToStep(1); });
    $('#btn-step2-next').on('click', function() { goToStep(3); });

    function calculerDuree() {
        var a = document.getElementById('date_arrivee_prevue').value;
        var d = document.getElementById('date_depart_prevue').value;
        if (a && d) { var diff = Math.round((new Date(d) - new Date(a)) / 86400000); $('#duree-valeur').text(diff); $('#duree-display').show(); }
    }
    $('#date_arrivee_prevue, #date_depart_prevue').on('change', calculerDuree);

    $('#btn-step3-prev').on('click', function() { goToStep(2); });
    $('#btn-step3-next').on('click', function() {
        var a = document.getElementById('date_arrivee_prevue').value;
        var d = document.getElementById('date_depart_prevue').value;
        if (!a || !d) { toastr.warning('Veuillez renseigner les dates de séjour'); return; }
        if (new Date(d) <= new Date(a)) { toastr.warning('La date de départ doit être après l\'arrivée'); return; }
        $('#recap-hebergeur-nom').text(hebergeurData.nom || '—');
        $('#recap-hebergeur-code').text(hebergeurData.code || '');
        $('#recap-hebergeur-type').text(hebergeurData.type || '');
        var hebergeNom = document.getElementById('heberge_impetrant_id').value
            ? $('#heberge-selectionne-nom').text()
            : (($('#heberge_nom').val() || '') + ' ' + ($('#heberge_prenom').val() || '')).trim();
        $('#recap-heberge-nom').text(hebergeNom || 'Nouveau profil');
        $('#recap-arrivee').text(new Date(a).toLocaleDateString('fr-FR'));
        $('#recap-depart').text(new Date(d).toLocaleDateString('fr-FR'));
        $('#recap-duree').text($('#duree-valeur').text() + ' jours');
        goToStep(4);
    });

    $('#btn-step4-prev').on('click', function() { goToStep(3); });

});
</script>
@endsection
