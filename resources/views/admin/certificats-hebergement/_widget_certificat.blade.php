{{--
    ══════════════════════════════════════════════════════════════════════════
    WIDGET : Vérification du certificat d'hébergement
    À inclure dans newcrt.blade.php et newvisa.blade.php
    USAGE : @include('admin.certificats-hebergement._widget_certificat')
    ══════════════════════════════════════════════════════════════════════════
--}}

<div class="card mb-3" id="card-certificat-widget" style="border-left:4px solid #1E9FF2;">
    <div class="card-header py-2" style="background:#f8f9fa;">
        <strong><i class="la la-building" style="color:#1E9FF2;"></i>
        Certificat d'hébergement</strong>
        <small class="text-muted ml-2">(optionnel)</small>
    </div>
    <div class="card-body">

        <div class="alert alert-info py-2 mb-3">
            <i class="la la-info-circle"></i>
            Si l'impétrant possède un certificat d'hébergement, saisissez son numéro pour le lier automatiquement.
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text"
                           id="input-numero-certificat"
                           class="form-control"
                           placeholder="N° certificat (ex: CERT-260422-0001)"
                           style="text-transform:uppercase;">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-info" id="btn-verifier-certificat">
                            <i class="la la-search"></i> Vérifier
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-outline-secondary btn-block" id="btn-sans-certificat">
                    <i class="la la-times"></i> Sans certificat
                </button>
            </div>
        </div>

        {{-- Résultat trouvé --}}
        <div id="certificat-result" class="mt-3" style="display:none;">
            <div class="card border-success">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-success mb-2">
                                <i class="la la-check-circle"></i> Certificat trouvé
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Numéro</small>
                                    <strong id="cert-numero"></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Statut</small>
                                    <span id="cert-statut" class="badge"></span>
                                </div>
                                <div class="col-6 mt-2">
                                    <small class="text-muted d-block">Hébergeur</small>
                                    <strong id="cert-hebergeur"></strong>
                                    <small id="cert-code-heb" class="badge badge-primary ml-1"></small>
                                </div>
                                <div class="col-6 mt-2">
                                    <small class="text-muted d-block">Hébergé enregistré</small>
                                    <strong id="cert-heberge"></strong>
                                </div>
                                <div class="col-6 mt-2">
                                    <small class="text-muted d-block">Arrivée prévue</small>
                                    <strong id="cert-arrivee"></strong>
                                </div>
                                <div class="col-6 mt-2">
                                    <small class="text-muted d-block">Départ prévu</small>
                                    <strong id="cert-depart"></strong>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger ml-2" id="btn-effacer-certificat">
                            <i class="la la-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Non trouvé --}}
        <div id="certificat-not-found" class="alert alert-warning mt-3" style="display:none;">
            <i class="la la-exclamation-triangle"></i>
            Certificat introuvable. Vérifiez le numéro ou continuez sans certificat.
            <a href="{{ route('certificats-hebergement.create') }}" target="_blank" class="ml-2">
                <i class="la la-plus"></i> Créer un certificat
            </a>
        </div>

        {{-- Champs cachés --}}
        <input type="hidden" name="certificat_hebergement_id"       id="certificat_hebergement_id"       value="">
        <input type="hidden" name="heberge_impetrant_id_from_cert"  id="heberge_impetrant_id_from_cert"  value="">

    </div>
</div>

