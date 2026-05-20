#!/usr/bin/env python3
"""
DMCE — Patch double : create.blade.php + ImpetrantController@store()
=====================================================================
Bug 1 : name="date_expiration_doc"  → le contrôleur lit date_expiration
Bug 2 : name="source_document"      → le contrôleur lit h_source_doc
Bug 3 : dans lancerReset()/error:   → data non défini hors callback AJAX

Corrections :
  Vue   : renommer les 2 champs cachés + nettoyer le code mort dans les
          callbacks error/reset (data n'existe pas dans ce scope)
  Ctrl  : aligner les noms lus dans store() avec les vrais noms de champs
"""

import sys, shutil, os
from datetime import datetime

VUE   = '/var/www/html/.apps/dmce/resources/views/admin/impetrants/create-direct.blade.php'
CTRL  = '/var/www/html/.apps/dmce/app/Http/Controllers/ImpetrantController.php'

def backup(path):
    bak = path + '.bak_' + datetime.now().strftime('%Y%m%d_%H%M%S')
    shutil.copy2(path, bak)
    print(f'[OK] Backup : {bak}')

# ══════════════════════════════════════════════════════════════════════════════
# PATCH VUE — create.blade.php
# ══════════════════════════════════════════════════════════════════════════════

# 1a. Renommer les champs cachés
VUE_ANCIEN_CHAMPS = '''\
<input type="hidden" name="numero_document"   id="h_num_doc">
<input type="hidden" name="date_delivrance"   id="h_date_deliv">
<input type="hidden" name="date_expiration_doc" id="h_date_exp">
<input type="hidden" name="mrz"               id="h_mrz">
<input type="hidden" name="source_document"   id="h_source_doc" value="manuel">'''

VUE_NOUVEAU_CHAMPS = '''\
<input type="hidden" name="numero_document"   id="h_num_doc">
<input type="hidden" name="date_delivrance"   id="h_date_deliv">
<input type="hidden" name="date_expiration"   id="h_date_exp">
<input type="hidden" name="h_mrz"             id="h_mrz">
<input type="hidden" name="h_source_doc"      id="h_source_doc" value="manuel">'''

# 1b. Nettoyer le bloc mort dans error: de lancerLecture()
# (data n'existe pas dans ce scope → erreur JS silencieuse)
VUE_ANCIEN_ERROR = '''\
        error: function() {
            setLed('err','Indisponible','<i class="la la-times-circle"></i> Service non disponible. Vérifiez que le programme Java tourne.');
            // ── Champs cachés document ──────────────────────────────
    if (data.num_doc)    document.getElementById('h_num_doc').value    = data.num_doc;
    if (data.expiration) document.getElementById('h_date_exp').value   = data.expiration;
    if (data.mrz)        document.getElementById('h_mrz').value        = data.mrz;
    document.getElementById('h_source_doc').value = 'lecteur';
    document.getElementById('btnLire').disabled = false;
        }
    });
}

function lancerReset()'''

VUE_NOUVEAU_ERROR = '''\
        error: function() {
            setLed('err','Indisponible','<i class="la la-times-circle"></i> Service non disponible. Vérifiez que le programme Java tourne.');
            document.getElementById('btnLire').disabled = false;
        }
    });
}

function lancerReset()'''

# 1c. Nettoyer le bloc mort dans success de lancerReset()
VUE_ANCIEN_RESET_OK = '''\
        success: function() {
            setTimeout(function() {
                setLed('ok','Redémarré','<i class="la la-check-circle"></i> Lecteur réinitialisé !');
                // ── Champs cachés document ──────────────────────────────
    if (data.num_doc)    document.getElementById('h_num_doc').value    = data.num_doc;
    if (data.expiration) document.getElementById('h_date_exp').value   = data.expiration;
    if (data.mrz)        document.getElementById('h_mrz').value        = data.mrz;
    document.getElementById('h_source_doc').value = 'lecteur';
    document.getElementById('btnLire').disabled = false;
            }, 3000);
        },
        error: function() {
            setLed('err','Erreur','<i class="la la-times-circle"></i> Erreur réinitialisation');
            // ── Champs cachés document ──────────────────────────────
    if (data.num_doc)    document.getElementById('h_num_doc').value    = data.num_doc;
    if (data.expiration) document.getElementById('h_date_exp').value   = data.expiration;
    if (data.mrz)        document.getElementById('h_mrz').value        = data.mrz;
    document.getElementById('h_source_doc').value = 'lecteur';
    document.getElementById('btnLire').disabled = false;
        }
    });
}'''

VUE_NOUVEAU_RESET_OK = '''\
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
}'''

