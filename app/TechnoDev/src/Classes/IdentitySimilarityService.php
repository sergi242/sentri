<?php

namespace App\TechnoDev\src\Classes;

use App\Models\Impetrant;

/**
 * IdentitySimilarityService — v2
 *
 * Remplace directement la v1. Même namespace, même interface publique.
 * Tous les appels existants dans DemandeController et TechnoDev sont
 * compatibles sans modification.
 *
 * ════════════════════════════════════════════════════════════════════
 * PHILOSOPHIE DE DÉTECTION
 * ════════════════════════════════════════════════════════════════════
 *
 * RÈGLE D'OR — Numéro de document :
 *   Un document d'identité est un identifiant unique par nature.
 *   Si deux profils ont le même numéro → même personne, certitude absolue.
 *   Court-circuit immédiat, score = 100, fin de comparaison.
 *
 * TOUS LES AUTRES CHAMPS sont sujets à erreur de saisie humaine :
 *   - Nom/Prénom : translittération, inversions culturelles, noms composés
 *   - Date de naissance : transposition jour/mois, erreur d'un an
 *   - Nationalité : parfois omise ou mal renseignée
 *   Les comparer avec tolérance est donc nécessaire et non optionnel.
 *
 * COMBINAISON GAGNANTE sans document :
 *   Nom ≈ + Prénom ≈ + Date naissance exacte → quasi-certitude (≥75)
 *   Nom ≈ + Prénom ≈ + Date proche ±30j + Nationalité → probable (≥60)
 *
 * ════════════════════════════════════════════════════════════════════
 */
class IdentitySimilarityService
{
    // ── Poids par critère (total = 100 sans pénalités) ───────────────
    private const W_NOM        = 25;   // Nom de famille
    private const W_PRENOM     = 25;   // Prénom (même poids que nom — les deux comptent)
    private const W_DOB        = 30;   // Date de naissance (critère le plus objectif)
    private const W_NATIONALITE = 10;  // Nationalité
    private const W_SEXE       = 5;    // Sexe
    private const W_LIEU       = 5;    // Lieu de naissance

    // ── Seuils de décision ───────────────────────────────────────────
    private const SEUIL_CERTAIN      = 90;
    private const SEUIL_QUASI        = 75;
    private const SEUIL_PROBABLE     = 60;   // Seuil d'alerte bloquante
    private const SEUIL_PARTIEL      = 45;

    // ── Seuil Jaro-Winkler pour considérer deux tokens comme proches ─
    private const JW_THRESHOLD = 0.80;

    // ── Tolérance date de naissance (jours) ──────────────────────────
    private const DOB_TOLERANCE_DAYS = 30;

    // ─────────────────────────────────────────────────────────────────
    // MÉTHODE PUBLIQUE PRINCIPALE
    // Compatible avec tous les appels existants :
    //   IdentitySimilarityService::compare($impetrantA, $impetrantB)
    // ─────────────────────────────────────────────────────────────────

