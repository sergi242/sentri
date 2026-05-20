<?php

namespace App\TechnoDev\src\Classes;

use App\Models\Impetrant;

class IdentitySimilarityService
{
    // ── Poids par critère ────────────────────────────────────────────────────
    private const WEIGHT_NOM    = 0.20;   // Nom : 20%
    private const WEIGHT_PRENOM = 0.30;   // Prénom : 30% (critère le plus discriminant)
    private const WEIGHT_DOB    = 0.25;   // Date de naissance : 25%
    private const WEIGHT_SEXE   = 0.10;   // Sexe : 10%
    private const WEIGHT_NATION = 0.10;   // Nationalité : 10%
    private const WEIGHT_LIEU   = 0.05;   // Lieu de naissance : 5%

    // ── Seuils de décision ───────────────────────────────────────────────────
    private const SEUIL_CERTAIN      = 90;
    private const SEUIL_QUASI        = 80;
    private const SEUIL_PROBABLE     = 70;
    private const SEUIL_PARTIEL      = 55;

    // ── Seuil minimum prénom pour déclencher une alerte ─────────────────────
    // Si le prénom score est en dessous, on plafonne le score final à 54
    // pour éviter les faux positifs sur homonymes (même nom, dates similaires)
    private const PRENOM_MIN_POUR_ALERTE = 60;

    /**
     * Normalise une chaîne : majuscules, sans accents, sans ponctuation
     */
    private static function normalize(?string $value): string
    {
        if (!$value) return '';

        $value = mb_strtoupper($value);

        if (function_exists('iconv')) {
            $trans = @iconv('UTF-8', 'ASCII//TRANSLIT', $value);
            if ($trans !== false && $trans !== null) {
                $value = $trans;
            }
        }

        $value = preg_replace('/[^A-Z0-9 ]/', '', $value);
        $value = trim(preg_replace('/\s+/', ' ', $value));

        return $value;
    }

    private static function phoneticMatch(string $a, string $b): bool
    {
        if (function_exists('metaphone')) {
            $ma = metaphone($a);
            $mb = metaphone($b);
            return $ma !== '' && $mb !== '' && $ma === $mb;
        }
        return $a === $b;
    }

    /**
     * Similarité flexible entre deux chaînes multi-mots.
     *
     * Règle clé : on divise par le PLUS COURT des deux listes de mots.
     * Cela couvre les prénoms composés :
     *   "Abdoulaye" vs "Abdoulaye Bakary"  → 1/1 = 100% ✓
     *   "Abdoulaye" vs "Bakary Abdoulaye"  → 1/1 = 100% ✓
     *   "Abdoulaye" vs "Bokary Adoulaye"   → 1/1 = 100% (Levenshtein) ✓
     *   "Bakary"    vs "Abdoulaye"         → 0/1 = 0%   ✓ (pas de match)
     *   "Jean Pierre" vs "Jean Paul"       → 1/2 = 50%  ✓ (partiel)
     *
     * Retourne un score de 0 à 100.
     */
    private static function flexibleSimilarity(?string $a, ?string $b): float
    {
        $normA = self::normalize($a);
        $normB = self::normalize($b);

        if ($normA === '' || $normB === '') return 0;
        if ($normA === $normB) return 100;

        $wordsA = explode(' ', $normA);
        $wordsB = explode(' ', $normB);

        $matches = 0;
        foreach ($wordsA as $wa) {
            foreach ($wordsB as $wb) {
                // Match exact ou phonétique
                if ($wa === $wb || self::phoneticMatch($wa, $wb)) {
                    $matches++;
                    break;
                }

                // Match approché via Levenshtein (tolérance 80%)
                if (function_exists('levenshtein')) {
                    $dist   = levenshtein($wa, $wb);
                    $maxLen = max(strlen($wa), strlen($wb));
                    if ($maxLen > 0 && (1 - $dist / $maxLen) >= 0.80) {
                        $matches++;
                        break;
                    }
                }
            }
        }

        if ($matches === 0) return 0;

        // Ratio sur le PLUS COURT : si tous les mots du prénom court
        // sont présents dans le prénom long → 100%
        $minWords   = min(count($wordsA), count($wordsB));
        $matchRatio = $matches / $minWords;

        return round(min(100, $matchRatio * 100));
    }

