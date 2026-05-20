@extends('admin.layouts.app')

@section('title', 'Enregistrer un impétrant')

@section('styles')
<style>
/* ─── Wizard steps ─────────────────────────────────────── */
.wizard-steps {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
}
.wizard-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
}
.wizard-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 18px;
    left: 55%;
    width: 90%;
    height: 2px;
    background: #dee2e6;
    z-index: 0;
}
.wizard-step.active:not(:last-child)::after,
.wizard-step.done:not(:last-child)::after { background: #1E9FF2; }
.step-circle {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px;
    z-index: 1;
    transition: background .3s, color .3s;
}
.wizard-step.active .step-circle { background: #1E9FF2; color: #fff; }
.wizard-step.done   .step-circle { background: #28D094; color: #fff; }
.step-label {
    font-size: 11px;
    margin-top: 6px;
    color: #6c757d;
    text-align: center;
}
.wizard-step.active .step-label { color: #1E9FF2; font-weight: 600; }
.wizard-step.done   .step-label { color: #28D094; }

/* ─── Photo preview ────────────────────────────────────── */
.photo-preview-box {
    width: 140px; height: 170px;
    border: 2px dashed #ced4da;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; cursor: pointer;
    background: #f8f9fa;
    transition: border-color .2s;
}
.photo-preview-box:hover { border-color: #1E9FF2; }
.photo-preview-box img { width: 100%; height: 100%; object-fit: cover; }
.photo-preview-box .placeholder { text-align: center; color: #adb5bd; font-size: 13px; }

/* ─── Doublon alert ────────────────────────────────────── */
#doublon-alert { display: none; }

/* ─── Step panels ──────────────────────────────────────── */
.step-panel { display: none; }
.step-panel.active { display: block; }
</style>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('impetrants.index') }}">Impétrants</a></li>
                    <li class="breadcrumb-item active">Enregistrement direct</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Titre --}}
    <div class="row mb-3">
        <div class="col-12 d-flex align-items-center">
            <i class="la la-user-plus mr-2" style="font-size:28px;color:#1E9FF2"></i>
            <h4 class="mb-0">Enregistrement d'un impétrant</h4>
            <span class="badge badge-info ml-2">Sans demande</span>
        </div>
    </div>

    {{-- Alerte doublon --}}
    <div id="doublon-alert" class="alert alert-danger alert-dismissible">
        <button type="button" class="close" onclick="$('#doublon-alert').hide()">×</button>
        <i class="la la-exclamation-triangle mr-1"></i>
        <strong>Doublon détecté !</strong>
        Un impétrant avec les mêmes données biométriques existe déjà :
        <strong id="doublon-nom"></strong>.
        <a id="doublon-lien" href="#" class="btn btn-sm btn-danger ml-2" target="_blank">
            <i class="la la-eye"></i> Voir la fiche
        </a>
    </div>

    @if(session('doublon_id'))
    <div class="alert alert-danger">
        <i class="la la-exclamation-triangle mr-1"></i>
        Enregistrement annulé — doublon détecté.
        <a href="{{ route('impetrants.show', session('doublon_id')) }}" class="btn btn-sm btn-danger ml-2">
            Voir la fiche existante
        </a>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Wizard steps --}}
    <div class="wizard-steps">
        <div class="wizard-step active" id="ws-1">
            <div class="step-circle">1</div>
            <div class="step-label">Identité</div>
        </div>
        <div class="wizard-step" id="ws-2">
            <div class="step-circle">2</div>
            <div class="step-label">Document</div>
        </div>
        <div class="wizard-step" id="ws-3">
            <div class="step-circle">3</div>
            <div class="step-label">Adresse</div>
        </div>
        <div class="wizard-step" id="ws-4">
            <div class="step-circle">4</div>
            <div class="step-label">Confirmation</div>
        </div>
    </div>

    {{-- Formulaire --}}
    <form method="POST"
          action="{{ route('impetrants.store') }}"
          enctype="multipart/form-data"
          id="formImpetrant">
        @csrf

        {{-- ══════════════ ÉTAPE 1 — IDENTITÉ ══════════════ --}}
        <div class="step-panel active" id="step-1">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="la la-id-card mr-1 text-primary"></i> Identité de l'impétrant
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        {{-- Photo --}}
                        <div class="col-md-2 d-flex flex-column align-items-center">
                            <label class="font-weight-bold mb-2">Photo</label>
                            <div class="photo-preview-box" onclick="$('#photoInput').click()">
                                <div id="photoPlaceholder" class="placeholder">
                                    <i class="la la-camera" style="font-size:32px"></i><br>
                                    Cliquez pour ajouter
                                </div>
                                <img id="photoPreview" src="" alt="" style="display:none">
                            </div>
                            <input type="file" name="photo" id="photoInput"
                                   accept="image/*" class="d-none">
                            <small class="text-muted mt-1">JPG/PNG — max 2 Mo</small>
                        </div>

                        {{-- Données identité --}}
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="nom"
                                           class="form-control @error('nom') is-invalid @enderror"
                                           value="{{ old('nom') }}"
                                           placeholder="NOM DE FAMILLE"
                                           style="text-transform:uppercase"
                                           required>
                                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Prénom(s) <span class="text-danger">*</span></label>
                                    <input type="text" name="prenom" id="prenom"
                                           class="form-control @error('prenom') is-invalid @enderror"
                                           value="{{ old('prenom') }}"
                                           placeholder="Prénom(s)"
                                           required>
                                    @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-2 form-group">
                                    <label class="font-weight-bold">Sexe <span class="text-danger">*</span></label>
                                    <select name="sexe" id="sexe"
                                            class="form-control @error('sexe') is-invalid @enderror"
                                            required>
                                        <option value="">-- Sexe --</option>
                                        <option value="M" {{ old('sexe')=='M'?'selected':'' }}>Masculin</option>
                                        <option value="F" {{ old('sexe')=='F'?'selected':'' }}>Féminin</option>
                                    </select>
                                    @error('sexe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-2 form-group">
                                    <label class="font-weight-bold">Date naissance <span class="text-danger">*</span></label>
                                    <input type="date" name="date_naissance" id="date_naissance"
                                           class="form-control @error('date_naissance') is-invalid @enderror"
                                           value="{{ old('date_naissance') }}"
                                           max="{{ date('Y-m-d') }}"
                                           required>
                                    @error('date_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Lieu de naissance</label>
                                    <input type="text" name="lieu_naissance"
                                           class="form-control"
                                           value="{{ old('lieu_naissance') }}"
                                           placeholder="Ville de naissance">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Nationalité principale <span class="text-danger">*</span></label>
                                    <select name="nationalites_id" id="nationalites_id"
                                            class="form-control select2 @error('nationalites_id') is-invalid @enderror"
                                            required>
                                        <option value="">-- Sélectionner --</option>
                                        @foreach($nationalites as $nat)
                                        <option value="{{ $nat->id }}"
                                            {{ old('nationalites_id')==$nat->id?'selected':'' }}>
                                            {{ $nat->libelle }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('nationalites_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Nationalités secondaires</label>
                                    <select name="nationalites_secondaires[]" id="nationalites_secondaires"
                                            class="form-control select2"
                                            multiple>
                                        @foreach($nationalites as $nat)
                                        <option value="{{ $nat->id }}">{{ $nat->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Profession</label>
                                    <input type="text" name="profession"
                                           class="form-control"
                                           value="{{ old('profession') }}"
                                           placeholder="Profession exercée">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Téléphone</label>
                                    <input type="text" name="telephone"
                                           class="form-control"
                                           value="{{ old('telephone') }}"
                                           placeholder="+242 06 ...">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Email</label>
                                    <input type="email" name="email"
                                           class="form-control"
                                           value="{{ old('email') }}"
                                           placeholder="adresse@email.com">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Nom du père</label>
                                    <input type="text" name="nom_pere"
                                           class="form-control"
                                           value="{{ old('nom_pere') }}"
                                           placeholder="Nom du père">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Nom de la mère</label>
                                    <input type="text" name="nom_mere"
                                           class="form-control"
                                           value="{{ old('nom_mere') }}"
                                           placeholder="Nom de la mère">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="goToStep(2)">
                        Suivant <i class="la la-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════ ÉTAPE 2 — DOCUMENT ══════════════ --}}
        <div class="step-panel" id="step-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="la la-passport mr-1 text-primary"></i> Document d'identité
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Type de document <span class="text-danger">*</span></label>
                            <select name="type_document" class="form-control select2 @error('type_document') is-invalid @enderror" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="Passeport"           {{ old('type_document')=='Passeport'?'selected':'' }}>Passeport</option>
                                <option value="Carte d'identité"    {{ old('type_document')=="Carte d'identité"?'selected':'' }}>Carte d'identité</option>
                                <option value="Titre de voyage"     {{ old('type_document')=='Titre de voyage'?'selected':'' }}>Titre de voyage</option>
                                <option value="Laissez-passer"      {{ old('type_document')=='Laissez-passer'?'selected':'' }}>Laissez-passer</option>
                                <option value="Autre"               {{ old('type_document')=='Autre'?'selected':'' }}>Autre</option>
                            </select>
                            @error('type_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Numéro de document <span class="text-danger">*</span></label>
                            <input type="text" name="numero_document"
                                   class="form-control @error('numero_document') is-invalid @enderror"
                                   value="{{ old('numero_document') }}"
                                   placeholder="Ex: AB123456"
                                   style="text-transform:uppercase"
                                   required>
                            @error('numero_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Pays d'émission</label>
                            <input type="text" name="pays_emission_document"
                                   class="form-control"
                                   value="{{ old('pays_emission_document') }}"
                                   placeholder="Pays émetteur">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Date d'émission</label>
                            <input type="date" name="date_emission_document"
                                   id="date_emission_doc"
                                   class="form-control"
                                   value="{{ old('date_emission_document') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Date d'expiration</label>
                            <input type="date" name="date_expiration_document"
                                   id="date_expiration_doc"
                                   class="form-control"
                                   value="{{ old('date_expiration_document') }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(1)">
                        <i class="la la-arrow-left mr-1"></i> Précédent
                    </button>
                    <button type="button" class="btn btn-primary" onclick="goToStep(3)">
                        Suivant <i class="la la-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════ ÉTAPE 3 — ADRESSE AU CONGO ══════════════ --}}
        <div class="step-panel" id="step-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="la la-map-marker mr-1 text-primary"></i> Adresse au Congo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="font-weight-bold">Adresse complète</label>
                            <input type="text" name="adresse"
                                   class="form-control"
                                   value="{{ old('adresse') }}"
                                   placeholder="Rue, quartier, immeuble...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Département</label>
                            <select name="departements_id" id="departements_id"
                                    class="form-control select2">
                                <option value="">-- Département --</option>
                                @foreach($departements as $dep)
                                <option value="{{ $dep->id }}"
                                    {{ old('departements_id')==$dep->id?'selected':'' }}>
                                    {{ $dep->libelle }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Arrondissement</label>
                            <select name="arrondissements_id" id="arrondissements_id"
                                    class="form-control select2">
                                <option value="">-- Arrondissement --</option>
                                @foreach($allArrondissements as $arr)
                                <option value="{{ $arr->id }}"
                                    data-dep="{{ $arr->departements_id }}"
                                    {{ old('arrondissements_id')==$arr->id?'selected':'' }}>
                                    {{ $arr->libelle }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Quartier</label>
                            <select name="quartiers_id" id="quartiers_id"
                                    class="form-control select2">
                                <option value="">-- Quartier --</option>
                                @foreach($allQuartiers as $q)
                                <option value="{{ $q->id }}"
                                    data-arr="{{ $q->arrondissements_id }}"
                                    {{ old('quartiers_id')==$q->id?'selected':'' }}>
                                    {{ $q->libelle }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(2)">
                        <i class="la la-arrow-left mr-1"></i> Précédent
                    </button>
                    <button type="button" class="btn btn-primary" onclick="goToStep(4)">
                        Suivant <i class="la la-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════ ÉTAPE 4 — CONFIRMATION ══════════════ --}}
        <div class="step-panel" id="step-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="la la-check-circle mr-1 text-success"></i> Récapitulatif
                    </h5>
                </div>
                <div class="card-body">
                    <div id="recap-content">
                        {{-- Rempli dynamiquement par JS --}}
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(3)">
                        <i class="la la-arrow-left mr-1"></i> Précédent
                    </button>
                    <button type="submit" class="btn btn-success btn-lg" id="btnSubmit">
                        <i class="la la-save mr-1"></i> Enregistrer l'impétrant
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

{{-- ══════════════════════════════════════════════════════════════
     SCRIPTS — fonctions globales en dehors de document.ready
     pour rester accessibles depuis onclick=""
══════════════════════════════════════════════════════════════ --}}

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>

// ── Variables JSON pour les selects dépendants ─────────────────
var allArrondissements = @json($allArrondissements);
var allQuartiers       = @json($allQuartiers);

// ── Navigation entre étapes ────────────────────────────────────
function goToStep(n) {
    // Validation étape 1 avant de passer à 2
    if (n === 2) {
        var nom    = document.getElementById('nom').value.trim();
        var prenom = document.getElementById('prenom').value.trim();
        var sexe   = document.getElementById('sexe').value;
        var ddn    = document.getElementById('date_naissance').value;
        var natId  = document.getElementById('nationalites_id').value;

        if (!nom || !prenom || !sexe || !ddn || !natId) {
            toastr.warning('Veuillez remplir tous les champs obligatoires (Nom, Prénom, Sexe, Date naissance, Nationalité).');
            return;
        }
        // Vérification doublon AJAX
        verifierDoublon(function() { _switchStep(n); });
        return;
    }
    // Validation étape 2 avant de passer à 3
    if (n === 3) {
        var typDoc = document.querySelector('[name=type_document]').value;
        var numDoc = document.querySelector('[name=numero_document]').value.trim();
        if (!typDoc || !numDoc) {
            toastr.warning('Veuillez renseigner le type et le numéro de document.');
            return;
        }
    }
    // Recap à l'étape 4
    if (n === 4) { buildRecap(); }
    _switchStep(n);
}

function _switchStep(n) {
    // Panneaux
    document.querySelectorAll('.step-panel').forEach(function(p) {
        p.classList.remove('active');
    });
    document.getElementById('step-' + n).classList.add('active');

    // Indicateurs wizard
    document.querySelectorAll('.wizard-step').forEach(function(ws, i) {
        var idx = i + 1;
        ws.classList.remove('active', 'done');
        if (idx === n) ws.classList.add('active');
        if (idx < n)   ws.classList.add('done');
    });

    window.scrollTo(0, 0);
}

// ── Vérification doublon (AJAX) ────────────────────────────────
function verifierDoublon(callback) {
    var data = {
        _token:          document.querySelector('meta[name=csrf-token]').getAttribute('content'),
        nom:             document.getElementById('nom').value.trim(),
        prenom:          document.querySelector('[name=prenom]').value.trim(),
        sexe:            document.getElementById('sexe').value,
        date_naissance:  document.getElementById('date_naissance').value,
        nationalites_id: document.getElementById('nationalites_id').value,
    };

    $.post('{{ route("impetrants.api.check-doublon") }}', data, function(resp) {
        if (resp.doublon) {
            document.getElementById('doublon-nom').textContent  = resp.nom;
            document.getElementById('doublon-lien').href =
                '{{ url("impetrants") }}/' + resp.id;
            $('#doublon-alert').show();
            // Ne pas bloquer — informer seulement
        } else {
            $('#doublon-alert').hide();
        }
        if (callback) callback();
    }).fail(function() {
        // En cas d'erreur réseau, on laisse passer
        if (callback) callback();
    });
}

// ── Recap étape 4 ──────────────────────────────────────────────
function buildRecap() {
    var natSelect  = document.getElementById('nationalites_id');
    var natText    = natSelect.options[natSelect.selectedIndex]
                    ? natSelect.options[natSelect.selectedIndex].text : '—';
    var sexeMap    = { M: 'Masculin', F: 'Féminin' };
    var sexe       = document.getElementById('sexe').value;
    var depSelect  = document.getElementById('departements_id');
    var depText    = depSelect.options[depSelect.selectedIndex]
                    ? depSelect.options[depSelect.selectedIndex].text : '—';

    var html = '<div class="row">';
    html += recapItem('Nom',              document.querySelector('[name=nom]').value.toUpperCase());
    html += recapItem('Prénom',           document.querySelector('[name=prenom]').value);
    html += recapItem('Sexe',             sexeMap[sexe] || '—');
    html += recapItem('Date naissance',   document.getElementById('date_naissance').value);
    html += recapItem('Nationalité',      natText);
    html += recapItem('Type document',    document.querySelector('[name=type_document]').value);
    html += recapItem('N° document',      document.querySelector('[name=numero_document]').value.toUpperCase());
    html += recapItem('Département',      depText);
    html += recapItem('Téléphone',        document.querySelector('[name=telephone]').value || '—');
    html += recapItem('Email',            document.querySelector('[name=email]').value || '—');
    html += '</div>';

    // Aperçu photo
    var preview = document.getElementById('photoPreview');
    if (preview.style.display !== 'none') {
        html += '<div class="mt-3"><strong>Photo :</strong><br>'
              + '<img src="' + preview.src + '" style="height:100px;border-radius:4px">'
              + '</div>';
    }

    document.getElementById('recap-content').innerHTML = html;
}

function recapItem(label, val) {
    return '<div class="col-md-4 mb-3">'
         + '<small class="text-muted d-block">' + label + '</small>'
         + '<strong>' + (val || '—') + '</strong>'
         + '</div>';
}

</script>

@push('scripts')
<script>
$(document).ready(function () {

    // ── Select2 ────────────────────────────────────────────────
    $('.select2').select2({ width: '100%', placeholder: '-- Sélectionner --' });

    // ── Photo preview ───────────────────────────────────────────
    $('#photoInput').on('change', function () {
        var file = this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#photoPreview').attr('src', e.target.result).show();
            $('#photoPlaceholder').hide();
        };
        reader.readAsDataURL(file);
    });

    // ── Selects dépendants Département → Arrondissement ────────
    $('#departements_id').on('change', function () {
        var depId = $(this).val();
        var $arr  = $('#arrondissements_id');
        $arr.empty().append('<option value="">-- Arrondissement --</option>');
        $.each(allArrondissements, function (_, a) {
            if (!depId || String(a.departements_id) === String(depId)) {
                $arr.append('<option value="' + a.id + '">' + a.libelle + '</option>');
            }
        });
        $arr.trigger('change');
    });

    // ── Selects dépendants Arrondissement → Quartier ───────────
    $('#arrondissements_id').on('change', function () {
        var arrId = $(this).val();
        var $q    = $('#quartiers_id');
        $q.empty().append('<option value="">-- Quartier --</option>');
        $.each(allQuartiers, function (_, q) {
            if (!arrId || String(q.arrondissements_id) === String(arrId)) {
                $q.append('<option value="' + q.id + '">' + q.libelle + '</option>');
            }
        });
    });

    // ── Date expiration ≥ date émission ────────────────────────
    $('#date_emission_doc').on('change', function () {
        $('#date_expiration_doc').attr('min', $(this).val());
    });

    // ── Restauration old() après erreur de validation ──────────
    @if(old('departements_id'))
        $('#departements_id').val('{{ old("departements_id") }}').trigger('change');
        setTimeout(function () {
            $('#arrondissements_id').val('{{ old("arrondissements_id") }}').trigger('change');
            setTimeout(function () {
                $('#quartiers_id').val('{{ old("quartiers_id") }}');
            }, 100);
        }, 100);
        // Sauter à l'étape 1 si erreur
        goToStep(1);
    @endif

    // ── Mise en majuscules auto ─────────────────────────────────
    $('#nom, [name=numero_document]').on('input', function () {
        var pos = this.selectionStart;
        this.value = this.value.toUpperCase();
        this.setSelectionRange(pos, pos);
    });

    // ── Prévenir soumission si doublon confirmé ─────────────────
    $('#formImpetrant').on('submit', function () {
        $('#btnSubmit').prop('disabled', true)
                       .html('<i class="la la-spinner la-spin mr-1"></i> Enregistrement...');
    });
});
</script>
@endpush