    /**
     * Compare deux impétrants. Retourne un tableau structuré identique
     * au format v1 pour compatibilité totale avec le code existant.
     *
     * @param Impetrant $a
     * @param Impetrant $b
     * @return array{score:int, match:bool, decision:string, confidence:string, level:string, details:array}
     */
    public static function compare(Impetrant $a, Impetrant $b): array
    {
        // ── COURT-CIRCUIT DOCUMENT ────────────────────────────────────
        // Un document identique = même personne, certitude absolue.
        // On vérifie tous les documents connus de chaque impétrant.
        $docResult = self::checkDocuments($a, $b);
        if ($docResult['match']) {
            return self::buildResult(
                score:         100,
                sexeMatch:     true,
                prenomRawScore: 100,
                nomRawScore:   100,
                details: [
                    'nom'         => 100,
                    'prenom'      => 100,
                    'date'        => true,
                    'lieu'        => 100,
                    'nationalite' => true,
                    'sexe'        => true,
                    'document'    => $docResult['numero'],
                ],
                documentCourt: true
            );
        }

        // ── NORMALISATION ─────────────────────────────────────────────
        $nomA    = self::normalize($a->nom);
        $nomB    = self::normalize($b->nom);
        $prenomA = self::normalize($a->prenom);
        $prenomB = self::normalize($b->prenom);
        $lieuA   = self::normalize($a->lieu_naissance);
        $lieuB   = self::normalize($b->lieu_naissance);

        // ── SCORES PAR CRITÈRE ────────────────────────────────────────

        // Nom (0–100)
        $nomScore = self::nameScore($nomA, $nomB);

        // Prénom (0–100) — gestion inversions et prénoms composés
        $prenomScore = self::nameScore($prenomA, $prenomB);

        // Date de naissance (0–30 points, puis ramenée à 0–100 pour le poids)
        ['pts' => $dobPts, 'match' => $dobExact, 'days' => $dobDays] = self::dobScore(
            $a->date_naissance,
            $b->date_naissance
        );
        // Convertir en score 0–100 pour le calcul pondéré
        $dobScore100 = ($dobPts / self::W_DOB) * 100;

        // Nationalité (booléen → 0 ou 100)
        // impetrants.nationalites_id est un INT (FK pays)
        $natioMatch  = self::nationaliteMatch($a, $b);
        $natioScore  = $natioMatch ? 100 : 0;

        // Sexe (booléen)
        $sexeMatch  = ($a->sexe === $b->sexe);
        $sexeScore  = $sexeMatch ? 100 : 0;

        // Lieu de naissance (0–100)
        $lieuScore  = ($lieuA !== '' && $lieuB !== '') ? self::jaroWinklerSimilarity($lieuA, $lieuB) * 100 : 0;

        // ── SCORE PONDÉRÉ ─────────────────────────────────────────────
        $score = 0;
        $score += $nomScore    * (self::W_NOM        / 100);
        $score += $prenomScore * (self::W_PRENOM     / 100);
        $score += $dobScore100 * (self::W_DOB        / 100);
        $score += $natioScore  * (self::W_NATIONALITE / 100);
        $score += $sexeScore   * (self::W_SEXE       / 100);
        $score += $lieuScore   * (self::W_LIEU       / 100);

        // ── PÉNALITÉ SEXE DIFFÉRENT ────────────────────────────────────────────
        // Deux personnes de sexe différent ne peuvent pas être la même
        // sauf erreur de saisie (rare mais possible).
        if (!$sexeMatch) {
            $score -= 25; // -25 pts : sexe est le seul critère objectif non sujet à erreur de saisie
        }

        // ── PÉNALITÉ DOCUMENT DIFFÉRENT ───────────────────────────────
        // Si les deux ont un document renseigné mais différent,
        // c'est une preuve forte que ce sont des personnes différentes.
        if ($docResult['both_have_doc'] && !$docResult['match']) {
            $score -= 30;
        }

        // ── GARDE-FOU IDENTITÉ ────────────────────────────────────────
        // Si NOM et PRÉNOM sont tous les deux en dessous de 50%,
        // on ne peut pas raisonnablement conclure à la même personne,
        // peu importe les autres critères (même DN, même nationalité).
        // Couvre le cas des homonymes partiels et faux positifs.
        if ($nomScore < 50 && $prenomScore < 50) {
            $score = min($score, 44); // Forcé sous SEUIL_PARTIEL
        }

        $finalScore = (int) max(0, min(100, round($score)));

        return self::buildResult(
            score:          $finalScore,
            sexeMatch:      $sexeMatch,
            prenomRawScore: $prenomScore,
            nomRawScore:    $nomScore,
            details: [
                'nom'         => (int) round($nomScore),
                'prenom'      => (int) round($prenomScore),
                'date'        => $dobExact,
                'date_days'   => $dobDays,
                'lieu'        => (int) round($lieuScore),
                'nationalite' => $natioMatch,
                'sexe'        => $sexeMatch,
                'document'    => null,
            ],
            documentCourt: false
        );
    }

    // ─────────────────────────────────────────────────────────────────
    // MÉTHODE DE SCORING WATCHLIST (NOUVEAU)
    // Utilisable depuis DemandeController::store() pour remplacer
    // la boucle manuelle existante.
    //
    // Usage :
    //   $result = IdentitySimilarityService::compareWithWatchlist($watchEntry, $requestData);
    //   if ($result['score'] >= 60) { ... }
    // ─────────────────────────────────────────────────────────────────