    /**
     * Compare deux impétrants et retourne un score + décision.
     */
    public static function compare(Impetrant $a, Impetrant $b): array
    {
        // ── Scores bruts par critère (0–100) ─────────────────────────────────
        $nomScore    = self::flexibleSimilarity($a->nom,            $b->nom);
        $prenomScore = self::flexibleSimilarity($a->prenom,         $b->prenom);
        $lieuScore   = self::flexibleSimilarity($a->lieu_naissance, $b->lieu_naissance);

        $sexeMatch   = ($a->sexe           === $b->sexe);
        $natioMatch  = (self::normalize($a->nationalite) === self::normalize($b->nationalite));
        $dobMatch    = ($a->date_naissance === $b->date_naissance);

        // ── Score pondéré ─────────────────────────────────────────────────────
        $score = 0;
        $score += $nomScore    * self::WEIGHT_NOM;
        $score += $prenomScore * self::WEIGHT_PRENOM;
        $score += ($dobMatch   ? 100 : 0) * self::WEIGHT_DOB;
        $score += ($sexeMatch  ? 100 : 0) * self::WEIGHT_SEXE;
        $score += ($natioMatch ? 100 : 0) * self::WEIGHT_NATION;
        $score += $lieuScore   * self::WEIGHT_LIEU;

        // ── Pénalité sexe différent ───────────────────────────────────────────
        if (!$sexeMatch) {
            $score -= 20;
        }

        // ── GARDE-FOU PRÉNOM ─────────────────────────────────────────────────
        // Si le prénom ne correspond pas du tout, impossible de conclure
        // que c'est la même personne.
        // Cas typique : DIAWARA Bakary vs DIAWARA Abdoulaye (homonymes)
        //   → même nom + même DN + même sexe = 68% sans garde-fou
        //   → avec garde-fou : plafonné à 54 = pas d'alerte
        if ($prenomScore < self::PRENOM_MIN_POUR_ALERTE) {
            $score = min($score, 54);
        }

        $finalScore = (int) max(0, min(100, round($score)));

        return [
            'score'      => $finalScore,
            'match'      => ($finalScore >= self::SEUIL_QUASI),
            'decision'   => self::getLabel($finalScore, $sexeMatch, $prenomScore),
            'confidence' => $finalScore . '%',
            'level'      => self::getLevel($finalScore),
            'details'    => [
                'nom'         => (int) round($nomScore),
                'prenom'      => (int) round($prenomScore),
                'date'        => $dobMatch,
                'lieu'        => (int) round($lieuScore),
                'nationalite' => $natioMatch,
                'sexe'        => $sexeMatch,
            ],
        ];
    }

    private static function getLabel(int $score, bool $sexeMatch, float $prenomScore): string
    {
        if (!$sexeMatch && $score < 40) {
            return 'PERSONNES DIFFÉRENTES (CONFLIT DE GENRE)';
        }
        if ($prenomScore < self::PRENOM_MIN_POUR_ALERTE && $score <= 54) {
            return 'HOMONYME PROBABLE (PRÉNOMS DIFFÉRENTS)';
        }
        if ($score >= self::SEUIL_CERTAIN)  return 'MÊME PERSONNE (CONFIRMÉ)';
        if ($score >= self::SEUIL_QUASI)    return 'QUASI-CERTAIN — MÊME PERSONNE';
        if ($score >= self::SEUIL_PROBABLE) return 'PROBABLEMENT LA MÊME PERSONNE';
        if ($score >= self::SEUIL_PARTIEL)  return 'ATTENTION : SIMILARITÉ PARTIELLE';
        return 'PERSONNES DIFFÉRENTES';
    }

    private static function getLevel(int $score): string
    {
        if ($score >= self::SEUIL_CERTAIN)  return 'CERTAIN';
        if ($score >= self::SEUIL_QUASI)    return 'QUASI_CERTAIN';
        if ($score >= self::SEUIL_PROBABLE) return 'PROBABLE';
        if ($score >= self::SEUIL_PARTIEL)  return 'PARTIEL';
        return 'DIFFERENT';
    }
}