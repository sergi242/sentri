{{-- ================= PRECHECK DEMANDE MODAL ================= --}}
<div class="modal fade" id="precheckModal" tabindex="-1" role="dialog" aria-labelledby="precheckModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="precheckModalLabel">
                    Vérification préalable du document
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                {{-- ── SECTION 1 : Vérification document existant ── --}}
                <div class="alert alert-info mb-2">
                    Entrez un numéro existant pour vérifier si une demande est déjà enregistrée.
                    <br>
                    <small>(Passeport, N° fiche, Visa ou ancienne carte)</small>
                </div>

                {{-- ── Lecteur passeport ── --}}
                <div class="card border-0 bg-light mb-3" id="precheck-lecteur-zone">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                            <div class="d-flex align-items-center" style="gap:10px;">
                                <span id="precheck-passport-status" class="badge badge-secondary px-2 py-1">
                                    <i class="la la-circle"></i> Lecteur
                                </span>
                                <small class="text-muted">Lire le passeport pour remplir automatiquement</small>
                            </div>
                            <div class="d-flex" style="gap:6px;">
                                <button type="button" id="btn-precheck-lire" class="btn btn-sm btn-primary">
                                    <i class="la la-id-card"></i> Lire passeport
                                </button>
                                <button type="button" id="btn-precheck-restart" class="btn btn-sm btn-warning">
                                    <i class="la la-refresh"></i>
                                </button>
                            </div>
                        </div>
                        {{-- Photo biométrique --}}
                        <div id="precheck-photo-preview" class="mt-2 text-center" style="display:none;">
                            <img id="precheck-photo-img" src="" alt="Photo"
                                 style="height:90px; width:72px; object-fit:cover;
                                        border-radius:4px; border:2px solid #28D094;">
                        </div>
                    </div>
                </div>

                {{-- Saisie manuelle --}}
                <div class="form-group row mb-2">
                    <label class="col-md-4 col-form-label">Type de document</label>
                    <div class="col-md-8">
                        <select id="precheck_type" class="form-control">
                            <option value="">— Sélectionner —</option>
                            <option value="PASSEPORT">Passeport</option>
                            <option value="NUM_FICHE">Numéro de fiche</option>
                            <option value="VISA">Visa</option>
                            <option value="CRT">Ancienne carte (CRT)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label class="col-md-4 col-form-label">Numéro</label>
                    <div class="col-md-8">
                        <input type="text" id="precheck_value" class="form-control"
                               placeholder="Entrer le numéro ou utiliser le lecteur ci-dessus">
                    </div>
                </div>

                <div class="text-right mb-2">
                    <button type="button" id="btnPrecheck" class="btn btn-primary">
                        <i class="la la-search"></i> Vérifier
                    </button>
                </div>

                <hr>

                {{-- Résultats document --}}
                <div id="precheckResult" style="display:none">
                    <h6 class="mb-2">Résultats trouvés</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Sexe</th>
                                    <th>Type</th>
                                    <th>Numéro</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="precheckTable"></tbody>
                        </table>
                    </div>
                </div>

                <div id="precheckEmpty" class="alert alert-warning mt-2" style="display:none">
                    Aucune demande existante trouvée.
                    Vous pouvez continuer la saisie normalement.
                </div>

                {{-- ══════════════════════════════════════════════════════════
                     SECTION 2 : Certificat d'hébergement
                     ══════════════════════════════════════════════════════════ --}}
                <hr>
                <div style="border-left:4px solid #1E9FF2; padding-left:12px; margin-bottom:8px;">
                    <strong><i class="la la-building" style="color:#1E9FF2;"></i>
                    Certificat d'hébergement</strong>
                    <small class="text-muted ml-1">(optionnel)</small>
                </div>

                <div class="alert alert-light py-2 mb-3" style="border:1px solid #bee5eb; font-size:13px;">
                    <i class="la la-info-circle text-info"></i>
                    Si l'impétrant a un certificat d'hébergement, entrez son numéro.
                    Les informations seront pré-remplies automatiquement.
                </div>

                <div class="row mb-2">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text"
                                   id="precheck_cert_numero"
                                   class="form-control"
                                   placeholder="Ex: CERT-260422-0001"
                                   style="text-transform:uppercase;">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-info" id="btn-precheck-cert">
                                    <i class="la la-search"></i> Vérifier
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline-secondary btn-block" id="btn-precheck-sans-cert">
                            <i class="la la-times"></i> Sans certificat
                        </button>
                    </div>
                </div>

                {{-- Résultat certificat trouvé --}}
                <div id="precheck-cert-result" style="display:none;" class="mt-2">
                    <div class="card border-success mb-0">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="text-success mb-2">
                                        <i class="la la-check-circle"></i> Certificat trouvé — informations pré-remplies
                                    </h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Numéro</small>
                                            <strong class="d-block" id="pc-cert-numero"></strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Statut</small>
                                            <span id="pc-cert-statut" class="badge d-block mt-1" style="width:fit-content;"></span>
                                        </div>
                                        <div class="col-6 mt-2">
                                            <small class="text-muted">Hébergeur</small>
                                            <strong class="d-block" id="pc-cert-hebergeur"></strong>
                                            <small id="pc-cert-code" class="badge badge-primary"></small>
                                        </div>
                                        <div class="col-6 mt-2">
                                            <small class="text-muted">Hébergé</small>
                                            <strong class="d-block" id="pc-cert-heberge"></strong>
                                        </div>
                                        <div class="col-6 mt-2">
                                            <small class="text-muted">Arrivée prévue</small>
                                            <strong class="d-block" id="pc-cert-arrivee"></strong>
                                        </div>
                                        <div class="col-6 mt-2">
                                            <small class="text-muted">Départ prévu</small>
                                            <strong class="d-block" id="pc-cert-depart"></strong>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger ml-2"
                                        id="btn-precheck-cert-effacer">
                                    <i class="la la-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Certificat non trouvé --}}
                <div id="precheck-cert-not-found" class="alert alert-warning mt-2" style="display:none;">
                    <i class="la la-exclamation-triangle"></i>
                    Certificat introuvable. Vérifiez le numéro ou continuez sans certificat.
                    <a href="{{ route('certificats-hebergement.create') }}" target="_blank" class="ml-2">
                        <i class="la la-external-link"></i> Créer un certificat
                    </a>
                </div>

                {{-- Champs cachés --}}
                <input type="hidden" name="certificat_hebergement_id"      id="certificat_hebergement_id"      value="">
                <input type="hidden" name="heberge_impetrant_id_from_cert" id="heberge_impetrant_id_from_cert" value="">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="skipPrecheck">
                    Continuer sans vérifier
                </button>
                <button type="button" class="btn btn-primary d-none" id="btn-precheck-continuer">
                    <i class="la la-arrow-right"></i> Continuer avec ces informations
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ================= SCRIPTS ================= --}}
<script>
$(document).ready(function () {

    // Affichage automatique à l'ouverture de la page
    if (document.activeElement) document.activeElement.blur();
    $('#precheckModal').modal('show');

    // ── Statut lecteur au chargement ─────────────────────────────────────
    _precheckCheckLecteur();

    // ── LECTEUR PASSEPORT ─────────────────────────────────────────────────
    var PRECHECK_READER_URL = 'http://127.0.0.1:8085';

    function _precheckSetStatus(type, html) {
        var cls = { success:'success', info:'info', warning:'warning', danger:'danger', secondary:'secondary' };
        $('#precheck-passport-status')
            .attr('class', 'badge badge-' + (cls[type] || 'secondary') + ' px-2 py-1')
            .html(html);
    }

    function _precheckCheckLecteur() {
        $.ajax({
            url: PRECHECK_READER_URL + '/status',
            method: 'GET', timeout: 3000,
            success: function() {
                _precheckSetStatus('success', '<i class="la la-check-circle"></i> Lecteur prêt');
            },
            error: function() {
                _precheckSetStatus('secondary', '<i class="la la-circle"></i> Lecteur hors ligne');
            }
        });
    }

    $('#btn-precheck-lire').on('click', function() {
        $(this).prop('disabled', true);
        _precheckSetStatus('info', '<i class="la la-spinner la-spin"></i> Lecture en cours...');
        $('#precheck-photo-preview').hide();

        $.ajax({
            url: PRECHECK_READER_URL + '/read',
            method: 'GET',
            timeout: 120000,
            success: function(data) {
                $('#btn-precheck-lire').prop('disabled', false);
                if (data.status === 'success') {
                    _precheckRemplir(data);
                } else {
                    _precheckSetStatus('warning',
                        '<i class="la la-exclamation-triangle"></i> ' + (data.message || 'Erreur lecture'));
                    toastr.warning(data.message || 'Erreur lecture passeport', 'Lecteur');
                }
            },
            error: function() {
                $('#btn-precheck-lire').prop('disabled', false);
                _precheckSetStatus('danger', '<i class="la la-times-circle"></i> Service non disponible');
                toastr.error('Service lecteur non disponible (port 8085)', 'Lecteur');
            }
        });
    });

    $('#btn-precheck-restart').on('click', function() {
        $(this).prop('disabled', true);
        _precheckSetStatus('info', '<i class="la la-refresh la-spin"></i> Réinitialisation...');
        $.ajax({
            url: PRECHECK_READER_URL + '/restart',
            method: 'GET', timeout: 10000,
            success: function() {
                setTimeout(function() {
                    _precheckSetStatus('success', '<i class="la la-check-circle"></i> Réinitialisé !');
                    $('#btn-precheck-restart, #btn-precheck-lire').prop('disabled', false);
                }, 3000);
            },
            error: function() {
                _precheckSetStatus('danger', '<i class="la la-times-circle"></i> Erreur redémarrage');
                $('#btn-precheck-restart').prop('disabled', false);
            }
        });
    });

    function _precheckRemplir(data) {
        // ── Remplir type + numéro pour la vérification ───────────────────
        if (data.num_doc) {
            $('#precheck_type').val('PASSEPORT');
            $('#precheck_value').val(data.num_doc);
        }

        // ── Photo biométrique ─────────────────────────────────────────────
        if (data.photo_base64 && data.photo_base64.length > 100) {
            $('#precheck-photo-img').attr('src', 'data:image/jpeg;base64,' + data.photo_base64);
            $('#precheck-photo-preview').show();
        }

        _precheckSetStatus('success',
            '<i class="la la-check-circle"></i> <strong>Lu !</strong> ' +
            (data.nom ? data.nom + ' ' + (data.prenoms || '') : '')
        );
        toastr.success('Passeport lu — numéro pré-rempli', 'Lecteur');

        // ── Pré-remplir aussi les champs du formulaire principal ──────────
        if (typeof remplirFormulaire === 'function') {
            // Les vues qui ont remplirFormulaire() (create, newcrt, etc.)
            remplirFormulaire(data);
        } else {
            // Remplissage direct des champs standards
            if (data.nom)            { if ($('#nom').length)    $('#nom').val(data.nom.toUpperCase()).trigger('input'); }
            if (data.prenoms)        { if ($('#prenom').length) $('#prenom').val(data.prenoms).trigger('input'); }
            if (data.naissance)      { if ($('#date_naissance').length) $('#date_naissance').val(data.naissance).trigger('change'); }
            if (data.lieu_naissance) { if ($('#lieu_naissance').length) $('#lieu_naissance').val(data.lieu_naissance).trigger('input'); }
            if (data.num_doc)        { if ($('input[name="numero_passeport"]').length) $('input[name="numero_passeport"]').val(data.num_doc).trigger('input'); }
            if (data.expiration)     { if ($('input[name="date_expiration_passeport"]').length) $('input[name="date_expiration_passeport"]').val(data.expiration); }
            if (data.date_emission)  { if ($('input[name="date_emission_passeport"]').length) $('input[name="date_emission_passeport"]').val(data.date_emission); }
            if (data.sexe) {
                var sexeVal = (data.sexe === 'M' || data.sexe === 'Male') ? 'Masculin' : 'Féminin';
                if ($('#sexe').length) $('#sexe').val(sexeVal).trigger('change');
            }
            if (data.nationalite) {
                $.get('/api/passport/pays', function(pays) {
                    var opt = pays[data.nationalite.toUpperCase()];
                    if (opt && $('#nationalites_id').length) {
                        $('#nationalites_id').val(opt.id).trigger('change');
                        if ($.fn.select2 && $('#nationalites_id').data('select2')) {
                            $('#nationalites_id').trigger('change.select2');
                        }
                    }
                });
            }
            if (data.mrz)    { if ($('#h_mrz').length)        $('#h_mrz').val(data.mrz); }
            if (data.num_doc){ if ($('#h_source_doc').length)  $('#h_source_doc').val('lecteur'); }
        }

        // ── Lancer la vérification automatiquement après lecture ──────────
        if (data.num_doc) {
            setTimeout(function() { $('#btnPrecheck').trigger('click'); }, 400);
        }
    }

    // ── Vérification document existant ───────────────────────────────────
    $('#btnPrecheck').on('click', function () {
        var type  = $('#precheck_type').val();
        var value = $('#precheck_value').val().trim();

        if (!type || !value) {
            toastr.warning('Veuillez sélectionner le type et entrer un numéro.');
            return;
        }

        var $btn = $(this);
        $btn.html('<i class="la la-spinner la-spin"></i> Vérification...').prop('disabled', true);
        $('#precheckResult').hide();
        $('#precheckEmpty').hide();
        $('#precheckTable').html('');

        $.ajax({
            url: "{{ route('demandes.searchdocument') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                search_type: type,
                numero_document: value
            },
            success: function (html) {
                if ($(html).find('table tbody tr').length > 0) {
                    $('#precheckResult').show();
                    $('#precheckTable').html($(html).find('table tbody').html());
                } else {
                    $('#precheckEmpty').show();
                    if (type === 'PASSEPORT') $('input[name="numero_passeport"]').val(value);
                    if (type === 'NUM_FICHE')  $('input[name="uuid"]').val(value);
                    if (type === 'VISA' || type === 'CRT') $('input[name="numero_document"]').val(value);
                }
            },
            error: function () { toastr.error('Erreur lors de la recherche.'); },
            complete: function() {
                $btn.html('<i class="la la-search"></i> Vérifier').prop('disabled', false);
            }
        });
    });

    // ── Vérification certificat d'hébergement ────────────────────────────
    $('#precheck_cert_numero').on('input', function() {
        this.value = this.value.toUpperCase();
    });

    $('#precheck_cert_numero').on('keypress', function(e) {
        if (e.which === 13) { e.preventDefault(); $('#btn-precheck-cert').trigger('click'); }
    });

    $('#btn-precheck-cert').on('click', function() {
        var numero = $('#precheck_cert_numero').val().trim().toUpperCase();
        if (!numero) {
            if (typeof toastr !== 'undefined') toastr.warning('Saisissez un numéro de certificat');
            return;
        }

        var $btn = $(this);
        $btn.html('<i class="la la-spinner la-spin"></i>').prop('disabled', true);

        $.get('{{ route("certificats-hebergement.api.certificat") }}', { numero: numero })
            .done(function(data) {
                if (data.found) {
                    $('#pc-cert-numero').text(data.numero);
                    $('#pc-cert-hebergeur').text(data.nom_hebergeur);
                    $('#pc-cert-code').text(data.code_hebergeur || '');
                    $('#pc-cert-heberge').text(
                        data.heberge_nom
                            ? data.heberge_nom + ' ' + data.heberge_prenom
                            : '(non enregistré)'
                    );
                    $('#pc-cert-arrivee').text(
                        data.date_arrivee
                            ? new Date(data.date_arrivee).toLocaleDateString('fr-FR') : '—'
                    );
                    $('#pc-cert-depart').text(
                        data.date_depart
                            ? new Date(data.date_depart).toLocaleDateString('fr-FR') : '—'
                    );

                    var couleurs = {
                        'Validé':'success','En attente':'warning','Rejeté':'danger','Expiré':'secondary'
                    };
                    $('#pc-cert-statut')
                        .text(data.statut)
                        .attr('class', 'badge badge-' + (couleurs[data.statut] || 'secondary'));

                    $('#certificat_hebergement_id').val(data.id);
                    $('#heberge_impetrant_id_from_cert').val(data.heberge_id || '');
                    $('#precheck-cert-result').show();
                    $('#precheck-cert-not-found').hide();
                    $('#btn-precheck-continuer').removeClass('d-none');

                    if (data.statut !== 'Validé' && typeof toastr !== 'undefined') {
                        toastr.warning('Certificat pas encore validé. Il sera quand même lié.');
                    }
                } else {
                    $('#precheck-cert-result').hide();
                    $('#precheck-cert-not-found').show();
                    $('#certificat_hebergement_id').val('');
                }
            })
            .fail(function() {
                if (typeof toastr !== 'undefined') toastr.error('Erreur lors de la vérification');
            })
            .always(function() {
                $btn.html('<i class="la la-search"></i> Vérifier').prop('disabled', false);
            });
    });

    // Effacer certificat
    $('#btn-precheck-cert-effacer').on('click', function() {
        $('#precheck-cert-result').hide();
        $('#precheck-cert-not-found').hide();
        $('#certificat_hebergement_id').val('');
        $('#heberge_impetrant_id_from_cert').val('');
        $('#precheck_cert_numero').val('');
        $('#btn-precheck-continuer').addClass('d-none');
    });

    // Sans certificat
    $('#btn-precheck-sans-cert').on('click', function() {
        $('#precheck-cert-result').hide();
        $('#precheck-cert-not-found').hide();
        $('#certificat_hebergement_id').val('');
        $(this).prop('disabled', true).html('<i class="la la-check"></i> Sans certificat');
    });

    // ── Bouton continuer avec les informations ────────────────────────────
    $('#btn-precheck-continuer').on('click', function() {
        _appliquerDonneesCertificat();
        $('#precheckModal').modal('hide');
    });

    // ── Continuer sans vérifier ───────────────────────────────────────────
    $('#skipPrecheck').on('click', function () {
        $('#precheck_type').val('');
        $('#precheck_value').val('');
        $('#precheckResult').hide();
        $('#precheckEmpty').hide();
        $('#precheckModal').modal('hide');
    });

    // ── Fermer le modal = appliquer les données si certificat trouvé ──────
    $('#precheckModal').on('hide.bs.modal', function() {
        if ($('#certificat_hebergement_id').val()) {
            _appliquerDonneesCertificat();
        }
    });

});

