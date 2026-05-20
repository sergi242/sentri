/**
 * DMCE — Lecteur de Passeport (MRZ / NFC)
 * =========================================
 * À inclure dans @push('scripts') de chaque vue concernée,
 * OU dans le layout principal admin/layouts/app.blade.php.
 *
 * Fonctionnement :
 *  - Simulation de lecture MRZ via champ série de scanner USB-HID
 *    (le scanner émet une chaîne MRZ sur l'entrée standard, interceptée ici)
 *  - En production : remplacer simulateMRZRead() par l'appel réel à l'API
 *    du lecteur de passeport (ex: Gemalto AT9000, ACR122U NFC, etc.)
 *
 * Champs remplis automatiquement (par préfixe target) :
 *  {target}_nom, {target}_prenom, {target}_date_naissance,
 *  {target}_lieu_naissance, {target}_nationalite, {target}_numero_passeport,
 *  {target}_date_expiration_passeport, {target}_sexe
 */

(function ($) {
    'use strict';

    // ----------------------------------------------------------------
    // Configuration de l'endpoint API lecteur passeport
    // Remplacez par votre vrai endpoint si vous avez un middleware NFC
    // ----------------------------------------------------------------
    var PASSPORT_API_URL = '/api/passport/read';  // à adapter

    // ----------------------------------------------------------------
    // Décodage MRZ (TD3 — passeport standard OACI Doc 9303)
    // Format MRZ ligne 1 : 44 chars | ligne 2 : 44 chars
    // ----------------------------------------------------------------
    function parseMRZ(mrz) {
        if (!mrz || mrz.length < 88) return null;

        var line1 = mrz.substring(0, 44).toUpperCase();
        var line2 = mrz.substring(44, 88).toUpperCase();

        // Ligne 1
        var docType    = line1.substring(0, 2).replace('<', '');
        var country    = line1.substring(2, 5).replace(/</g, '');
        var namesRaw   = line1.substring(5, 44);
        var namesParts = namesRaw.split('<<');
        var nom        = (namesParts[0] || '').replace(/</g, ' ').trim();
        var prenom     = (namesParts[1] || '').replace(/</g, ' ').trim();

        // Ligne 2
        var passportNo   = line2.substring(0, 9).replace(/</g, '');
        var nationality  = line2.substring(10, 13).replace(/</g, '');
        var dob          = line2.substring(13, 19);   // AAMMJJ
        var sex          = line2.substring(20, 21);   // M/F/<
        var expiry       = line2.substring(21, 27);   // AAMMJJ

        function mrzDateToISO(d) {
            if (!d || d.length < 6) return '';
            var yy = parseInt(d.substring(0, 2), 10);
            var mm = d.substring(2, 4);
            var dd = d.substring(4, 6);
            // Pivot year 2000 : yy >= 30 → 19xx, sinon 20xx
            var year = yy >= 30 ? '19' + ('0' + yy).slice(-2) : '20' + ('0' + yy).slice(-2);
            return year + '-' + mm + '-' + dd;
        }

        return {
            nom:                 nom,
            prenom:              prenom,
            numero_passeport:    passportNo,
            date_naissance:      mrzDateToISO(dob),
            date_expiration:     mrzDateToISO(expiry),
            nationalite_code:    nationality,
            pays_code:           country,
            sexe:                sex === 'M' ? 'M' : (sex === 'F' ? 'F' : ''),
            lieu_naissance:      '',   // non disponible en MRZ standard
        };
    }

    // ----------------------------------------------------------------
    // Simulation MRZ (DÉVELOPPEMENT UNIQUEMENT)
    // En production, remplacer par appel Ajax à l'API lecteur
    // ----------------------------------------------------------------
    function simulateMRZRead(target, callback) {
        // MRZ fictive de test
        var fakeMRZ =
            'P<CONGOONDELE<<SERGI<<<<<<<<<<<<<<<<<<<<<<<' +
            'A12345678<COG9001011M2601014<<<<<<<<<<<<<<<6';

        setTimeout(function () {
            callback(null, parseMRZ(fakeMRZ));
        }, 1200);
    }

    // ----------------------------------------------------------------
    // Remplissage des champs du formulaire
    // ----------------------------------------------------------------
    function fillFields(target, data) {
        var map = {
            nom:                 ['nom', 'name', 'last_name'],
            prenom:              ['prenom', 'first_name', 'prenoms'],
            date_naissance:      ['date_naissance', 'dob', 'birth_date'],
            lieu_naissance:      ['lieu_naissance', 'birthplace'],
            numero_passeport:    ['numero_passeport', 'passport_number', 'num_passeport'],
            date_expiration:     ['date_expiration_passeport', 'expiry_date', 'date_expiration'],
            sexe:                ['sexe', 'gender', 'sex'],
        };

        $.each(map, function (key, aliases) {
            var value = data[key] || '';
            if (!value) return;

            $.each(aliases, function (_, alias) {
                // Chercher champ avec id ou name commençant par target_
                var $field = $('[id="' + target + '_' + alias + '"],' +
                               '[name="' + target + '_' + alias + '"],' +
                               '[id="' + alias + '"][data-target="' + target + '"]');

                if ($field.length) {
                    if ($field.is('select')) {
                        // Essayer de trouver l'option correspondante
                        var $opt = $field.find('option').filter(function () {
                            return $(this).text().toUpperCase().indexOf(value.toUpperCase()) >= 0
                                || $(this).val().toUpperCase() === value.toUpperCase();
                        }).first();
                        if ($opt.length) {
                            $field.val($opt.val()).trigger('change');
                        }
                    } else {
                        $field.val(value).trigger('change');
                    }
                    return false; // break aliases loop
                }
            });
        });
    }

    // ----------------------------------------------------------------
    // Réinitialisation des champs
    // ----------------------------------------------------------------
    function resetFields(target) {
        $('[id^="' + target + '_"], [name^="' + target + '_"]').each(function () {
            var $f = $(this);
            if ($f.is('select')) {
                $f.val('').trigger('change');
            } else if ($f.is(':checkbox, :radio')) {
                $f.prop('checked', false);
            } else {
                $f.val('');
            }
        });
    }

    // ----------------------------------------------------------------
    // Mise à jour du badge de statut
    // ----------------------------------------------------------------
    function setStatus(target, type, message) {
        var $badge = $('#passport-status-' + target);
        var classes = {
            loading : 'badge-warning',
            success : 'badge-success',
            error   : 'badge-danger',
            idle    : 'badge-light',
        };

        $badge.removeClass('badge-warning badge-success badge-danger badge-light')
              .addClass(classes[type] || 'badge-light')
              .text(message)
              .show();

        if (type === 'success' || type === 'error') {
            setTimeout(function () {
                $badge.fadeOut(400, function () { $badge.text('').show(); });
            }, 4000);
        }
    }

    // ----------------------------------------------------------------
    // Événements
    // ----------------------------------------------------------------
    $(document).on('click', '.passport-read-btn', function () {
        var target = $(this).data('target');
        var $btn   = $(this);

        $btn.prop('disabled', true)
            .html('<i class="la la-spinner la-spin mr-1"></i> Lecture...');
        setStatus(target, 'loading', 'Lecture en cours…');

        // --- PRODUCTION ---
        // Remplacez simulateMRZRead par :
        // $.ajax({
        //     url: PASSPORT_API_URL,
        //     method: 'POST',
        //     data: { target: target, _token: $('meta[name="csrf-token"]').attr('content') },
        //     success: function(response) { ... },
        //     error: function() { ... }
        // });

        // --- SIMULATION (développement) ---
        simulateMRZRead(target, function (err, data) {
            $btn.prop('disabled', false)
                .html('<i class="la la-wifi mr-1"></i> Lire le passeport');

            if (err || !data) {
                setStatus(target, 'error', 'Échec de lecture');
                toastr.error('Impossible de lire le passeport. Vérifiez le lecteur.', 'Lecteur passeport');
                return;
            }

            fillFields(target, data);
            setStatus(target, 'success', '✓ Passeport lu');
            toastr.success('Passeport lu avec succès.', 'Lecteur passeport');
        });
    });

    $(document).on('click', '.passport-reset-btn', function () {
        var target = $(this).data('target');

        if (!confirm('Réinitialiser tous les champs de ' + target + ' ?')) return;

        resetFields(target);
        setStatus(target, 'idle', 'Réinitialisé');
        toastr.warning('Champs réinitialisés.', 'Lecteur passeport');
    });

}(jQuery));
