<?php

namespace App\TechnoDev\src\Classes;

use Carbon\Carbon;
use App\Models\Demande;
use App\Models\Impetrant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;

class TechnoDev
{
    /* ==========================================================
     |  IDENTITÉ UNIQUE IMPÉTRANT
     |========================================================== */
    public function impetrantUniqueString(Impetrant $impetrant): string
    {
        $str  = strtoupper(trim($impetrant->nom ?? ''));
        $str .= strtoupper(trim($impetrant->prenom ?? ''));
        $str .= strtoupper(trim($impetrant->sexe ?? ''));
        $str .= trim($impetrant->date_naissance ?? '');
        $str .= trim($impetrant->nationalites_id ?? '');

        return $str;
    }

    /* ==========================================================
     |  SIMILARITÉ RAPIDE (PRÉ-FILTRE)
     |========================================================== */
    public function tauxSimilarity(string $s1, string $s2): float
    {
        if ($s1 === '' || $s2 === '') {
            return 0;
        }

        $distance = levenshtein($s1, $s2);
        $maxLen   = max(strlen($s1), strlen($s2));

        if ($maxLen === 0) {
            return 100;
        }

        return round(100 - ($distance / $maxLen) * 100, 2);
    }

    /* ==========================================================
     |  SIMILARITÉ MÉTIER OFFICIELLE
     |  — Délégué à IdentitySimilarityService (logique unifiée)
     |  — Gère les prénoms composés, Levenshtein, garde-fou prénom
     |========================================================== */
    public static function tauxSimilarityDetaille(Impetrant $a, Impetrant $b): array
    {
        $result = IdentitySimilarityService::compare($a, $b);

        // Adapter le format de retour pour la compatibilité
        // avec le code existant qui attend 'score', 'details', 'level'
        return [
            'score'   => $result['score'],
            'level'   => self::similarityLevel($result['score']),
            'decision'=> $result['decision'],
            'details' => [
                'nom' => [
                    'a'      => strtoupper(trim($a->nom ?? '')),
                    'b'      => strtoupper(trim($b->nom ?? '')),
                    'match'  => $result['details']['nom'] >= 80,
                    'weight' => 20,
                    'score'  => $result['details']['nom'],
                ],
                'prenom' => [
                    'a'      => strtoupper(trim($a->prenom ?? '')),
                    'b'      => strtoupper(trim($b->prenom ?? '')),
                    'match'  => $result['details']['prenom'] >= 60,
                    'weight' => 30,
                    'score'  => $result['details']['prenom'],
                ],
                'date_naissance' => [
                    'a'      => $a->date_naissance ?? '—',
                    'b'      => $b->date_naissance ?? '—',
                    'match'  => $result['details']['date'],
                    'weight' => 25,
                    'score'  => $result['details']['date'] ? 25 : 0,
                ],
                'sexe' => [
                    'a'      => $a->sexe ?? '—',
                    'b'      => $b->sexe ?? '—',
                    'match'  => $result['details']['sexe'],
                    'weight' => 10,
                    'score'  => $result['details']['sexe'] ? 10 : 0,
                ],
                'nationalites_id' => [
                    'a'      => $a->nationalites_id ?? '—',
                    'b'      => $b->nationalites_id ?? '—',
                    'match'  => $result['details']['nationalite'],
                    'weight' => 10,
                    'score'  => $result['details']['nationalite'] ? 10 : 0,
                ],
            ],
        ];
    }

    /* ==========================================================
     |  NIVEAU DE SIMILARITÉ (DÉCISION MÉTIER)
     |========================================================== */
    public static function similarityLevel(float $score): array
    {
        if ($score >= 80) {
            return [
                'level'  => 'HIGH',
                'label'  => 'Similarité élevée',
                'color'  => 'danger',
                'action' => 'Fusion fortement recommandée',
            ];
        }

        if ($score >= 60) {
            return [
                'level'  => 'MEDIUM',
                'label'  => 'Similarité moyenne',
                'color'  => 'warning',
                'action' => 'Vérification manuelle requise',
            ];
        }

        return [
            'level'  => 'LOW',
            'label'  => 'Similarité faible',
            'color'  => 'success',
            'action' => 'Aucune action nécessaire',
        ];
    }