    /**
     * Compare une entrée Watchlist avec les données d'une requête de création.
     * Retourne le même format que compare().
     *
     * @param \App\Models\Watchlist $watch
     * @param array $data  Données de la requête (nom, prenom, date_naissance,
     *                     numero_passeport, telephone, nationalites_id, etc.)
     */
    public static function compareWithWatchlist(\App\Models\Watchlist $watch, array $data): array
    {
        // ── COURT-CIRCUIT DOCUMENT ────────────────────────────────────
        $docData = trim(strtoupper($data['numero_passeport'] ?? $data['numero_document'] ?? ''));
        $docWatch = trim(strtoupper($watch->numero_document ?? ''));

        if ($docData !== '' && $docWatch !== '' && $docData === $docWatch) {
            return [
                'score'      => 100,
                'match'      => true,
                'decision'   => 'MÊME PERSONNE (DOCUMENT IDENTIQUE)',
                'confidence' => '100%',
                'level'      => 'CERTAIN',
                'reason'     => 'Document identique : ' . $docData,
                'details'    => ['document' => $docData],
            ];
        }

        // ── SCORING PONDÉRÉ ───────────────────────────────────────────
        $score   = 0;
        $details = [];

        // Nom (25 pts max) — fuzzy
        $nomA = self::normalize($watch->nom);
        $nomB = self::normalize($data['nom'] ?? '');
        if ($nomA !== '' && $nomB !== '') {
            $ns = self::nameScore($nomA, $nomB);
            $score += $ns * 0.25;
            $details['nom'] = (int) round($ns);
        }

        // Prénom (25 pts max) — fuzzy
        $prenomA = self::normalize($watch->prenom);
        $prenomB = self::normalize($data['prenom'] ?? '');
        if ($prenomA !== '' && $prenomB !== '') {
            $ps = self::nameScore($prenomA, $prenomB);
            $score += $ps * 0.25;
            $details['prenom'] = (int) round($ps);
        }

        // Date de naissance (30 pts max)
        $dobData  = $data['date_naissance'] ?? '';
        $dobWatch = $watch->date_naissance  ?? '';

        if ($dobWatch && $dobData) {
            ['pts' => $dobPts] = self::dobScore($dobWatch, $dobData);
            $score += $dobPts;
            $details['date_naissance'] = $dobPts;
        } elseif ($watch->age_min && $watch->age_max && $dobData) {
            // Fallback : tranche d'âge si pas de DN exacte dans la watchlist
            try {
                $age = (int) \Carbon\Carbon::parse($dobData)->age;
                if ($age >= $watch->age_min && $age <= $watch->age_max) {
                    $score += 15; // Demi-points (incertitude plus grande)
                    $details['tranche_age'] = "Dans tranche {$watch->age_min}–{$watch->age_max}";
                }
            } catch (\Exception $e) {}
        }

        // Téléphone (10 pts — bonus, pas dans le total de base)
        $telData  = self::normalizePhone($data['telephone'] ?? '');
        $telWatch = self::normalizePhone($watch->telephone ?? '');
        if ($telData !== '' && $telWatch !== '' && $telData === $telWatch) {
            $score += 10;
            $details['telephone'] = 'identique';
        }

        // Nationalité (10 pts)
        $natData  = (string) ($data['nationalites_id'] ?? '');
        $natWatch = (string) ($watch->nationalite       ?? '');
        if ($natData !== '' && $natWatch !== '' && $natData === $natWatch) {
            $score += 10;
            $details['nationalite'] = true;
        }

        // Nom père (5 pts) — fuzzy
        $nomPereW = self::normalize($watch->nom_pere ?? '');
        $nomPereD = self::normalize($data['nom_pere'] ?? '');
        if ($nomPereW !== '' && $nomPereD !== '') {
            $ns = self::nameScore($nomPereW, $nomPereD);
            if ($ns >= 80) { $score += 5; $details['nom_pere'] = (int) round($ns); }
        }

        // Nom mère (5 pts) — fuzzy
        $nomMereW = self::normalize($watch->nom_mere ?? '');
        $nomMereD = self::normalize($data['nom_mere'] ?? '');
        if ($nomMereW !== '' && $nomMereD !== '') {
            $ns = self::nameScore($nomMereW, $nomMereD);
            if ($ns >= 80) { $score += 5; $details['nom_mere'] = (int) round($ns); }
        }

        // ── PÉNALITÉ DOCUMENT DIFFÉRENT ───────────────────────────────
        if ($docData !== '' && $docWatch !== '' && $docData !== $docWatch) {
            $score -= 30;
            $details['document'] = 'différent';
        }

        // ── GARDE-FOU ─────────────────────────────────────────────────
        $nomScore    = $details['nom']    ?? 0;
        $prenomScore = $details['prenom'] ?? 0;
        if ($nomScore < 50 && $prenomScore < 50) {
            $score = min($score, 44);
        }

        $finalScore = (int) max(0, min(100, round($score)));
        $level      = self::getLevel($finalScore);

        return [
            'score'      => $finalScore,
            'match'      => $finalScore >= self::SEUIL_PROBABLE,
            'decision'   => self::getLabel($finalScore, true, $prenomScore),
            'confidence' => $finalScore . '%',
            'level'      => $level,
            'details'    => $details,
        ];
    }

