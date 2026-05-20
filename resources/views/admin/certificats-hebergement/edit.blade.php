@extends('admin.layouts.app')

@section('title')
    Modifier — {{ $certificat->numero_certificat }}
@endsection

@section('styles')
<style>
.form-label-required::after { content: ' *'; color: #FF4961; }
.card-section { border: 1px solid #e4e9f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
.card-section-title {
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1px; color: #6c757d; margin-bottom: 16px;
    padding-bottom: 8px; border-bottom: 2px solid #f0f2f5;
}
.edit-badge {
    background: #FF9149; color: #fff; padding: 4px 12px;
    border-radius: 20px; font-size: 12px; font-weight: 600;
}
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <h3 class="content-header-title">
                    Modifier le Certificat
                    <span class="edit-badge ml-2">{{ $certificat->numero_certificat }}</span>
                </h3>
            </div>
            <div class="content-header-right col-md-4 col-12 d-flex justify-content-end">
                <a href="{{ route('certificats-hebergement.show', $certificat->id) }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="la la-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="content-body">
            <form action="{{ route('certificats-hebergement.update', $certificat->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ── HÉBERGEUR ─────────────────────────────────────────── --}}
                <div class="card-section">
                    <div class="card-section-title">
                        <i class="la la-home"></i> Informations de l'Hébergeur
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Nom</label>
                            <input type="text" name="hebergeur_nom" class="form-control @error('hebergeur_nom') is-invalid @enderror"
                                   value="{{ old('hebergeur_nom', $certificat->hebergeur_nom) }}">
                            @error('hebergeur_nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Prénom</label>
                            <input type="text" name="hebergeur_prenom" class="form-control @error('hebergeur_prenom') is-invalid @enderror"
                                   value="{{ old('hebergeur_prenom', $certificat->hebergeur_prenom) }}">
                            @error('hebergeur_prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Sexe</label>
                            <select name="hebergeur_sexe" class="form-control">
                                <option value="Masculin" {{ old('hebergeur_sexe', $certificat->hebergeur_sexe) == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                <option value="Féminin"  {{ old('hebergeur_sexe', $certificat->hebergeur_sexe) == 'Féminin'  ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Date de naissance</label>
                            <input type="date" name="hebergeur_date_naissance" class="form-control"
                                   value="{{ old('hebergeur_date_naissance', $certificat->hebergeur_date_naissance?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Lieu de naissance</label>
                            <input type="text" name="hebergeur_lieu_naissance" class="form-control"
                                   value="{{ old('hebergeur_lieu_naissance', $certificat->hebergeur_lieu_naissance) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Nationalité</label>
                            <input type="text" name="hebergeur_nationalite" class="form-control"
                                   value="{{ old('hebergeur_nationalite', $certificat->hebergeur_nationalite) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Type de document</label>
                            <select name="hebergeur_type_document" class="form-control">
                                @foreach($typesDocuments as $td)
                                    <option value="{{ $td }}" {{ old('hebergeur_type_document', $certificat->hebergeur_type_document) == $td ? 'selected' : '' }}>{{ $td }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Numéro du document</label>
                            <input type="text" name="hebergeur_numero_document" class="form-control"
                                   value="{{ old('hebergeur_numero_document', $certificat->hebergeur_numero_document) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Téléphone</label>
                            <input type="text" name="hebergeur_telephone" class="form-control"
                                   value="{{ old('hebergeur_telephone', $certificat->hebergeur_telephone) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Email</label>
                            <input type="email" name="hebergeur_email" class="form-control"
                                   value="{{ old('hebergeur_email', $certificat->hebergeur_email) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Profession</label>
                            <input type="text" name="hebergeur_profession" class="form-control"
                                   value="{{ old('hebergeur_profession', $certificat->hebergeur_profession) }}">
                        </div>
                    </div>

                    <div class="card-section-title mt-3">Adresse de l'Hébergeur</div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Département</label>
                            <select name="departement_id" id="departement_id" class="form-control">
                                <option value="">-- Sélectionner --</option>
                                @foreach($departements as $dep)
                                    <option value="{{ $dep->id }}"
                                        {{ $dep->id == $certificat->quartierHebergeur?->arrondissement?->departements_id ? 'selected' : '' }}>
                                        {{ $dep->lib_departement }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Arrondissement</label>
                            <select name="arrondissement_id" id="arrondissement_id" class="form-control">
                                <option value="{{ $certificat->quartierHebergeur?->arrondissement?->id }}">
                                    {{ $certificat->quartierHebergeur?->arrondissement?->lib_arrondissement }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Quartier</label>
                            <select name="hebergeur_quartiers_id" id="hebergeur_quartiers_id" class="form-control">
                                <option value="{{ $certificat->hebergeur_quartiers_id }}">
                                    {{ $certificat->quartierHebergeur?->lib_quartier }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label-required">Avenue / Rue</label>
                            <input type="text" name="hebergeur_avenue_rue" class="form-control"
                                   value="{{ old('hebergeur_avenue_rue', $certificat->hebergeur_avenue_rue) }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label-required">Numéro</label>
                            <input type="text" name="hebergeur_numero_adresse" class="form-control"
                                   value="{{ old('hebergeur_numero_adresse', $certificat->hebergeur_numero_adresse) }}">
                        </div>
                    </div>
                </div>

                {{-- ── HÉBERGÉ ────────────────────────────────────────────── --}}
                <div class="card-section">
                    <div class="card-section-title">
                        <i class="la la-user"></i> Informations de l'Hébergé
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Nom</label>
                            <input type="text" name="heberge_nom" class="form-control"
                                   value="{{ old('heberge_nom', $certificat->heberge_nom) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Prénom</label>
                            <input type="text" name="heberge_prenom" class="form-control"
                                   value="{{ old('heberge_prenom', $certificat->heberge_prenom) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Sexe</label>
                            <select name="heberge_sexe" class="form-control">
                                <option value="Masculin" {{ old('heberge_sexe', $certificat->heberge_sexe) == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                <option value="Féminin"  {{ old('heberge_sexe', $certificat->heberge_sexe) == 'Féminin'  ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Date de naissance</label>
                            <input type="date" name="heberge_date_naissance" class="form-control"
                                   value="{{ old('heberge_date_naissance', $certificat->heberge_date_naissance?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Lieu de naissance</label>
                            <input type="text" name="heberge_lieu_naissance" class="form-control"
                                   value="{{ old('heberge_lieu_naissance', $certificat->heberge_lieu_naissance) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Nationalité</label>
                            <input type="text" name="heberge_nationalite" class="form-control"
                                   value="{{ old('heberge_nationalite', $certificat->heberge_nationalite) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Type de document</label>
                            <select name="heberge_type_document" class="form-control">
                                @foreach($typesDocuments as $td)
                                    <option value="{{ $td }}" {{ old('heberge_type_document', $certificat->heberge_type_document) == $td ? 'selected' : '' }}>{{ $td }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-required">Numéro du document</label>
                            <input type="text" name="heberge_numero_document" class="form-control"
                                   value="{{ old('heberge_numero_document', $certificat->heberge_numero_document) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Téléphone</label>
                            <input type="text" name="heberge_telephone" class="form-control"
                                   value="{{ old('heberge_telephone', $certificat->heberge_telephone) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Email</label>
                            <input type="email" name="heberge_email" class="form-control"
                                   value="{{ old('heberge_email', $certificat->heberge_email) }}">
                        </div>
                    </div>
                </div>

                {{-- ── SÉJOUR ─────────────────────────────────────────────── --}}
                <div class="card-section">
                    <div class="card-section-title">
                        <i class="la la-calendar"></i> Informations du Séjour
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="form-label-required">Date d'arrivée prévue</label>
                            <input type="date" name="date_arrivee_prevue" id="date_arrivee_prevue" class="form-control"
                                   value="{{ old('date_arrivee_prevue', $certificat->date_arrivee_prevue?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label-required">Date de départ prévue</label>
                            <input type="date" name="date_depart_prevue" id="date_depart_prevue" class="form-control"
                                   value="{{ old('date_depart_prevue', $certificat->date_depart_prevue?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Durée (jours)</label>
                            <input type="text" id="duree_sejour_affichage" class="form-control" readonly
                                   style="background:#f8f9fa;">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label-required">Type de relation</label>
                            <select name="type_relation" id="type_relation_select" class="form-control">
                                <option value="Famille"       {{ old('type_relation', $certificat->type_relation) == 'Famille'       ? 'selected' : '' }}>Famille</option>
                                <option value="Ami"           {{ old('type_relation', $certificat->type_relation) == 'Ami'           ? 'selected' : '' }}>Ami(e)</option>
                                <option value="Professionnel" {{ old('type_relation', $certificat->type_relation) == 'Professionnel' ? 'selected' : '' }}>Relation professionnelle</option>
                                <option value="Autre"         {{ old('type_relation', $certificat->type_relation) == 'Autre'         ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group" id="precision_relation_div"
                             style="{{ old('type_relation', $certificat->type_relation) == 'Autre' ? '' : 'display:none;' }}">
                            <label>Précision</label>
                            <input type="text" name="precision_relation" class="form-control"
                                   value="{{ old('precision_relation', $certificat->precision_relation) }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Motif du séjour</label>
                            <textarea name="motif_sejour" class="form-control" rows="2">{{ old('motif_sejour', $certificat->motif_sejour) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ── BOUTONS ────────────────────────────────────────────── --}}
                <div class="d-flex justify-content-between mb-4">
                    <a href="{{ route('certificats-hebergement.show', $certificat->id) }}" class="btn btn-secondary">
                        <i class="la la-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="la la-save"></i> Enregistrer les modifications
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    function calculerDuree() {
        var d1 = $('#date_arrivee_prevue').val();
        var d2 = $('#date_depart_prevue').val();
        if (d1 && d2) {
            var diff = Math.round((new Date(d2) - new Date(d1)) / 86400000);
            $('#duree_sejour_affichage').val(diff > 0 ? diff + ' jour(s)' : 'Date invalide');
        }
    }
    $('#date_arrivee_prevue, #date_depart_prevue').on('change', calculerDuree);
    calculerDuree();

    $('#type_relation_select').on('change', function() {
        $('#precision_relation_div').toggle($(this).val() === 'Autre');
    });

    // Sélects dépendants
    var allArrondissements = @json(\App\Models\Arrondissement::orderBy('lib_arrondissement')->get());
    var allQuartiers       = @json(\App\Models\Quartier::orderBy('lib_quartier')->get());

    $('#departement_id').on('change', function() {
        var depId = parseInt($(this).val());
        var arr   = allArrondissements.filter(function(a) { return a.departements_id === depId; });
        var opts  = '<option value="">-- Sélectionner --</option>';
        arr.forEach(function(a) { opts += '<option value="' + a.id + '">' + a.lib_arrondissement + '</option>'; });
        $('#arrondissement_id').html(opts);
        $('#hebergeur_quartiers_id').html('<option value="">-- Sélectionner --</option>');
    });

    $('#arrondissement_id').on('change', function() {
        var arrId = parseInt($(this).val());
        var qts   = allQuartiers.filter(function(q) { return q.arrondissements_id === arrId; });
        var opts  = '<option value="">-- Sélectionner --</option>';
        qts.forEach(function(q) { opts += '<option value="' + q.id + '">' + q.lib_quartier + '</option>'; });
        $('#hebergeur_quartiers_id').html(opts);
    });
});
</script>
@endpush