# ══════════════════════════════════════════════════════════════════════════════
# PATCH CONTRÔLEUR — aligner les noms dans store()
# ══════════════════════════════════════════════════════════════════════════════
CTRL_ANCIEN = '''\
                if (!$docExistant) {
                    \\App\\Models\\ImpetrantDocument::create([
                        'impetrants_id'      => $impetrant->id,
                        'type_document'      => $request->type_document      ?? 'Passeport',
                        'numero_document'    => strtoupper(trim($request->numero_document)),
                        'date_delivrance'    => $request->date_delivrance     ?: null,
                        'date_expiration'    => $request->date_expiration     ?: null,
                        'pays_delivrance_id' => $request->pays_delivrance_id  ?: null,
                        'mrz'                => $request->h_mrz               ?: null,
                        'source'             => $request->h_source_doc        ?? 'manuel',
                        'created_by'         => Auth::id(),
                    ]);
                }'''

CTRL_NOUVEAU = '''\
                if (!$docExistant) {
                    \\App\\Models\\ImpetrantDocument::create([
                        'impetrants_id'      => $impetrant->id,
                        'type_document'      => $request->type_document      ?? 'Passeport',
                        'numero_document'    => strtoupper(trim($request->numero_document)),
                        'date_delivrance'    => $request->date_delivrance     ?: null,
                        'date_expiration'    => $request->date_expiration     ?: null,
                        'pays_delivrance_id' => $request->pays_delivrance_id  ?: null,
                        'mrz'                => $request->h_mrz               ?: null,
                        'source'             => $request->h_source_doc        ?? 'manuel',
                        'created_by'         => Auth::id(),
                    ]);
                }'''
# Note: le contrôleur est déjà correct après le patch précédent.
# On vérifie juste que les noms correspondent aux nouveaux noms de champs.
# date_expiration ✓ (était date_expiration_doc → corrigé dans la vue)
# h_mrz          ✓ (était mrz → corrigé dans la vue)
# h_source_doc   ✓ (était source_document → corrigé dans la vue)

# ══════════════════════════════════════════════════════════════════════════════
# APPLICATION
# ══════════════════════════════════════════════════════════════════════════════
def patch_vue():
    if not os.path.exists(VUE):
        print(f'[ERREUR] Vue introuvable : {VUE}'); sys.exit(1)
    backup(VUE)
    with open(VUE, 'r', encoding='utf-8') as f:
        c = f.read()

    errors = []

    if VUE_ANCIEN_CHAMPS in c:
        c = c.replace(VUE_ANCIEN_CHAMPS, VUE_NOUVEAU_CHAMPS, 1)
        print('[OK] Patch vue 1/3 : noms champs cachés corrigés')
    else:
        errors.append('Patch vue 1/3 : ancre champs cachés introuvable')

    if VUE_ANCIEN_ERROR in c:
        c = c.replace(VUE_ANCIEN_ERROR, VUE_NOUVEAU_ERROR, 1)
        print('[OK] Patch vue 2/3 : bloc mort error: nettoyé')
    else:
        errors.append('Patch vue 2/3 : ancre error: introuvable')

    if VUE_ANCIEN_RESET_OK in c:
        c = c.replace(VUE_ANCIEN_RESET_OK, VUE_NOUVEAU_RESET_OK, 1)
        print('[OK] Patch vue 3/3 : blocs morts reset nettoyés')
    else:
        errors.append('Patch vue 3/3 : ancre reset introuvable')

    if errors:
        print('\n[ATTENTION] Patches échoués :')
        for e in errors: print(f'  ✗ {e}')
        print('Fichier NON modifié.'); sys.exit(2)

    with open(VUE, 'w', encoding='utf-8') as f:
        f.write(c)
    print('[OK] Vue sauvegardée')

def patch_ctrl():
    if not os.path.exists(CTRL):
        print(f'[ERREUR] Contrôleur introuvable : {CTRL}'); sys.exit(1)

    with open(CTRL, 'r', encoding='utf-8') as f:
        c = f.read()

    # Vérifier que les bons noms sont déjà présents (patch précédent appliqué)
    ok = (
        "'date_expiration'    => $request->date_expiration" in c and
        "'mrz'                => $request->h_mrz" in c and
        "'source'             => $request->h_source_doc" in c
    )
    if ok:
        print('[OK] Contrôleur : noms déjà corrects, aucune modification nécessaire')
    else:
        print('[ATTENTION] Contrôleur : noms inattendus, vérifiez manuellement store()')

def main():
    print('=== PATCH DMCE : create.blade.php + ImpetrantController ===\n')
    patch_vue()
    print()
    patch_ctrl()
    print('\n[RÉSUMÉ] Corrections appliquées :')
    print('  Bug 1 : name="date_expiration_doc" → "date_expiration"')
    print('  Bug 2 : name="source_document"     → "h_source_doc"')
    print('  Bug 3 : name="mrz"                 → "h_mrz"')
    print('  Bug 4 : data hors scope dans error:/reset → nettoyé')
    print('\n[COMMANDE]')
    print('  cd /var/www/html/.apps/dmce && php artisan view:clear && php artisan cache:clear')

if __name__ == '__main__':
    main()