    // ─────────────────────────────────────────────────────────────────
    // MÉTHODES INTERNES
    // ─────────────────────────────────────────────────────────────────

    /**
     * Score de similarité pour un nom ou prénom (0–100).
     *
     * Stratégie multi-niveaux :
     * 1. Identité exacte après normalisation → 100
     * 2. Jaro-Winkler sur la chaîne complète → si ≥ seuil, proportionnel
     * 3. Token-sort (gestion inversions "Jean Paul" vs "Paul Jean") → max des scores
     * 4. Token-subset : tous les mots du PLUS LONG présents dans le court
     *    (couvre "Abdoulaye" vs "Abdoulaye Bakary" sans faux positifs "Ali")
     */
    private static function nameScore(string $a, string $b): float
    {
        if ($a === '' || $b === '') return 0.0;
        if ($a === $b) return 100.0;

        // ── Score Jaro-Winkler direct ─────────────────────────────────
        $jwDirect = self::jaroWinklerSimilarity($a, $b) * 100;

        // ── Token-sort (inversion prénom/nom) ─────────────────────────
        $tokA     = explode(' ', $a);
        $tokB     = explode(' ', $b);
        sort($tokA);
        sort($tokB);
        $jwSorted = self::jaroWinklerSimilarity(implode(' ', $tokA), implode(' ', $tokB)) * 100;

        // ── Token-subset : chaque token du PLUS LONG comparé au PLUS COURT ──
        // On utilise le PLUS LONG comme référence (anti faux positifs "Ali")
        // Chaque token du plus long doit trouver un match dans le plus court
        $longer  = count($tokA) >= count($tokB) ? explode(' ', $a) : explode(' ', $b);
        $shorter = count($tokA) >= count($tokB) ? explode(' ', $b) : explode(' ', $a);

        $matchedLong = 0;
        foreach ($longer as $tl) {
            if (strlen($tl) < 2) continue; // Ignorer les initiales seules
            $bestTok = 0.0;
            foreach ($shorter as $ts) {
                if (strlen($ts) < 2) continue;
                $jw = self::jaroWinklerSimilarity($tl, $ts);
                if ($jw > $bestTok) $bestTok = $jw;
            }
            if ($bestTok >= self::JW_THRESHOLD) {
                $matchedLong++;
            }
        }

        $countLong = count(array_filter($longer, fn($t) => strlen($t) >= 2));
        $subsetScore = $countLong > 0 ? ($matchedLong / $countLong) * 100 : 0;

        // Score final = max des trois approches
        $best = max($jwDirect, $jwSorted, $subsetScore);

        // Appliquer un plancher minimal si le score est marginal :
        // en dessous de 50, on considère qu'il n'y a pas de correspondance
        return $best < 50 ? 0.0 : $best;
    }

    /**
     * Score date de naissance.
     * Retourne ['pts' => int, 'match' => bool, 'days' => int|null]
     */
    private static function dobScore(?string $dateA, ?string $dateB): array
    {
        if (empty($dateA) || empty($dateB)) {
            return ['pts' => 0, 'match' => false, 'days' => null];
        }

        // Normaliser au format Y-m-d
        $dA = self::normalizeDate($dateA);
        $dB = self::normalizeDate($dateB);

        if ($dA === '' || $dB === '') {
            return ['pts' => 0, 'match' => false, 'days' => null];
        }

        if ($dA === $dB) {
            return ['pts' => self::W_DOB, 'match' => true, 'days' => 0]; // 30 pts
        }

        try {
            $dtA  = new \DateTime($dA);
            $dtB  = new \DateTime($dB);
            $diff = (int) abs($dtA->getTimestamp() - $dtB->getTimestamp());
            $days = (int) round($diff / 86400);

            if ($days <= self::DOB_TOLERANCE_DAYS) {
                // ±30 jours : erreur de retranscription probable
                return ['pts' => (int) round(self::W_DOB * 0.6), 'match' => false, 'days' => $days]; // 18 pts
            }

            // Même année de naissance : moins probant mais utile
            if (substr($dA, 0, 4) === substr($dB, 0, 4)) {
                return ['pts' => (int) round(self::W_DOB * 0.27), 'match' => false, 'days' => $days]; // 8 pts
            }
        } catch (\Exception $e) {
            // Date non parseable
        }

        return ['pts' => 0, 'match' => false, 'days' => null];
    }