// ── Appliquer les données du certificat aux champs du formulaire ──────────
function _appliquerDonneesCertificat() {
    var certId = $('#certificat_hebergement_id').val();
    if (!certId) return;

    var numero = $('#precheck_cert_numero').val().trim().toUpperCase();
    if (!numero) return;

    $.get('{{ route("certificats-hebergement.api.certificat") }}', { numero: numero })
        .done(function(data) {
            if (!data.found || !data.heberge_id) return;

            if ($('#nom').length    && data.heberge_nom)    $('#nom').val(data.heberge_nom.toUpperCase()).trigger('input');
            if ($('#prenom').length && data.heberge_prenom) $('#prenom').val(data.heberge_prenom).trigger('input');
            if ($('#sexe').length   && data.heberge_sexe)   $('#sexe').val(data.heberge_sexe).trigger('change');
            if ($('#date_naissance').length && data.heberge_dn)
                $('#date_naissance').val(data.heberge_dn).trigger('change');
            if ($('#lieu_naissance').length && data.heberge_lieu)
                $('#lieu_naissance').val(data.heberge_lieu).trigger('input');
            if ($('#nationalites_id').length && data.heberge_nationalites_id) {
                $('#nationalites_id').val(data.heberge_nationalites_id).trigger('change');
                if ($.fn.select2 && $('#nationalites_id').data('select2')) {
                    $('#nationalites_id').trigger('change.select2');
                }
            }
            if ($('#nom_pere').length    && data.heberge_nom_pere)    $('#nom_pere').val(data.heberge_nom_pere).trigger('input');
            if ($('#prenom_pere').length && data.heberge_prenom_pere) $('#prenom_pere').val(data.heberge_prenom_pere).trigger('input');
            if ($('#nom_mere').length    && data.heberge_nom_mere)    $('#nom_mere').val(data.heberge_nom_mere).trigger('input');
            if ($('#prenom_mere').length && data.heberge_prenom_mere) $('#prenom_mere').val(data.heberge_prenom_mere).trigger('input');

            if (typeof toastr !== 'undefined') {
                toastr.success('Informations de l\'hébergé pré-remplies depuis le certificat');
            }
        });
}
</script>