    public static function similarityDecision(float $score): array
    {
        $low    = env('SIMILARITY_LOW', 65);
        $medium = env('SIMILARITY_MEDIUM', 80);
        $high   = env('SIMILARITY_HIGH', 90);

        if ($score >= $high) {
            return [
                'level'  => 'CRITICAL',
                'label'  => 'Quasi-certitude',
                'color'  => 'danger',
                'action' => 'BLOCK'
            ];
        }

        if ($score >= $medium) {
            return [
                'level'  => 'HIGH',
                'label'  => 'Probabilité forte',
                'color'  => 'warning',
                'action' => 'REVIEW'
            ];
        }

        if ($score >= $low) {
            return [
                'level'  => 'MEDIUM',
                'label'  => 'Suspicion',
                'color'  => 'info',
                'action' => 'DISPLAY'
            ];
        }

        return [
            'level'  => 'LOW',
            'label'  => 'Faible similarité',
            'color'  => 'secondary',
            'action' => 'IGNORE'
        ];
    }

    public static function maxSimilarityScore(Demande $demande): float
    {
        $impetrantBase = $demande->impetrant;

        if (!$impetrantBase) {
            return 0;
        }

        $maxScore = 0;

        $autresImpetrants = Impetrant::where('id', '!=', $impetrantBase->id)->get();

        foreach ($autresImpetrants as $imp) {
            $result = self::tauxSimilarityDetaille($impetrantBase, $imp);
            if ($result['score'] > $maxScore) {
                $maxScore = $result['score'];
            }
        }

        return round($maxScore, 2);
    }

    /* ==========================================================
     |  UUID DEMANDE
     |========================================================== */
    public function demandeUuid(User $user): string
    {
        $userSeq = $this->strpad($user->id, 3);

        $last = Demande::where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $nextNumber = 1;

        if ($last && $last->uuid) {
            $nextNumber = ((int) substr($last->uuid, 13)) + 1;
        }

        return $userSeq . '_' . date('dmY') . '_' . $this->strpad($nextNumber, 10);
    }

    /* ==========================================================
     |  UTILITAIRES
     |========================================================== */
    public function strpad(int $value, int $zeros): string
    {
        return str_pad($value, $zeros, '0', STR_PAD_LEFT);
    }

    public function joinFluxData(
        int $pays_id,
        int $frontieres_id,
        string $from,
        string $to
    ) {
        return collect(DB::select(
            "SELECT
                pays_id,
                frontieres_id,
                SUM(total_entree) AS tentree,
                SUM(total_sortie) AS tsortie
             FROM flux_migratoires
             WHERE pays_id = ?
               AND frontieres_id = ?
               AND DATE(date_movement) BETWEEN ? AND ?",
            [$pays_id, $frontieres_id, $from, $to]
        ))->first();
    }

    public static function similarityScore(Impetrant $a, Impetrant $b): array
    {
        // Délégué à IdentitySimilarityService pour cohérence
        $result = IdentitySimilarityService::compare($a, $b);

        return [
            'score' => $result['score'],
            'level' => self::similarityLevel($result['score']),
        ];
    }

    public function timespan($inputtime): string
    {
        $dateTime = Carbon::parse($inputtime);
        $now      = Carbon::now();

        if ($now->diffInSeconds($dateTime) < 60) {
            return $now->diffInSeconds($dateTime) . ' seconde(s)';
        }
        if ($now->diffInMinutes($dateTime) < 60) {
            return $now->diffInMinutes($dateTime) . ' minute(s)';
        }
        if ($now->diffInHours($dateTime) < 24) {
            return $now->diffInHours($dateTime) . ' heure(s)';
        }
        if ($now->diffInDays($dateTime) < 7) {
            return $now->diffInDays($dateTime) . ' jour(s)';
        }
        if ($now->diffInWeeks($dateTime) < 4) {
            return $now->diffInWeeks($dateTime) . ' semaine(s)';
        }
        if ($now->diffInMonths($dateTime) < 12) {
            return $now->diffInMonths($dateTime) . ' mois';
        }

        return $now->diffInYears($dateTime) . ' année(s)';
    }
}