    /**
     * Vérifie si deux impétrants partagent au moins un numéro de document.
     * Cherche dans impetrant_documents ET dans document_demandes via les demandes.
     *
     * Retourne ['match' => bool, 'numero' => string|null, 'both_have_doc' => bool]
     */
    private static function checkDocuments(Impetrant $a, Impetrant $b): array
    {
        // Collecter les numéros de $a
        $docsA = self::collectDocumentNumbers($a);

        // Collecter les numéros de $b
        $docsB = self::collectDocumentNumbers($b);

        $bothHaveDoc = (!empty($docsA) && !empty($docsB));

        // Chercher une intersection
        $common = array_intersect($docsA, $docsB);

        if (!empty($common)) {
            return ['match' => true, 'numero' => reset($common), 'both_have_doc' => true];
        }

        return ['match' => false, 'numero' => null, 'both_have_doc' => $bothHaveDoc];
    }

    /**
     * Collecte tous les numéros de document normalisés d'un impétrant.
     * Sources : impetrant_documents + document_demandes via ses demandes.
     */
    private static function collectDocumentNumbers(Impetrant $impetrant): array
    {
        $nums = [];

        // Source 1 : impetrant_documents (historique direct)
        if ($impetrant->relationLoaded('documents')) {
            foreach ($impetrant->documents as $doc) {
                $n = self::normalizeDocument($doc->numero_document ?? '');
                if ($n !== '') $nums[] = $n;
            }
        } else {
            // Chargement léger si pas en mémoire
            try {
                foreach ($impetrant->documents()->pluck('numero_document') as $n) {
                    $norm = self::normalizeDocument($n);
                    if ($norm !== '') $nums[] = $norm;
                }
            } catch (\Exception $e) {}
        }

        // Source 2 : document_demandes via les demandes de cet impétrant
        try {
            $demandeDocs = \App\Models\DocumentDemande::whereIn(
                'demandes_id',
                \App\Models\Demande::where('impetrants_id', $impetrant->id)->pluck('id')
            )->pluck('numero_document');

            foreach ($demandeDocs as $n) {
                $norm = self::normalizeDocument($n);
                if ($norm !== '') $nums[] = $norm;
            }
        } catch (\Exception $e) {}

        return array_unique($nums);
    }

    /**
     * Vérifie la correspondance de nationalité entre deux impétrants.
     * Gère le fait que nationalites_id est un INT dans impetrants.
     */
    private static function nationaliteMatch(Impetrant $a, Impetrant $b): bool
    {
        // Cas 1 : les deux ont nationalites_id (INT FK) — comparaison directe
        if (!empty($a->nationalites_id) && !empty($b->nationalites_id)) {
            return (int)$a->nationalites_id === (int)$b->nationalites_id;
        }

        // Cas 2 : fallback sur le champ texte "nationalite" si présent
        $natA = self::normalize($a->nationalite ?? '');
        $natB = self::normalize($b->nationalite ?? '');
        if ($natA !== '' && $natB !== '') {
            return $natA === $natB;
        }

        return false;
    }

    // ─────────────────────────────────────────────────────────────────
    // ALGORITHME JARO-WINKLER (implémentation native PHP)
    // ─────────────────────────────────────────────────────────────────