{{-- ── JS inline — compatible avec @section('scripts') ET @push('scripts') ── --}}
<script>
(function() {
    // Attendre que jQuery soit prêt
    function initCertificatWidget() {
        if (typeof $ === 'undefined') {
            setTimeout(initCertificatWidget, 100);
            return;
        }

        var apiUrl = '{{ route("certificats-hebergement.api.certificat") }}';

        // Majuscules auto
        $('#input-numero-certificat').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Vérifier
        $('#btn-verifier-certificat').on('click', function() {
            var numero = $('#input-numero-certificat').val().trim().toUpperCase();
            if (!numero) {
                if (typeof toastr !== 'undefined') toastr.warning('Saisissez un numéro de certificat');
                return;
            }

            $(this).html('<i class="la la-spinner la-spin"></i>').prop('disabled', true);

            $.get(apiUrl, { numero: numero })
                .done(function(data) {
                    if (data.found) {
                        // Remplir les infos
                        $('#cert-numero').text(data.numero);
                        $('#cert-hebergeur').text(data.nom_hebergeur);
                        $('#cert-code-heb').text(data.code_hebergeur || '');
                        $('#cert-heberge').text(
                            data.heberge_nom
                                ? data.heberge_nom + ' ' + data.heberge_prenom
                                : '(non enregistré)'
                        );
                        $('#cert-arrivee').text(
                            data.date_arrivee
                                ? new Date(data.date_arrivee).toLocaleDateString('fr-FR')
                                : '—'
                        );
                        $('#cert-depart').text(
                            data.date_depart
                                ? new Date(data.date_depart).toLocaleDateString('fr-FR')
                                : '—'
                        );

                        // Badge statut
                        var couleurs = {
                            'Validé':     'success',
                            'En attente': 'warning',
                            'Rejeté':     'danger',
                            'Expiré':     'secondary'
                        };
                        var sc = couleurs[data.statut] || 'secondary';
                        $('#cert-statut')
                            .text(data.statut)
                            .attr('class', 'badge badge-' + sc);

                        // Champs cachés
                        $('#certificat_hebergement_id').val(data.id);
                        $('#heberge_impetrant_id_from_cert').val(data.heberge_id || '');

                        // ── Auto-remplissage des champs de l'impétrant ────────
                        if (data.heberge_id && data.heberge_nom) {
                            // Nom
                            if ($('#nom').length) {
                                $('#nom').val(data.heberge_nom.toUpperCase()).trigger('change');
                            }
                            // Prénom
                            if ($('#prenom').length && data.heberge_prenom) {
                                $('#prenom').val(data.heberge_prenom).trigger('change');
                            }
                            // Sexe
                            if ($('#sexe').length && data.heberge_sexe) {
                                $('#sexe').val(data.heberge_sexe).trigger('change');
                            }
                            // Date naissance
                            if ($('#date_naissance').length && data.heberge_dn) {
                                $('#date_naissance').val(data.heberge_dn).trigger('change');
                            }
                            // Lieu naissance
                            if ($('#lieu_naissance').length && data.heberge_lieu) {
                                $('#lieu_naissance').val(data.heberge_lieu).trigger('change');
                            }
                            // Nationalité
                            if ($('#nationalites_id').length && data.heberge_nationalites_id) {
                                $('#nationalites_id').val(data.heberge_nationalites_id).trigger('change');
                            }
                            // Nom père
                            if ($('#nom_pere').length && data.heberge_nom_pere) {
                                $('#nom_pere').val(data.heberge_nom_pere).trigger('change');
                            }
                            // Nom mère
                            if ($('#nom_mere').length && data.heberge_nom_mere) {
                                $('#nom_mere').val(data.heberge_nom_mere).trigger('change');
                            }

                            if (typeof toastr !== 'undefined') {
                                toastr.success('Informations de l\'hébergé pré-remplies automatiquement');
                            }
                        }

                        // Afficher résultat
                        $('#certificat-result').show();
                        $('#certificat-not-found').hide();

                        // Alerte si pas validé
                        if (data.statut !== 'Validé' && typeof toastr !== 'undefined') {
                            toastr.warning('Ce certificat n\'est pas encore validé. Il sera quand même lié à la demande.');
                        }

                    } else {
                        $('#certificat-result').hide();
                        $('#certificat-not-found').show();
                        $('#certificat_hebergement_id').val('');
                    }
                })
                .fail(function() {
                    if (typeof toastr !== 'undefined') toastr.error('Erreur lors de la vérification');
                })
                .always(function() {
                    $('#btn-verifier-certificat')
                        .html('<i class="la la-search"></i> Vérifier')
                        .prop('disabled', false);
                });
        });

        // Entrée = vérifier
        $('#input-numero-certificat').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#btn-verifier-certificat').trigger('click');
            }
        });

        // Effacer
        $('#btn-effacer-certificat').on('click', function() {
            $('#certificat-result').hide();
            $('#certificat-not-found').hide();
            $('#certificat_hebergement_id').val('');
            $('#heberge_impetrant_id_from_cert').val('');
            $('#input-numero-certificat').val('');
        });

        // Sans certificat
        $('#btn-sans-certificat').on('click', function() {
            $('#card-certificat-widget').css('opacity', '0.5');
            $('#certificat_hebergement_id').val('');
            $(this).prop('disabled', true)
                   .html('<i class="la la-check"></i> Sans certificat confirmé');
        });
    }

    // Lancer l'init
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCertificatWidget);
    } else {
        initCertificatWidget();
    }
})();
</script>