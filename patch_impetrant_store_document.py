#!/usr/bin/env python3
"""
DMCE — Patch ImpetrantController@store()
=========================================
Injecte la sauvegarde ImpetrantDocument juste avant DB::commit()
dans la méthode store(), où elle est actuellement absente.
"""

import sys
import shutil
import os
from datetime import datetime

TARGET = '/var/www/html/.apps/dmce/app/Http/Controllers/ImpetrantController.php'
BACKUP = TARGET + '.bak_' + datetime.now().strftime('%Y%m%d_%H%M%S')

# ── Ancre : le DB::commit() + toastr + redirect dans store() ─────────────────
# On cible le bloc exact de fin de store() — unique dans le fichier
ANCIEN = """            DB::commit();
            toastr()->success('Impétrant enregistré avec succès.');
            return redirect()->route('impetrants.demandes', $impetrant->id);"""

NOUVEAU = """            // ── Sauvegarde document si numéro fourni ──────────────
            if (!empty($request->numero_document)) {
                $docExistant = \\App\\Models\\ImpetrantDocument::where('impetrants_id', $impetrant->id)
                    ->where('numero_document', $request->numero_document)
                    ->first();

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
                }
            }
            // ──────────────────────────────────────────────────────

            DB::commit();
            toastr()->success('Impétrant enregistré avec succès.');
            return redirect()->route('impetrants.demandes', $impetrant->id);"""

def apply():
    if not os.path.exists(TARGET):
        print(f"[ERREUR] Fichier introuvable : {TARGET}")
        sys.exit(1)

    shutil.copy2(TARGET, BACKUP)
    print(f"[OK] Backup : {BACKUP}")

    with open(TARGET, 'r', encoding='utf-8') as f:
        content = f.read()

    if ANCIEN not in content:
        print("[ERREUR] Ancre introuvable — le fichier a peut-être déjà été patché.")
        print("Vérifiez manuellement autour de DB::commit() dans store().")
        sys.exit(2)

    # Vérifier qu'il n'y a qu'une seule occurrence (store != update)
    occurrences = content.count(ANCIEN)
    if occurrences > 1:
        print(f"[ATTENTION] {occurrences} occurrences trouvées — patch annulé pour sécurité.")
        print("Corrigez manuellement.")
        sys.exit(3)

    content = content.replace(ANCIEN, NOUVEAU, 1)

    with open(TARGET, 'w', encoding='utf-8') as f:
        f.write(content)

    print("[OK] Patch appliqué : bloc ImpetrantDocument injecté dans store()")
    print("\n[RÉSUMÉ]")
    print("  • Sauvegarde déclenchée si $request->numero_document non vide")
    print("  • Champs : type_document, numero_document, date_delivrance,")
    print("             date_expiration, pays_delivrance_id, mrz, source, created_by")
    print("  • Anti-doublon : vérifie impetrants_id + numero_document avant insert")
    print("  • Placé AVANT DB::commit() → annulé si erreur")
    print("\n[COMMANDE] Vider le cache :")
    print("  cd /var/www/html/.apps/dmce && php artisan cache:clear")

if __name__ == '__main__':
    apply()
