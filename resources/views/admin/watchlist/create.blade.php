@extends('admin.layouts.app')

@section('title')
Inscription Watchlist
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
<style>
    .form-section { border-bottom: 1px solid #dfdfdf; padding-bottom: 10px; margin-bottom: 20px; color: #1e9ff2; font-weight: bold; }
    .nav-tabs .nav-link.active { background-color: #1e9ff2; color: white !important; border-radius: 5px; }
    #img-preview { max-height: 200px; border: 2px dashed #ddd; padding: 5px; background-color: #f8f8f8; }
    .age-toggle-container { background: #f4f7f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Enregistrement au registre de surveillance (Watchlist)</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('watchlist.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type_source" id="type_source" value="impetrant">

                                    <ul class="nav nav-tabs mb-3" id="intelTab" role="tablist">
                                        <li class="nav-item">
                                            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#search" role="tab">
                                                <i class="la la-search"></i> Recherche dans la Base
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#manual" role="tab">
                                                <i class="la la-user-plus"></i> Création Manuelle (Profilage)
                                            </button>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        {{-- ================= MODE RECHERCHE ================= --}}
                                        <div class="tab-pane fade show active" id="search" role="tabpanel">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Rechercher un impétrant existant</label>
                                                <select id="impetrant_ajax" name="impetrant_id" class="form-control"></select>
                                                <small class="text-muted">Saisissez le nom ou le numéro de document pour rechercher.</small>
                                            </div>
                                        </div>

                                        {{-- ================= MODE MANUEL / PROFILAGE ================= --}}
                                        <div class="tab-pane fade" id="manual" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h4 class="form-section"><i class="la la-user"></i> Identité du sujet</h4>
                                                    
                                                    <div class="form-group row">
                                                        <label class="col-md-4">Nom & Prénom</label>
                                                        <div class="col-md-4">
                                                            <input name="nom" class="form-control" placeholder="NOM">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input name="prenom" class="form-control" placeholder="Prénom">
                                                        </div>
                                                    </div>

                                                    <div class="age-toggle-container">
                                                        <div class="form-group row">
                                                            <label class="col-md-4 font-small-3">Détermination de l'âge</label>
                                                            <div class="col-md-8">
                                                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                                    <label class="btn btn-outline-primary active btn-sm">
                                                                        <input type="radio" name="age_type" id="type_date" checked> Date de naissance exacte
                                                                    </label>
                                                                    <label class="btn btn-outline-primary btn-sm">
                                                                        <input type="radio" name="age_type" id="type_estimation"> Estimation visuelle
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="section_date_naissance" class="form-group row mb-0">
                                                            <label class="col-md-4">Date de naissance</label>
                                                            <div class="col-md-8">
                                                                <input type="date" name="date_naissance" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div id="section_estimation_age" class="form-group row d-none mb-0">
                                                            <label class="col-md-4">Tranche d'âge</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <span class="input-group-text">Entre</span>
                                                                    <input type="number" name="age_min" class="form-control" placeholder="Min">
                                                                    <span class="input-group-text">et</span>
                                                                    <input type="number" name="age_max" class="form-control" placeholder="Max">
                                                                    <span class="input-group-text">ans</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4">Sexe & Nationalité</label>
                                                        <div class="col-md-4">
                                                            <select name="sexe" class="form-control">
                                                                <option value="">Sexe...</option>
                                                                <option value="Masculin">Masculin</option>
                                                                <option value="Féminin">Féminin</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select name="pays_naissance" class="select2 form-control">
                                                                <option value="">Nationalité...</option>
                                                                @foreach($countries as $country)
                                                                    <option value="{{ $country->lib_pays }}">{{ $country->lib_pays }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <h4 class="form-section"><i class="la la-map-marker"></i> Localisation</h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Département</label>
                                                                <select id="dep-select" name="departement_id" class="form-control select2">
                                                                    <option value="">Sélectionner</option>
                                                                    @foreach($departements as $d)
                                                                        <option value="{{ $d->id }}">{{ $d->lib_departement }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Arrondissement / Commune</label>
                                                                <select id="com-select" name="commune_id" class="form-control">
                                                                    <option value="">---</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Quartier / Village</label>
                                                                <select id="quart-select" name="quartier_id" class="form-control">
                                                                    <option value="">---</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Rue / Avenue</label>
                                                                <input name="adresse_rue" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>N°</label>
                                                                <input name="numero_domicile" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h4 class="form-section"><i class="la la-users"></i> Filiation</h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="small">Père</label>
                                                            <input name="nom_pere" class="form-control mb-1" placeholder="Nom du père">
                                                            <input name="prenom_pere" class="form-control" placeholder="Prénom du père">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small">Mère</label>
                                                            <input name="nom_mere" class="form-control mb-1" placeholder="Nom de la mère">
                                                            <input name="prenom_mere" class="form-control" placeholder="Prénom de la mère">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 text-center">
                                                    <h4 class="form-section"><i class="la la-camera"></i> Photo de profil</h4>
                                                    <div class="form-group">
                                                        <input type="file" name="photo_profil" id="photo_input" class="form-control">
                                                        <div class="mt-2">
                                                            <img id="img-preview" src="{{ asset('res/app-assets/images/portrait/small/default.png') }}" class="img-fluid rounded shadow-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h4 class="form-section text-danger"><i class="la la-warning"></i> Classification du risque</h4>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Niveau de menace</label>
                                                <select name="niveau_risque" class="form-control">
                                                    <option value="1">🟢 Niveau 1 (Faible)</option>
                                                    <option value="2">🟡 Niveau 2 (Modéré)</option>
                                                    <option value="3">🔴 Niveau 3 (Élevé)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Motif de l'inscription *</label>
                                                <textarea name="motif" class="form-control" rows="3" required placeholder="Pourquoi ce sujet est-il surveillé ?"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{route('watchlist.index')}}" class="btn btn-warning mr-1">Retour</a>
                                        <button type="submit" class="btn btn-primary shadow">
                                            <i class="la la-save"></i> Enregistrer le profil
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('res/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function(){
    // Select2 par défaut
    $('.select2').select2({ width: '100%' });

    // Recherche Impétrant Ajax
    $('#impetrant_ajax').select2({
        placeholder: "Rechercher...",
        minimumInputLength: 2,
        width: '100%',
        ajax: {
            url: "{{ route('impetrants.search.ajax') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) { return { q: params.term }; },
            processResults: function(data) { return { results: data }; }
        }
    });

    // Toggle entre Mode Recherche et Manuel
    $('#intelTab button').on('shown.bs.tab', function (e){
        var target = $(e.target).data('bs-target');
        $('#type_source').val(target === '#manual' ? 'manuel' : 'impetrant');
    });

    // --- LOGIQUE HYBRIDE AGE (DATE vs ESTIMATION) ---
    $('input[name="age_type"]').on('change', function() {
        if ($('#type_date').is(':checked')) {
            $('#section_date_naissance').removeClass('d-none');
            $('#section_estimation_age').addClass('d-none');
            $('input[name="age_min"], input[name="age_max"]').val('');
        } else {
            $('#section_date_naissance').addClass('d-none');
            $('#section_estimation_age').removeClass('d-none');
            $('input[name="date_naissance"]').val('');
        }
    });

    // Chaînage Géo
    $('#dep-select').on('change', function () {
        var id = $(this).val();
        $('#com-select').empty().append("<option value=''>Chargement...</option>");
        if (id) {
            let route = "{{ route('departements.arrondissements', ':id') }}".replace(':id', id);
            $.get(route, function (data) {
                let out = "<option value=''>Sélectionner</option>";
                data.forEach(item => { out += `<option value="${item.id}">${item.lib_arrondissement}</option>`; });
                $('#com-select').empty().append(out);
            });
        }
    });

    $('#com-select').on('change', function () {
        var id = $(this).val();
        $('#quart-select').empty().append("<option value=''>Chargement...</option>");
        if (id) {
            let route = "{{ route('arrondissements.quartiers', ':id') }}".replace(':id', id);
            $.get(route, function (data) {
                let out = "<option value=''>Sélectionner</option>";
                data.forEach(item => { out += `<option value="${item.id}">${item.lib_quartier}</option>`; });
                $('#quart-select').empty().append(out);
            });
        }
    });

    // Preview Photo
    $('#photo_input').change(function(){
        let file = this.files[0];
        if(file){
            let reader = new FileReader();
            reader.onload = function(e){ $('#img-preview').attr('src', e.target.result); }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection