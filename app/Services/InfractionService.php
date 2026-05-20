<?php

namespace App\Services;

use App\Models\Infraction;
use App\Models\Impetrant;
use Carbon\Carbon;

class InfractionService
{
    /**
     * Synchronise automatiquement les infractions pour tous les impétrants.
     * À appeler via un Command/Scheduler quotidien.
     */
    public function syncAll(): void
    {
        Impetrant::with(['demandes'])->chunk(100, function($impetrants) {
            foreach ($impetrants as $imp) {
                $this->syncPourImpetrant($imp);
            }
        });
    }

    public function syncPourImpetrant(Impetrant $imp): void
    {
        foreach ($imp->demandes as $demande) {
            $this->verifierExpirationSansRenouvellement($imp, $demande);
            $this->verifierDemandeExpireeSansSuite($imp, $demande);
            $this->verifierContentieux($imp, $demande);
        }
    }

    /**
     * Carte expirée depuis plus d'1 an sans nouveau dossier approuvé
     */
    private function verifierExpirationSansRenouvellement(Impetrant $imp, $demande): void
    {
        if (!$demande->date_expiration) return;
        if ($demande->attribue != 1) return;

        $expiration = Carbon::parse($demande->date_expiration);
        if (!$expiration->addYear()->isPast()) return;

        // Vérifie qu'il n'a pas fait de nouvelle demande approuvée après
        $aRenouvele = $imp->demandes
            ->where('id', '!=', $demande->id)
            ->where('statut_demande', 'Approuvée')
            ->where('date_demande', '>', $demande->date_expiration)
            ->isNotEmpty();

        if ($aRenouvele) return;

        // Évite les doublons
        $existe = Infraction::where('impetrant_id', $imp->id)
            ->where('demande_id', $demande->id)
            ->where('type', 'expiration_sans_renouvellement')
            ->exists();

        if (!$existe) {
            Infraction::create([
                'impetrant_id'   => $imp->id,
                'demande_id'     => $demande->id,
                'user_id'        => null,
                'type'           => 'expiration_sans_renouvellement',
                'gravite'        => 'moyen',
                'statut'         => 'en_cours',
                'motif'          => "Carte expirée le {$expiration->subYear()->format('d/m/Y')} sans renouvellement dans l'année suivante.",
                'date_infraction'=> $expiration->subYear()->addYear(),
                'auto_generee'   => true,
            ]);
        }
    }

    /**
     * Demande en attente dont la fiche a expiré sans aucune action
     */
    private function verifierDemandeExpireeSansSuite(Impetrant $imp, $demande): void
    {
        if (!$demande->date_validiter_fiche) return;
        if ($demande->statut_demande !== "En attente d'approbation") return;

        $expiration = Carbon::parse($demande->date_validiter_fiche);
        if (!$expiration->isPast()) return;

        $existe = Infraction::where('impetrant_id', $imp->id)
            ->where('demande_id', $demande->id)
            ->where('type', 'demande_expiree_sans_suite')
            ->exists();

        if (!$existe) {
            Infraction::create([
                'impetrant_id'   => $imp->id,
                'demande_id'     => $demande->id,
                'user_id'        => null,
                'type'           => 'demande_expiree_sans_suite',
                'gravite'        => 'mineur',
                'statut'         => 'en_cours',
                'motif'          => "Fiche de demande expirée le {$expiration->format('d/m/Y')} sans suite donnée.",
                'date_infraction'=> $expiration,
                'auto_generee'   => true,
            ]);
        }
    }

    /**
     * Passage au contentieux
     */
    private function verifierContentieux(Impetrant $imp, $demande): void
    {
        if ($demande->statut_demande !== 'Envoyée au contentieux') return;

        $existe = Infraction::where('impetrant_id', $imp->id)
            ->where('demande_id', $demande->id)
            ->where('type', 'contentieux')
            ->exists();

        if (!$existe) {
            Infraction::create([
                'impetrant_id'   => $imp->id,
                'demande_id'     => $demande->id,
                'user_id'        => null,
                'type'           => 'contentieux',
                'gravite'        => 'grave',
                'statut'         => 'en_cours',
                'motif'          => "Dossier ref. {$demande->uuid} envoyé au contentieux.",
                'date_infraction'=> $demande->updated_at ?? now(),
                'auto_generee'   => true,
            ]);
        }
    }
}