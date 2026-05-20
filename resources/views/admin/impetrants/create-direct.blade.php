@extends('admin.layouts.app')

@section('title', 'Enregistrer un impétrant')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
/* ══════════════════════════════════════════════════════════
   VARIABLES & BASE
══════════════════════════════════════════════════════════ */
:root {
    --blue:    #1E9FF2;
    --green:   #28D094;
    --orange:  #FF9149;
    --red:     #FF4961;
    --dark:    #2c3e50;
    --muted:   #8898aa;
    --border:  #e4e8ef;
    --bg:      #f7f9fc;
    --card:    #ffffff;
    --radius:  10px;
}

/* ── Progress bar ──────────────────────────────────────── */
.wiz-progress {
    display: flex; align-items: center;
    justify-content: center;
    padding: 28px 0 24px;
    gap: 0;
}
.wiz-step {
    display: flex; flex-direction: column;
    align-items: center; flex: 1;
    position: relative; z-index: 1;
}
.wiz-step + .wiz-step::before {
    content: ''; position: absolute;
    right: 50%; top: 19px;
    width: 100%; height: 2px;
    background: var(--border);
    z-index: -1; transition: background .4s;
}
.wiz-step.done + .wiz-step::before,
.wiz-step.active + .wiz-step::before {
    background: var(--blue);
}
.wiz-dot {
    width: 38px; height: 38px; border-radius: 50%;
    background: #fff; border: 2px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: var(--muted);
    transition: all .3s; box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.wiz-step.active .wiz-dot {
    border-color: var(--blue); color: var(--blue);
    box-shadow: 0 0 0 5px rgba(30,159,242,.12);
}
.wiz-step.done .wiz-dot {
    background: var(--green); border-color: var(--green);
    color: #fff; box-shadow: 0 2px 8px rgba(40,208,148,.3);
}
.wiz-label {
    margin-top: 8px; font-size: 10px; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase; color: var(--muted);
}
.wiz-step.active .wiz-label { color: var(--blue); }
.wiz-step.done  .wiz-label  { color: var(--green); }

/* ── Lecteur passeport ─────────────────────────────────── */
.reader-panel {
    border: 1.5px solid rgba(30,159,242,.2);
    border-radius: var(--radius);
    background: linear-gradient(135deg, #f0f8ff 0%, #f8fbff 100%);
    overflow: hidden; margin-bottom: 24px;
}
.reader-header {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 16px;
    background: rgba(30,159,242,.06);
    border-bottom: 1px solid rgba(30,159,242,.12);
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .07em; color: var(--blue);
}
.reader-header .spacer { flex: 1; }
.led {
    width: 9px; height: 9px; border-radius: 50%;
    background: #ced4da; flex-shrink: 0; transition: background .3s;
}
.led.ok   { background: var(--green); animation: pulse-led 2s infinite; }
.led.busy { background: var(--orange); animation: pulse-led .6s infinite; }
.led.err  { background: var(--red); }
@keyframes pulse-led { 0%,100%{opacity:1} 50%{opacity:.3} }

.reader-body {
    display: flex; gap: 16px; padding: 14px 16px;
    align-items: flex-start;
}
.reader-photo {
    width: 80px; height: 98px; flex-shrink: 0;
    border-radius: 7px; border: 2px solid var(--border);
    background: #eef2f7; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    transition: border-color .3s;
}
.reader-photo.loaded { border-color: var(--green); }
.reader-photo img { width:100%; height:100%; object-fit:cover; display:none; }
.reader-photo i   { font-size: 2rem; color: #ced4da; }
.reader-actions { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 8px; }
.reader-log {
    font-size: 12px; color: var(--muted);
    min-height: 18px; transition: color .3s;
}

/* ── Doublon banner ────────────────────────────────────── */
.doublon-banner {
    display: none; align-items: flex-start; gap: 10px;
    border-radius: 8px; padding: 12px 14px; margin-bottom: 16px;
    border-left: 4px solid #ffc107;
    background: #fffdf0;
}
.doublon-banner.bloquant {
    border-left-color: var(--red);
    background: #fff5f5;
}

/* ── Section titres ────────────────────────────────────── */
.sec-title {
    font-size: 10px; font-weight: 800; letter-spacing: .09em;
    text-transform: uppercase; color: var(--blue);
    padding-bottom: 8px; margin-bottom: 16px;
    border-bottom: 2px solid var(--border);
    display: flex; align-items: center; gap: 7px;
}
.sec-title i { font-size: 14px; }

/* ── Steps ─────────────────────────────────────────────── */
.step-pane { display: none; animation: fadeInUp .3s ease; }
.step-pane.active { display: block; }
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Formulaire ────────────────────────────────────────── */
.form-label-sm {
    font-size: 11px; font-weight: 700;
    color: var(--dark); letter-spacing: .03em;
    margin-bottom: 5px; display: block;
}
.form-control-sm {
    border-radius: 7px !important;
    border-color: var(--border) !important;
    font-size: 13px !important;
    transition: border-color .2s, box-shadow .2s;
}
.form-control-sm:focus {
    border-color: var(--blue) !important;
    box-shadow: 0 0 0 3px rgba(30,159,242,.1) !important;
}

/* ── Zone photo ────────────────────────────────────────── */
.photo-upload-zone {
    border: 2px dashed var(--border); border-radius: 10px;
    text-align: center; padding: 18px 12px; cursor: pointer;
    transition: border-color .2s, background .2s;
    background: #fafbfc;
}
.photo-upload-zone:hover {
    border-color: var(--blue);
    background: rgba(30,159,242,.03);
}
.photo-upload-zone.has-photo { border-style: solid; border-color: var(--green); }

/* ── Nav wizard ────────────────────────────────────────── */
.wiz-nav {
    display: flex; justify-content: space-between; align-items: center;
    border-top: 1px solid var(--border);
    padding-top: 20px; margin-top: 24px;
}

/* ── Récap ──────────────────────────────────────────────── */
.recap-card {
    background: linear-gradient(135deg, #f7faff, #f0f8ff);
    border: 1px solid rgba(30,159,242,.15);
    border-radius: 12px; padding: 20px; text-align: center;
    margin-bottom: 20px;
}
.recap-avatar {
    width: 90px; height: 110px; border-radius: 9px;
    object-fit: cover; border: 3px solid var(--blue);
    margin: 0 auto 12px; display: block;
}
.recap-avatar-placeholder {
    width: 72px; height: 72px; border-radius: 50%;
    background: var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 12px; font-size: 2rem; color: #ced4da;
}
.recap-nom  { font-size: 17px; font-weight: 800; color: var(--dark); }
.recap-nat  {
    display: inline-block; background: rgba(30,159,242,.1);
    color: var(--blue); font-size: 11px; font-weight: 700;
    padding: 3px 12px; border-radius: 20px; margin-top: 4px;
}
.recap-row  {
    display: flex; font-size: 13px;
    padding: 7px 0; border-bottom: 1px solid #eef1f5;
}
.recap-row:last-child { border-bottom: none; }
.recap-key  { width: 45%; color: var(--muted); font-weight: 600; font-size: 12px; }
.recap-val  { flex: 1; color: var(--dark); font-weight: 500; }

/* ── Bouton enregistrer ────────────────────────────────── */
.btn-enreg {
    background: linear-gradient(135deg, var(--green), #1db87d);
    color: #fff; border: none; border-radius: 8px;
    padding: 10px 28px; font-size: 14px; font-weight: 700;
    display: inline-flex; align-items: center; gap: 8px;
    cursor: pointer; transition: opacity .2s, box-shadow .2s;
    box-shadow: 0 4px 14px rgba(40,208,148,.35);
}
.btn-enreg:hover   { opacity: .92; box-shadow: 0 6px 18px rgba(40,208,148,.45); }
.btn-enreg:disabled{ opacity: .55; cursor: not-allowed; box-shadow: none; }
</style>
@endsection

@section('content')
<div class="container-fluid">

    {{-- ── En-tête page ──────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap" style="gap:10px;">
        <div>
            <h4 class="mb-0 font-weight-bold" style="color:var(--dark);">
                <i class="la la-id-card text-primary mr-2"></i>
                Enregistrer un impétrant
            </h4>
            <small class="text-muted">Enregistrement direct · sans demande associée</small>
        </div>
        <a href="{{ route('impetrants.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="la la-arrow-left mr-1"></i> Liste des impétrants
        </a>
    </div>

    <div style="max-width:860px; margin:0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
            <div class="card-body p-4">

                {{-- ── Progress ────────────────────────────────── --}}
                <div class="wiz-progress">
                    <div class="wiz-step active" id="ps1">
                        <div class="wiz-dot"><i class="la la-user"></i></div>
                        <span class="wiz-label">Identité</span>
                    </div>
                    <div class="wiz-step" id="ps2">
                        <div class="wiz-dot"><i class="la la-check"></i></div>
                        <span class="wiz-label">Confirmation</span>
                    </div>
                </div>

                <form id="frmImp" method="POST"
                      action="{{ route('impetrants.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- ══════════════════════════════════════════
                         ÉTAPE 1 — Identité
                    ══════════════════════════════════════════ --}}
                    <div class="step-pane active" id="pane1">

                        {{-- Lecteur passeport --}}
                        <div class="reader-panel">
                            <div class="reader-header">
                                <div class="led" id="rLed"></div>
                                <i class="la la-passport"></i>
                                Lecteur de document
                                <span id="rBadge" class="badge badge-secondary ml-1" style="font-size:10px;">—</span>
                                <span class="spacer"></span>
                            </div>
                            <div class="reader-body">
                                <div class="reader-photo" id="rPhotoWrap">
                                    <i class="la la-user" id="rPhotoIcon"></i>
                                    <img id="rPhotoImg" src="" alt="Photo">
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-2" style="font-size:12px;">
                                        <i class="la la-info-circle text-primary mr-1"></i>
                                        Placez le passeport ou la pièce d'identité sur le lecteur,
                                        puis cliquez <strong>Lire</strong>.
                                    </p>
                                    <div class="reader-actions">
                                        <button type="button" class="btn btn-primary btn-sm"
                                                id="btnLire" onclick="lancerLecture()">
                                            <i class="la la-barcode mr-1"></i>Lire
                                        </button>
                                        <button type="button" class="btn btn-outline-warning btn-sm"
                                                onclick="lancerReset()">
                                            <i class="la la-redo mr-1"></i>Redémarrer
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                                onclick="viderChamps()">
                                            <i class="la la-times mr-1"></i>Vider
                                        </button>
                                    </div>
                                    <div class="reader-log" id="rLog">Vérification du lecteur…</div>
                                </div>
                            </div>
                        </div>

                        {{-- Doublon --}}
                        <div class="doublon-banner" id="doublonBanner">
                            <i class="la la-exclamation-triangle"
                               style="font-size:1.3rem;flex-shrink:0;color:#ffc107;margin-top:2px;"></i>
                            <div>
                                <strong id="doublonTitre">Doublon détecté</strong><br>
                                <span id="doublonMsg" style="font-size:13px;"></span>
                                <a id="doublonLien" href="#" target="_blank"
                                   class="btn btn-sm btn-warning ml-2 py-0" style="font-size:12px;">
                                    Voir la fiche <i class="la la-external-link ml-1"></i>
                                </a>
                            </div>
                        </div>

                        @if($errors->has('doublon'))
                            <div class="alert alert-danger border-0 mb-3 d-flex align-items-center"
                                 style="border-radius:8px;">
                                <i class="la la-ban mr-2" style="font-size:1.2rem;"></i>
                                <div>
                                    {{ $errors->first('doublon') }}
                                    @if(session('doublon_id'))
                                        <a href="{{ route('impetrants.demandes', session('doublon_id')) }}"
                                           class="btn btn-sm btn-danger ml-2">
                                            Voir la fiche existante
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- État civil --}}
                        <div class="sec-title">
                            <i class="la la-user"></i> État civil
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label-sm">
                                    Nom <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nom" id="fNom"
                                       class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                       value="{{ old('nom') }}"
                                       placeholder="NOM DE FAMILLE"
                                       style="text-transform:uppercase; font-weight:700; letter-spacing:.03em;"
                                       oninput="this.value=this.value.toUpperCase();deferred_doublon()">
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label-sm">
                                    Prénom(s) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="prenom" id="fPrenom"
                                       class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                       value="{{ old('prenom') }}"
                                       placeholder="Prénom(s)"
                                       oninput="deferred_doublon()">
                                @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label-sm">
                                    Sexe <span class="text-danger">*</span>
                                </label>
                                <select name="sexe" id="fSexe"
                                        class="form-control form-control-sm select2-sm @error('sexe') is-invalid @enderror"
                                        onchange="deferred_doublon()">
                                    <option value="">— Sélectionner —</option>
                                    <option value="Masculin"  {{ old('sexe')=='Masculin' ?'selected':'' }}>Masculin</option>
                                    <option value="Féminin"   {{ old('sexe')=='Féminin'  ?'selected':'' }}>Féminin</option>
                                </select>
                                @error('sexe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 form-group mb-3">
                                <label class="form-label-sm">
                                    Date de naissance <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="date_naissance" id="fDN"
                                       class="form-control form-control-sm @error('date_naissance') is-invalid @enderror"
                                       value="{{ old('date_naissance') }}"
                                       onchange="deferred_doublon()">
                                @error('date_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label class="form-label-sm">Lieu de naissance</label>
                                <input type="text" name="lieu_naissance" id="fLN"
                                       class="form-control form-control-sm"
                                       value="{{ old('lieu_naissance') }}"
                                       placeholder="Ville, pays">
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label class="form-label-sm">
                                    Nationalité <span class="text-danger">*</span>
                                </label>
                                <select name="nationalites_id" id="fNat"
                                        class="form-control form-control-sm select2-sm @error('nationalites_id') is-invalid @enderror"
                                        onchange="deferred_doublon()">
                                    <option value="">— Sélectionner —</option>
                                    @foreach($pays as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('nationalites_id')==$p->id?'selected':'' }}>
                                            {{ $p->nationalite ?? $p->lib_pays ?? $p->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nationalites_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label class="form-label-sm">Nom du père</label>
                                <input type="text" name="nom_pere" id="fNomPere"
                                       class="form-control form-control-sm"
                                       value="{{ old('nom_pere') }}" placeholder="Nom du père">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 form-group mb-3">
                                <label class="form-label-sm">Prénom du père</label>
                                <input type="text" name="prenom_pere" id="fPrenomPere"
                                       class="form-control form-control-sm"
                                       value="{{ old('prenom_pere') }}" placeholder="Prénom du père">
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label class="form-label-sm">Nom de la mère</label>
                                <input type="text" name="nom_mere" id="fNomMere"
                                       class="form-control form-control-sm"
                                       value="{{ old('nom_mere') }}" placeholder="Nom de la mère">
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label class="form-label-sm">Prénom de la mère</label>
                                <input type="text" name="prenom_mere" id="fPrenomMere"
                                       class="form-control form-control-sm"
                                       value="{{ old('prenom_mere') }}" placeholder="Prénom de la mère">
                            </div>
                        </div>

                        {{-- Photo --}}
                        <div class="sec-title mt-2">
                            <i class="la la-camera"></i> Photo d'identité
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div id="photoZone"
                                     class="photo-upload-zone"
                                     onclick="document.getElementById('fPhoto').click()">
                                    <img id="photoPreview"
                                         style="width:90px;height:110px;object-fit:cover;
                                                border-radius:8px;border:2px solid var(--blue);
                                                display:none;margin:0 auto 8px;">
                                    <div id="photoIconWrap">
                                        <i class="la la-camera"
                                           style="font-size:2rem;color:var(--muted);"></i>
                                        <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                                            Cliquer pour choisir
                                        </div>
                                    </div>
                                </div>
                                <input type="file" id="fPhoto" name="photo"
                                       accept="image/*" style="display:none;"
                                       onchange="aperçuPhoto(this)">
                            </div>
                            <div class="col-md-9">
                                <div class="alert alert-light border mb-0"
                                     style="border-radius:8px; font-size:12px; border-color:var(--border)!important;">
                                    <i class="la la-lightbulb text-warning mr-1"></i>
                                    <strong>Photo liée à l'impétrant.</strong>
                                    Elle sera visible sur toutes ses demandes futures.
                                    Le lecteur de passeport la remplit automatiquement
                                    si elle contient une photo biométrique.<br>
                                    <span class="text-muted">Formats acceptés : JPG, PNG · Max 3 Mo</span>
                                </div>
                            </div>
                        </div>

                        <div class="wiz-nav">
                            <div></div>
                            <button type="button" class="btn btn-primary px-4"
                                    onclick="allerEtape(2)" style="border-radius:8px;">
                                Vérifier et confirmer
                                <i class="la la-arrow-right ml-1"></i>
                            </button>
                        </div>
                    </div>{{-- /pane1 --}}

                    {{-- ══════════════════════════════════════════
                         ÉTAPE 2 — Récapitulatif
                    ══════════════════════════════════════════ --}}
                    <div class="step-pane" id="pane2">

                        <div class="alert alert-info border-0 mb-4"
                             style="border-radius:8px; font-size:13px;">
                            <i class="la la-check-circle mr-1"></i>
                            Vérifiez les informations avant d'enregistrer.
                            Cliquez <strong>Modifier</strong> pour corriger.
                        </div>

                        {{-- Carte récap --}}
                        <div class="recap-card">
                            <div id="rcAvatarWrap">
                                <img id="rcPhoto" class="recap-avatar" style="display:none;">
                                <div id="rcNoPhoto" class="recap-avatar-placeholder">
                                    <i class="la la-user"></i>
                                </div>
                            </div>
                            <div class="recap-nom" id="rcNom">—</div>
                            <span class="recap-nat" id="rcNat">—</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="sec-title">
                                    <i class="la la-user"></i> Identité
                                </div>
                                <div class="recap-row">
                                    <span class="recap-key">Date de naissance</span>
                                    <span class="recap-val" id="rcDN">—</span>
                                </div>
                                <div class="recap-row">
                                    <span class="recap-key">Lieu de naissance</span>
                                    <span class="recap-val" id="rcLN">—</span>
                                </div>
                                <div class="recap-row">
                                    <span class="recap-key">Sexe</span>
                                    <span class="recap-val" id="rcSexe">—</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="sec-title">
                                    <i class="la la-users"></i> Filiation
                                </div>
                                <div class="recap-row">
                                    <span class="recap-key">Père</span>
                                    <span class="recap-val" id="rcPere">—</span>
                                </div>
                                <div class="recap-row">
                                    <span class="recap-key">Mère</span>
                                    <span class="recap-val" id="rcMere">—</span>
                                </div>
                            </div>
                        </div>

                        {{-- Champs cachés document --}}
                        <input type="hidden" name="numero_document"   id="h_num_doc">
                        <input type="hidden" name="date_delivrance"   id="h_date_deliv">
                        <input type="hidden" name="date_expiration"   id="h_date_exp">
                        <input type="hidden" name="h_mrz"             id="h_mrz">
                        <input type="hidden" name="h_source_doc"      id="h_source_doc" value="manuel">

                        <div class="wiz-nav">
                            <button type="button"
                                    class="btn btn-outline-secondary px-4"
                                    onclick="allerEtape(1)"
                                    style="border-radius:8px;">
                                <i class="la la-arrow-left mr-1"></i> Modifier
                            </button>
                            <button type="submit" class="btn-enreg" id="btnSubmit">
                                <i class="la la-save"></i> Enregistrer l'impétrant
                            </button>
                        </div>
                    </div>{{-- /pane2 --}}

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- ══════════════════════════════════════════════════════════
     SCRIPTS — logique identique, aucune modification
══════════════════════════════════════════════════════════ --}}
<script>
var READER_URL   = 'http://127.0.0.1:8085';
var _URL_DOUBLON = "{{ route('impetrants.api.check-doublon') }}";
var _CSRF        = "{{ csrf_token() }}";
var _ETAPE       = 1;
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
function aperçuPhoto(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        showPhotoPreview(e.target.result);
        document.querySelector('#photoZone #photoIconWrap').style.display = 'none';
        document.getElementById('photoZone').classList.add('has-photo');
    };
    reader.readAsDataURL(input.files[0]);
}

function showPhotoPreview(src) {
    var prev = document.getElementById('photoPreview');
    prev.src = src;
    prev.style.display = 'block';
}

function allerEtape(n) {
    if (n > _ETAPE && !valider()) return;
    document.querySelectorAll('.step-pane').forEach(function(p){ p.classList.remove('active'); });
    document.getElementById('pane' + n).classList.add('active');
    for (var i = 1; i <= 2; i++) {
        var el = document.getElementById('ps' + i);
        el.classList.remove('active','done');
        if (i < n)        el.classList.add('done');
        else if (i === n) el.classList.add('active');
    }
    _ETAPE = n;
    if (n === 2) buildRecap();
    window.scrollTo({top:0,behavior:'smooth'});
}

function valider() {
    var nom  = document.getElementById('fNom').value.trim();
    var pre  = document.getElementById('fPrenom').value.trim();
    var sexe = document.getElementById('fSexe').value;
    var dn   = document.getElementById('fDN').value;
    var nat  = document.getElementById('fNat').value;
    if (!nom || !pre || !sexe || !dn || !nat) {
        toastr.warning('Nom, prénom, sexe, date de naissance et nationalité sont obligatoires.');
        return false;
    }
    return true;
}

function buildRecap() {
    var nom = document.getElementById('fNom').value;
    var pre = document.getElementById('fPrenom').value;
    document.getElementById('rcNom').textContent = nom + ' ' + pre;
    var natSel = document.getElementById('fNat');
    document.getElementById('rcNat').textContent =
        natSel.options[natSel.selectedIndex] ? natSel.options[natSel.selectedIndex].text : '—';
    set('rcDN',   document.getElementById('fDN').value   || '—');
    set('rcLN',   document.getElementById('fLN').value   || '—');
    set('rcSexe', document.getElementById('fSexe').value || '—');
    var pere = (document.getElementById('fNomPere').value   + ' ' + document.getElementById('fPrenomPere').value).trim();
    var mere = (document.getElementById('fNomMere').value   + ' ' + document.getElementById('fPrenomMere').value).trim();
    set('rcPere', pere || '—');
    set('rcMere', mere || '—');
    var prev = document.getElementById('photoPreview');
    var rcPh = document.getElementById('rcPhoto');
    var rcNo = document.getElementById('rcNoPhoto');
    if (prev && prev.src && prev.style.display !== 'none') {
        rcPh.src = prev.src; rcPh.style.display = 'block'; rcNo.style.display = 'none';
    } else {
        rcPh.style.display = 'none'; rcNo.style.display = 'flex';
    }
}
function set(id, v) { var el=document.getElementById(id); if(el) el.textContent=v; }

var _dt = null;
function deferred_doublon() { clearTimeout(_dt); _dt = setTimeout(checkDoublon, 650); }
function checkDoublon() {
    var nom  = document.getElementById('fNom').value.trim();
    var pre  = document.getElementById('fPrenom').value.trim();
    var sexe = document.getElementById('fSexe').value;
    var dn   = document.getElementById('fDN').value;
    var nat  = document.getElementById('fNat').value;
    if (!nom || !pre || !sexe || !dn || !nat) {
        document.getElementById('doublonBanner').style.display = 'none'; return;
    }
    $.post(_URL_DOUBLON, {
        _token: _CSRF, nom: nom, prenom: pre,
        sexe: sexe, date_naissance: dn, nationalites_id: nat
    }, function(r) {
        var b = document.getElementById('doublonBanner');
        if (r.doublon) {
            document.getElementById('doublonTitre').textContent = '⚠ Impétrant déjà enregistré !';
            document.getElementById('doublonMsg').textContent =
                r.nom + ' existe déjà dans le système. Inutile de le recréer.';
            document.getElementById('doublonLien').href = '/impetrants/' + r.id + '/demandes';
            b.classList.add('bloquant'); b.style.display = 'flex';
        } else { b.style.display = 'none'; }
    });
}

function setLed(etat, badge, html) {
    document.getElementById('rLed').className = 'led ' + etat;
    var bdg = document.getElementById('rBadge');
    bdg.textContent = badge;
    bdg.className = 'badge ml-1 ' + (etat==='ok'?'badge-success':etat==='busy'?'badge-warning':etat==='err'?'badge-danger':'badge-secondary');
    var colors = {'ok':'#28D094','busy':'#FF9149','err':'#FF4961'};
    document.getElementById('rLog').innerHTML =
        '<span style="color:'+(colors[etat]||'#8898aa')+'">' + html + '</span>';
}

function lancerLecture() {
    document.getElementById('btnLire').disabled = true;
    setLed('busy','Lecture…','<i class="la la-spinner la-spin"></i> Lecture en cours… Posez le document sur le lecteur');
    document.getElementById('rPhotoImg').style.display = 'none';
    document.getElementById('rPhotoIcon').style.display = '';
    $.ajax({
        url: READER_URL + '/read', method: 'GET', timeout: 120000,
        success: function(data) {
            if (data.status === 'success') {
                remplirFormulaire(data);
            } else {
                setLed('err','Erreur','<i class="la la-times-circle"></i> ' + (data.message||'Erreur inconnue'));
                document.getElementById('btnLire').disabled = false;
            }
        },
        error: function() {
            setLed('err','Indisponible','<i class="la la-times-circle"></i> Service non disponible. Vérifiez que le programme Java tourne.');
            document.getElementById('btnLire').disabled = false;
        }
    });
}

function lancerReset() {
    document.getElementById('btnLire').disabled = true;
    setLed('busy','Reset…','<i class="la la-refresh la-spin"></i> Réinitialisation…');
    $.ajax({
        url: READER_URL + '/restart', method: 'GET', timeout: 10000,
        success: function() {
            setTimeout(function() {
                setLed('ok','Redémarré','<i class="la la-check-circle"></i> Lecteur réinitialisé !');
                document.getElementById('btnLire').disabled = false;
            }, 3000);
        },
        error: function() {
            setLed('err','Erreur','<i class="la la-times-circle"></i> Erreur réinitialisation');
            document.getElementById('btnLire').disabled = false;
        }
    });
}

function viderChamps() {
    ['fNom','fPrenom','fDN','fLN','fNomPere','fPrenomPere','fNomMere','fPrenomMere']
        .forEach(function(id){ document.getElementById(id).value = ''; });
    document.getElementById('fSexe').value = '';
    $('#fSexe').trigger('change.select2');
    document.getElementById('fNat').value = '';
    $('#fNat').trigger('change.select2');
    document.getElementById('rPhotoImg').style.display = 'none';
    document.getElementById('rPhotoIcon').style.display = '';
    document.getElementById('doublonBanner').style.display = 'none';
    document.getElementById('photoZone').classList.remove('has-photo');
    setLed('','—','Champs effacés.');
    toastr.info('Champs effacés.');
}

function remplirFormulaire(data) {
    if (data.nom)           document.getElementById('fNom').value    = data.nom;
    if (data.prenoms)       document.getElementById('fPrenom').value  = data.prenoms;
    if (data.naissance)     document.getElementById('fDN').value      = data.naissance;
    if (data.lieu_naissance && data.lieu_naissance !== '')
        document.getElementById('fLN').value = data.lieu_naissance;
    if (data.sexe) {
        document.getElementById('fSexe').value = (data.sexe === 'M') ? 'Masculin' : 'Féminin';
        $('#fSexe').trigger('change.select2');
    }
    if (data.nationalite) {
        $.get('/api/passport/pays', function(pays) {
            var code = data.nationalite.toUpperCase();
            if (pays[code]) $('#fNat').val(pays[code].id).trigger('change.select2');
        });
    }
    if (data.photo_base64 && data.photo_base64.length > 100) {
        var imgSrc = 'data:image/jpeg;base64,' + data.photo_base64;
        var rImg = document.getElementById('rPhotoImg');
        rImg.src = imgSrc; rImg.style.display = 'block';
        document.getElementById('rPhotoIcon').style.display = 'none';
        document.getElementById('rPhotoWrap').classList.add('loaded');
        try {
            var b = atob(data.photo_base64), ab = new ArrayBuffer(b.length),
                ia = new Uint8Array(ab);
            for (var i=0;i<b.length;i++) ia[i]=b.charCodeAt(i);
            var dt = new DataTransfer();
            dt.items.add(new File([new Blob([ab],{type:'image/jpeg'})],'passport_photo.jpg',{type:'image/jpeg'}));
            document.getElementById('fPhoto').files = dt.files;
        } catch(e) {}
        showPhotoPreview(imgSrc);
        document.querySelector('#photoZone #photoIconWrap').style.display = 'none';
        document.getElementById('photoZone').classList.add('has-photo');
        setLed('ok','OK',
            '<i class="la la-check-circle"></i> <strong>Document lu !</strong>' +
            (data.source_photo ? ' <small>Source: '+data.source_photo+'</small>' : ''));
    } else {
        setLed('ok','OK','<i class="la la-check-circle"></i> Données lues.');
    }
    if (data.num_doc)    document.getElementById('h_num_doc').value   = data.num_doc;
    if (data.expiration) document.getElementById('h_date_exp').value  = data.expiration;
    if (data.mrz)        document.getElementById('h_mrz').value       = data.mrz;
    document.getElementById('h_source_doc').value = 'lecteur';
    document.getElementById('btnLire').disabled = false;
    toastr.success('Formulaire rempli automatiquement !', 'Lecteur passeport');
    deferred_doublon();
}
</script>

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2-sm').select2({ width: '100%' });
    $.ajax({
        url: READER_URL + '/status', method: 'GET', timeout: 2000,
        success: function() {
            setLed('ok','Connecté','<i class="la la-check-circle"></i> Lecteur connecté');
        },
        error: function() {
            setLed('err','Non démarré','<i class="la la-exclamation-triangle"></i> Service lecteur non démarré');
        }
    });
    $('#frmImp').on('submit', function() {
        document.getElementById('btnSubmit').disabled = true;
    });
});
</script>
@endpush