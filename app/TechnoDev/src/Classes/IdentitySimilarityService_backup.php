<?php

namespace App\TechnoDev\src\Classes;

use App\Models\Impetrant;

class IdentitySimilarityService
{
    // Config / poids (facile à ajuster sans toucher la logique)
    private const WEIGHT_NOM_PRONOM = 0.45;
    private const WEIGHT_SEXE = 0.15;
    private const WEIGHT_NATION = 0.10;
    private const WEIGHT_DOB = 0.20;
    private const WEIGHT_LIEU = 0.10;

    // Seuils et libellés
    private const SEUIL_MATCH = 80;
    private const SEUIL_CONFIDENT = 90;
    private const SEUIL_PROBABLE = 75;
    private const SEUIL_PARTIEL = 50;

    // Enable optional fallback si Metaphone non disponible
    private static function phoneticMatch(string $a, string $b): bool
    {
        // Si Metaphone est dispo et donne un résultat équivalent
        if (function_exists('metaphone')) {
            $ma = metaphone($a);
            $mb = metaphone($b);
            return $ma !== '' && $mb !== '' && $ma === $mb;
        }
        // fallback simple: exact match après normalization
        return $a === $b;
    }

    private static function normalize(?string $value): string
    {
        if (!$value) return '';

        // Normalisation de base
        $value = mb_strtoupper($value);

        // Convertir en ASCII si possible
        if (function_exists('iconv')) {
            $trans = @iconv('UTF-8', 'ASCII//TRANSLIT', $value);
            if ($trans !== false && $trans !== null) {
                $value = $trans;
            }
        }

        // Nettoyage: retirer les caractères non alphanumériques (sauf espace)
        $value = preg_replace('/[^A-Z0-9 ]/', '', $value);
        // Garder les espaces pour découper les mots
        $value = trim(preg_replace('/\s+/', ' ', $value));

        return $value;
    }

    /* ===============================
     * LOGIQUE DE MATCH PARTIEL (Noms/Prénoms)
     * Gère les oublis de 2ème prénom ou inversion
     * =============================== */
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
                // Si un mot matche exactement ou est phonétiquement identique
                if ($wa === $wb || self::phoneticMatch($wa, $wb)) {
                    $matches++;
                    break;
                }

                // Optionnel: distance de Levenshtein si disponible (threshold bas)
                if (function_exists('levenshtein')) {
                    $dist = levenshtein($wa, $wb);
                    $maxLen = max(strlen($wa), strlen($wb));
                    if ($maxLen > 0 && (1 - $dist / $maxLen) >= 0.8) {
                        $matches++;
                        break;
                    }
                }
            }
        }

        // Si au moins un nom/prénom matche parmi la liste, on donne 85% du score du champ
        // Si tout matche, c'est 100%.
        $matchRatio = $matches / max(count($wordsA), count($wordsB));

        if ($matches >= 1) {
            $base = $matchRatio * 100;
            // Forcer un minimum de 85 si on a au moins 1 correspondance
            $startAt = 85;
            return max($startAt, $base);
        }

        return 0;
    }

public static function compare(Impetrant $a, Impetrant $b): array
    {
        // 1. Calcul des scores par champ (valeurs de 0 à 100)
        $nomScore    = self::flexibleSimilarity($a->nom, $b->nom);
        $prenomScore = self::flexibleSimilarity($a->prenom, $b->prenom);
        $lieuScore   = self::flexibleSimilarity($a->lieu_naissance, $b->lieu_naissance);

        $sexeMatch   = ($a->sexe === $b->sexe);
        $natioMatch  = (self::normalize($a->nationalite) === self::normalize($b->nationalite));
        $dobMatch    = ($a->date_naissance === $b->date_naissance);

        $score = 0;

        // PRIORITÉ 1 : NOM ET PRÉNOM (45%)
        $score += (($nomScore + $prenomScore) / 2) * self::WEIGHT_NOM_PRONOM;

        // PRIORITÉ 2 : SEXE (15%)
        if ($sexeMatch) {
            $score += 100 * self::WEIGHT_SEXE;
        } else {
            $score -= 30; // Pénalité fixe pour éviter les faux positifs de genre
        }

        // PRIORITÉ 3 : NATIONALITÉ (10%)
        if ($natioMatch) {
            $score += 100 * self::WEIGHT_NATION;
        }

        // PRIORITÉ 4 : DATE DE NAISSANCE (20%)
        if ($dobMatch) {
            $score += 100 * self::WEIGHT_DOB;
        }

        // PRIORITÉ 5 : LIEU DE NAISSANCE (10%)
        $score += $lieuScore * self::WEIGHT_LIEU; // Corrigé : pas de * 100 ici

        $finalScore = max(0, min(100, round($score)));

        return [
            'score'      => $finalScore,
            'match'      => ($finalScore >= self::SEUIL_MATCH),
            'decision'   => self::getLabel($finalScore, $sexeMatch),
            'confidence' => $finalScore . '%',
            'details'    => [
                'nom' => $nomScore,
                'prenom' => $prenomScore,
                'date' => $dobMatch,
                'lieu' => $lieuScore,
                'nationalite' => $natioMatch
            ]
        ];
    }

    private static function getLabel(int $score, bool $sexeMatch): string
    {
        if (!$sexeMatch && $score < 50) return "PERSONNES DIFFÉRENTES (CONFLIT DE GENRE)";
        if ($score >= self::SEUIL_CONFIDENT) return "MÊME PERSONNE (CONFIRMÉ)";
        if ($score >= self::SEUIL_PROBABLE) return "PROBABLEMENT LA MÊME PERSONNE";
        if ($score >= self::SEUIL_PARTIEL) return "ATTENTION : SIMILARITÉ PARTIELLE";
        return "PERSONNES DIFFÉRENTES";
    }
}