    /**
     * Similarité Jaro-Winkler entre deux chaînes normalisées.
     * Retourne 0.0 à 1.0.
     * Idéale pour les noms propres courts — favorise les préfixes communs.
     */
    private static function jaroWinklerSimilarity(string $a, string $b): float
    {
        if ($a === $b) return 1.0;
        if ($a === '' || $b === '') return 0.0;

        $lenA = mb_strlen($a);
        $lenB = mb_strlen($b);

        $matchDist = (int) floor(max($lenA, $lenB) / 2) - 1;
        if ($matchDist < 0) $matchDist = 0;

        $aMatches = array_fill(0, $lenA, false);
        $bMatches = array_fill(0, $lenB, false);
        $matches = 0;

        for ($i = 0; $i < $lenA; $i++) {
            $start = max(0, $i - $matchDist);
            $end   = min($i + $matchDist + 1, $lenB);
            for ($j = $start; $j < $end; $j++) {
                if ($bMatches[$j] || mb_substr($a, $i, 1) !== mb_substr($b, $j, 1)) continue;
                $aMatches[$i] = $bMatches[$j] = true;
                $matches++;
                break;
            }
        }

        if ($matches === 0) return 0.0;

        // Transpositions
        $trans = 0;
        $k = 0;
        for ($i = 0; $i < $lenA; $i++) {
            if (!$aMatches[$i]) continue;
            while (!$bMatches[$k]) $k++;
            if (mb_substr($a, $i, 1) !== mb_substr($b, $k, 1)) $trans++;
            $k++;
        }

        $jaro = ($matches/$lenA + $matches/$lenB + ($matches - $trans/2)/$matches) / 3;

        // Bonus Winkler : préfixe commun (max 4 chars)
        $prefix = 0;
        $maxP   = min(4, min($lenA, $lenB));
        for ($i = 0; $i < $maxP; $i++) {
            if (mb_substr($a, $i, 1) !== mb_substr($b, $i, 1)) break;
            $prefix++;
        }

        return $jaro + $prefix * 0.1 * (1 - $jaro);
    }

    // ─────────────────────────────────────────────────────────────────
    // NORMALISATION
    // ─────────────────────────────────────────────────────────────────

    /**
     * Normalise une chaîne : majuscules, sans accents, sans ponctuation.
     * Compatible avec la v1 — même logique, même résultat.
     */
    private static function normalize(?string $value): string
    {
        if (!$value) return '';

        $value = mb_strtoupper($value, 'UTF-8');

        if (function_exists('iconv')) {
            $trans = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
            if ($trans !== false && $trans !== null) {
                $value = $trans;
            }
        }

        $value = preg_replace('/[^A-Z0-9 ]/', '', $value);
        return trim(preg_replace('/\s+/', ' ', $value));
    }

    /**
     * Normalise un numéro de document : majuscules, sans espaces/tirets.
     */
    private static function normalizeDocument(string $value): string
    {
        $v = preg_replace('/[\s\-\.]/', '', strtoupper(trim($value)));
        return $v === '' ? '' : $v;
    }

    /**
     * Normalise un numéro de téléphone : chiffres seulement, sans préfixe +242/00242.
     */
    private static function normalizePhone(string $value): string
    {
        $v = preg_replace('/[^\d+]/', '', trim($value));
        $v = preg_replace('/^\+242/', '242', $v);
        $v = preg_replace('/^00242/', '242', $v);
        $v = preg_replace('/^0/', '',  $v);
        return $v;
    }

    /**
     * Normalise une date au format Y-m-d.
     * Accepte : d/m/Y, d-m-Y, Y-m-d, Y/m/d
     */
    private static function normalizeDate(string $value): string
    {
        $value = trim($value);
        if ($value === '') return '';

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) return $value;

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $value, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }

        if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $value, $m)) {
            return sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
        }

        $ts = strtotime($value);
        return $ts !== false ? date('Y-m-d', $ts) : '';
    }

    // ─────────────────────────────────────────────────────────────────
    // LABELS ET NIVEAUX — Compatibles v1
    // ─────────────────────────────────────────────────────────────────

    private static function buildResult(
        int    $score,
        bool   $sexeMatch,
        float  $prenomRawScore,
        float  $nomRawScore,
        array  $details,
        bool   $documentCourt = false
    ): array {
        return [
            'score'      => $score,
            'match'      => ($score >= self::SEUIL_QUASI),
            'decision'   => self::getLabel($score, $sexeMatch, $prenomRawScore, $documentCourt),
            'confidence' => $score . '%',
            'level'      => self::getLevel($score),
            'details'    => $details,
        ];
    }

    private static function getLabel(
        int   $score,
        bool  $sexeMatch,
        float $prenomScore,
        bool  $documentCourt = false
    ): string {
        if ($documentCourt) {
            return 'MÊME PERSONNE (DOCUMENT IDENTIQUE)';
        }
        if (!$sexeMatch && $score < 40) {
            return 'PERSONNES DIFFÉRENTES (CONFLIT DE GENRE)';
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
