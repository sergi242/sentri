<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Pays;
use App\Models\User;
use App\Models\Demande;
use App\Models\Employeur;
use App\Models\SoitTransmis;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use App\Models\FrontiereCongo;
use Illuminate\Support\Facades\DB;
use App\Models\CategorieSocioProfessionnelle;
use App\Models\Departement;

class ReportingController extends Controller
{

    public function employeur()
    {
        $employeurs = Employeur::all();
        return view('admin.reporting.employeur.index', compact('employeurs'));
    }
    public function employeurShow(Request $request)
    {
        $users = User::all();
        $nomDocument = $request->input('nomDocument');
        $dateDebut = $request->input('duree_travail_domicile_de');
        $dateFin = $request->input('duree_travail_domicile_a');
        $typeEmployeur = $request->input('type_employeur');
        $employeurId = $request->input('employeur_id');
        $query = DB::table('impetrants')
            ->select(
                'employeurs.nom_employeur',
                DB::raw("SUM(impetrants.sexe = 'Masculin') AS Masculin"),
                DB::raw("SUM(impetrants.sexe = 'Féminin') AS Feminin"),
                DB::raw("COUNT(impetrants.id) AS total"),
                'pays.lib_pays AS nationnalite'
            )
            ->join('pays', 'impetrants.nationalites_id', '=', 'pays.id')
            ->join('demandes', 'impetrants.id', '=', 'demandes.impetrants_id')
            ->join('employeurs', 'demandes.employeur_id', '=', 'employeurs.id')
            ->when($typeEmployeur && $typeEmployeur !== 'all_type', function ($query) use ($typeEmployeur) {
                return $query->where('employeurs.type', $typeEmployeur);
            })
            ->when($employeurId && $employeurId !== 'all', function ($query) use ($employeurId) {
                return $query->where('employeurs.id', $employeurId);
            })
            ->groupBy('employeurs.nom_employeur');

        if ($dateDebut && $dateFin) {
            $query->whereBetween('demandes.date_demande', [$dateDebut, $dateFin]);
        }
        $resultats = $query->get();

        // dd($nomDocument);
        if (count($resultats) > 0) {
            $titre = $nomDocument;
            $hommes = $resultats->sum('Masculin');
            $femmes = $resultats->sum('Feminin');
            $total = $resultats->sum('total');
            $employeur = null;
            $somme = [
                'Masculin' =>  $hommes,
                'Feminin' =>  $femmes,
                'total_par_nationnalite' =>  $total,
            ];

            if ($typeEmployeur == 'all_type') {
                $all = true;
            } else {
                $all = false;
                $employeur = $resultats[0]->nom_employeur;
            }
            // Obtenez les données sur les nationalités par employeur
            $nationalitesParEmployeur = [];

            foreach ($resultats as $resultat) {
                $employeur = $resultat->nom_employeur;
                $nationalite = $resultat->nationnalite;

                if (!isset($nationalitesParEmployeur[$employeur])) {
                    $nationalitesParEmployeur[$employeur] = [];
                }

                $nationalitesParEmployeur[$employeur][$nationalite] = $resultat->total;
            }
        } else {
            toastr()->info("Aucun resultat trouvé");
            return back();
        }

        return view('admin.reporting.employeur.show', compact('users', 'resultats', 'somme', 'employeur', 'all', 'titre', 'nationalitesParEmployeur', 'employeurId', 'nomDocument', 'dateDebut', 'dateFin', 'typeEmployeur'));

        // return view('admin.reporting.employeur.show', compact('resultats', 'somme', 'employeur', 'all', 'titre', 'nationalitesData'));

    }
    public function employeurReportingPdf(Request $request)
{
    // Récupérer les paramètres supplémentaires
    $entete = $request->input('entete', 1);
    $section = $request->input('section', 'Toutes les sections');
    $signataireId = $request->input('signataire');
    $commentaires = $request->input('commentaire', '');

    // Récupérer les informations du signataire si défini
    $signataire = $signataireId ? User::find($signataireId) : null;

    // Configuration des sections et divisions
    $sectionsConfig = config('sections.sections');
    $division = null;

    foreach ($sectionsConfig as $div) {
        foreach ($div['sections'] as $sect) {
            if ($sect['name'] == $section) {
                $division = $div['division'];
                break 2;
            }
        }
    }

    // Récupération des autres paramètres existants
    $nomDocument = $request->input('nomDocument');
    $dateDebut = $request->input('duree_travail_domicile_de');
    $dateFin = $request->input('duree_travail_domicile_a');
    $typeEmployeur = $request->input('type_employeur');
    $employeurId = $request->input('employeur_id');

    $query = DB::table('impetrants')
        ->select(
            'employeurs.nom_employeur',
            DB::raw("SUM(impetrants.sexe = 'Masculin') AS Masculin"),
            DB::raw("SUM(impetrants.sexe = 'Féminin') AS Feminin"),
            DB::raw("COUNT(impetrants.id) AS total"),
            'pays.lib_pays AS nationnalite'
        )
        ->join('pays', 'impetrants.nationalites_id', '=', 'pays.id')
        ->join('demandes', 'impetrants.id', '=', 'demandes.impetrants_id')
        ->join('employeurs', 'demandes.employeur_id', '=', 'employeurs.id')
        ->when($typeEmployeur && $typeEmployeur !== 'all_type', function ($query) use ($typeEmployeur) {
            return $query->where('employeurs.type', $typeEmployeur)
                ->groupBy('pays.lib_pays');
        })
        ->when($typeEmployeur && $typeEmployeur === 'all_type', function ($query) {
            return $query->groupBy('employeurs.nom_employeur');
        })
        ->when($employeurId && $employeurId !== 'all', function ($query) use ($employeurId) {
            return $query->where('employeurs.id', $employeurId);
        });

    if ($dateDebut && $dateFin) {
        $query->whereBetween('demandes.date_demande', [$dateDebut, $dateFin]);
    }

    $resultats = $query->get();

    $title = $nomDocument;
    $hommes = $resultats->sum('Masculin');
    $femmes = $resultats->sum('Feminin');
    $total = $resultats->sum('total');
    $employeur = null;
    $somme = [
        'Masculin' => $hommes,
        'Feminin' => $femmes,
        'total_par_nationnalite' => $total,
    ];

    if (count($resultats) > 0) {
        if ($typeEmployeur == 'all_type') {
            $all = true;
        } else {
            $all = false;
            $employeur = $resultats[0]->nom_employeur;
        }
    } else {
        toastr()->info("Aucun résultat trouvé");
        return back();
    }

    // Générer le PDF avec les nouvelles informations
    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
    $html2pdf->setDefaultFont('Arial');

    $html = view("admin.reporting.employeur.pdf.employeur", compact(
        'resultats', 
        'somme', 
        'employeur', 
        'all', 
        'title', 
        'nomDocument', 
        'division', 
        'section', 
        'entete', 
        'signataire', 
        'commentaires'
    ))->render();

    $html2pdf->writeHTML($html);
    return $html2pdf->output($nomDocument . ".pdf");
}

    public function categorie()
    {
        return view('admin.reporting.categorie.index');
    }
    public function nationnalite()
    {
        return view('admin.reporting.nationnalite.index');
    }
    public function impetrant()
    {
        $categories = CategorieSocioProfessionnelle::all();
        $nationnalites = Pays::all();
        $users = User::all();
        return view('admin.reporting.impetrant.index', compact('categories', 'nationnalites', 'users'));
    }

    public function impetrantShow(Request $request)
    {
        $users = User::all();

        // Récupération des paramètres de la requête
        $nom_document = $request->input('nom_document');
        $pays_id = $request->input('pays_id');
        $statut_demande = $request->input('statut_demande');
        $categories_id = $request->input('categories_id');
        $type_demande = $request->input('type_demande');
        $age_a = $request->input('age_a');
        $age_de = $request->input('age_de');
        $demande_de = $request->input('demande_de');
        $demande_a = $request->input('demande_a');
        $etat_civil = $request->input('etat_civil');
        $genre = $request->input('genre');
    
        // Requête principale pour obtenir les impétrants filtrés par les différents critères
        $query = DB::table('impetrants')
            ->select('impetrants.id', 'impetrants.sexe', 'pays.lib_pays AS nationalite')
            ->leftJoin('pays', 'impetrants.nationalites_id', '=', 'pays.id')
            ->leftJoin('demandes', 'impetrants.id', '=', 'demandes.impetrants_id')
            ->when($pays_id && $pays_id !== 'all_pays_id', function ($query) use ($pays_id) {
                return $query->where('pays.id', $pays_id);
            })
            ->when($statut_demande && $statut_demande !== 'all_statut_demande', function ($query) use ($statut_demande) {
                return $query->where('demandes.statut_demande', $statut_demande);
            })
            ->when($categories_id && $categories_id !== 'all_categories_id', function ($query) use ($categories_id) {
                return $query->where('demandes.categorie_socioprof_id', $categories_id);
            })
            ->when($type_demande && $type_demande !== 'all_type_demande', function ($query) use ($type_demande) {
                return $query->where('demandes.type_demande', $type_demande);
            })
            ->when($age_de && $age_a, function ($query) use ($age_de, $age_a) {
                return $query->whereBetween(DB::raw('YEAR(NOW()) - YEAR(impetrants.date_naissance)'), [$age_de, $age_a]);
            })
            ->when($demande_de && $demande_a, function ($query) use ($demande_de, $demande_a) {
                return $query->whereBetween('demandes.created_at', [$demande_de, $demande_a]);
            })
            ->when($etat_civil && $etat_civil !== 'all_etat_civil', function ($query) use ($etat_civil) {
                return $query->where('demandes.etat_civil', $etat_civil);
            })
            ->when($genre && $genre !== 'all_genre', function ($query) use ($genre) {
                return $query->where('impetrants.sexe', $genre);
            })
            ->distinct();
    
        // Comptage des impétrants distincts par nationalité et statut de la demande
        $resultats = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query) // Merge les bindings pour éviter l'erreur de paramètres
            ->select(
                DB::raw("SUM(sub.sexe = 'Masculin') AS Masculin"),
                DB::raw("SUM(sub.sexe = 'Féminin') AS Feminin"),
                DB::raw("COUNT(sub.id) AS total"),
                'sub.nationalite'
            )
            ->groupBy('sub.nationalite')
            ->get();
    
        if (count($resultats) > 0) {
            $titre = $nom_document;
            $hommes = $resultats->sum('Masculin');
            $femmes = $resultats->sum('Feminin');
            $total = $resultats->sum('total');
            $somme = [
                'Masculin' => $hommes,
                'Feminin' => $femmes,
                'total_par_nationalite' => $total,
            ];
    
            // Obtenez les données sur les nationalités par genre
            $nationalitesParGenre = [];
            foreach ($resultats as $resultat) {
                $nationalite = $resultat->nationalite;
                $masculin = $resultat->Masculin;
                $feminin = $resultat->Feminin;
    
                if (!isset($nationalitesParGenre[$nationalite])) {
                    $nationalitesParGenre[$nationalite] = [];
                }
    
                $nationalitesParGenre[$nationalite]['Masculin'] = $masculin;
                $nationalitesParGenre[$nationalite]['Feminin'] = $feminin;
            }
        } else {
            toastr()->info("Aucun résultat trouvé");
            return back();
        }
    
        return view('admin.reporting.impetrant.show', compact('resultats', 'somme', 'nationalitesParGenre', 'nom_document', 'pays_id', 'statut_demande', 'categories_id', 'type_demande', 'age_a', 'age_de', 'demande_de', 'demande_a', 'etat_civil', 'genre', 'users'));
    }
    
    

    public function impetrantReportingPdf(Request $request)
    {
        // Récupération des paramètres de la requête
        $nom_document = $request->input('nom_document');
        $genre = $request->input('genre');
        $etat_civil = $request->input('etat_civil');
        $statut_demande = $request->input('statut_demande');
        $age_de = $request->input('age_de');
        $age_a = $request->input('age_a');
        $demande_de = $request->input('demande_de');
        $demande_a = $request->input('demande_a');
        $type_demande = $request->input('type_demande');
        $categories_id = $request->input('categories_id');
        $pays_id = $request->input('pays_id');
    
        // Récupération des champs supplémentaires
        $entete = $request->input('entete', 1);
        $commentaires = $request->input('commentaire', '');
        $selectedSection = $request->input('section', 'Toutes les sections');
        $signataireId = $request->input('signataire');
        $signataire = $signataireId ? User::find($signataireId) : null;
    
        // Récupérer les données de configuration pour trouver la division et section
        $sectionsConfig = config('sections.sections');
        $division = null;
        $section = null;
    
        foreach ($sectionsConfig as $div) {
            foreach ($div['sections'] as $sect) {
                if ($sect['name'] == $selectedSection) {
                    $division = $div['division'];
                    $section = $sect['name'];
                    break 2;
                }
            }
        }
    
        // Requête principale pour obtenir les impétrants filtrés par les différents critères
        $query = DB::table('impetrants')
            ->select('impetrants.id', 'impetrants.sexe', 'pays.lib_pays AS nationalite')
            ->leftJoin('pays', 'impetrants.nationalites_id', '=', 'pays.id')
            ->leftJoin('demandes', 'impetrants.id', '=', 'demandes.impetrants_id')
            ->leftJoin('categorie_socio_professionnelles', 'demandes.categorie_socioprof_id', '=', 'categorie_socio_professionnelles.id')
            ->when($genre && $genre !== 'all_genre', function ($query) use ($genre) {
                return $query->where('impetrants.sexe', $genre);
            })
            ->when($etat_civil && $etat_civil !== 'all_etat_civil', function ($query) use ($etat_civil) {
                return $query->where('demandes.etat_civil', $etat_civil);
            })
            ->when($type_demande && $type_demande !== 'all_type_demande', function ($query) use ($type_demande) {
                return $query->where('demandes.type_demande', $type_demande);
            })
            ->when($statut_demande && $statut_demande !== 'all_statut_demande', function ($query) use ($statut_demande) {
                return $query->where('demandes.statut_demande', $statut_demande);
            })
            ->when($categories_id && $categories_id !== 'all_categories_id', function ($query) use ($categories_id) {
                return $query->where('categorie_socio_professionnelles.id', $categories_id);
            })
            ->when($pays_id && $pays_id !== 'all_pays_id', function ($query) use ($pays_id) {
                return $query->where('pays.id', $pays_id);
            })
            ->when($age_de !== null && $age_a !== null, function ($query) use ($age_de, $age_a) {
                return $query->whereRaw('YEAR(NOW()) - YEAR(impetrants.date_naissance) BETWEEN ? AND ?', [$age_de, $age_a]);
            })
            ->when($demande_de !== null && $demande_a !== null, function ($query) use ($demande_de, $demande_a) {
                return $query->whereBetween('demandes.created_at', [$demande_de, $demande_a]);
            })
            ->distinct();
    
        // Comptage des impétrants distincts
        $resultats = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->select(
                DB::raw("SUM(sub.sexe = 'Masculin') AS Masculin"),
                DB::raw("SUM(sub.sexe = 'Féminin') AS Feminin"),
                DB::raw("COUNT(sub.id) AS total"),
                'sub.nationalite'
            )
            ->groupBy('sub.nationalite')
            ->get();
    
        if (count($resultats) > 0) {
            $title = $nom_document;
            $hommes = $resultats->sum('Masculin');
            $femmes = $resultats->sum('Feminin');
            $total = $resultats->sum('total');
            $somme = [
                'Masculin' => $hommes,
                'Feminin' => $femmes,
                'total_par_nationalite' => $total,
            ];
    
            $nationalitesParGenre = [];
            foreach ($resultats as $resultat) {
                $nationalite = $resultat->nationalite;
                $masculin = $resultat->Masculin;
                $feminin = $resultat->Feminin;
    
                if (!isset($nationalitesParGenre[$nationalite])) {
                    $nationalitesParGenre[$nationalite] = [];
                }
    
                $nationalitesParGenre[$nationalite]['Masculin'] = $masculin;
                $nationalitesParGenre[$nationalite]['Feminin'] = $feminin;
            }
        } else {
            toastr()->info("Aucun résultat trouvé");
            return back();
        }
    
        // Génération du PDF avec Html2Pdf
        $html = view('admin.reporting.impetrant.pdf.impetrant', compact(
            'resultats', 'somme', 'title', 'nom_document', 'nationalitesParGenre',
            'entete', 'division', 'section', 'commentaires', 'signataire'
        ))->render();
    
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Times');
        $html2pdf->writeHTML($html);
    
        return $html2pdf->output($nom_document . ".pdf");
    }
    

    public function impetrantListing(Request $request)
    {
        // Récupération des paramètres de la requête
        $nom_document = $request->input('nom_document');
        $genre = $request->input('genre');
        $etat_civil = $request->input('etat_civil');
        $age_de = $request->input('age_de');
        $age_a = $request->input('age_a');
        $categories_id = $request->input('categories_id');
        $pays_id = $request->input('pays_id');
        $entete = $request->input('entete');
        $section = $request->input('section');
        $signataire = User::find($request->input('signataire'));
        $commentaire = $request->input('commentaire');

        // Requête principale pour obtenir les impétrants filtrés
        $impetrants = DB::table('impetrants')
            ->select(
                'impetrants.id', 
                'impetrants.sexe', 
                'impetrants.nom', 
                'impetrants.prenom',
                'impetrants.date_naissance',
                'pays.lib_pays AS nationalite',
                DB::raw('(SELECT photo FROM demandes WHERE demandes.impetrants_id = impetrants.id ORDER BY demandes.created_at DESC LIMIT 1) as photo')
            )
            ->leftJoin('pays', 'impetrants.nationalites_id', '=', 'pays.id')
            ->when($genre && $genre !== 'all_genre', function ($query) use ($genre) {
                return $query->where('impetrants.sexe', $genre);
            })
            ->when($etat_civil && $etat_civil !== 'all_etat_civil', function ($query) use ($etat_civil) {
                return $query->where('impetrants.etat_civil', $etat_civil);
            })
            ->when($categories_id && $categories_id !== 'all_categories_id', function ($query) use ($categories_id) {
                return $query->where('impetrants.categorie_socioprof_id', $categories_id);
            })
            ->when($pays_id && $pays_id !== 'all_pays_id', function ($query) use ($pays_id) {
                return $query->where('pays.id', $pays_id);
            })
            ->when($age_de !== null && $age_a !== null, function ($query) use ($age_de, $age_a) {
                return $query->whereRaw('YEAR(NOW()) - YEAR(impetrants.date_naissance) BETWEEN ? AND ?', [$age_de, $age_a]);
            })
            ->distinct()->get();

        // Retourne la vue avec les données
        return view('admin.reporting.impetrant.listing', compact(
            'impetrants', 'nom_document', 'genre', 'etat_civil', 'age_de', 'age_a', 
            'categories_id', 'pays_id', 'entete', 'section', 'signataire', 'commentaire'
        ));
    }

    public function impetrantReportingListingPdf(Request $request)
    {
        // Récupération des paramètres de la requête
        $title = $request->input('nom_document');
        $genre = $request->input('genre');
        $etat_civil = $request->input('etat_civil');
        $age_de = $request->input('age_de');
        $age_a = $request->input('age_a');
        $categories_id = $request->input('categories_id');
        $pays_id = $request->input('pays_id');
        $section = $request->input('section');
        $signataireId = $request->input('signataire');
        $commentaire = $request->input('commentaire');
        $entete = $request->input('entete', 1);
    
        // Récupération du signataire
        $signataire = $signataireId ? User::find($signataireId) : null;
    
        // Sous-requête pour récupérer la dernière photo d'une demande pour chaque impétrant
        $subQuery = DB::table('demandes')
            ->select('demandes.impetrants_id', 'demandes.photo')
            ->whereNotNull('demandes.photo')
            ->orderBy('demandes.created_at', 'desc');
    
        // Requête principale pour obtenir les impétrants filtrés par les différents critères
        $impetrants = DB::table('impetrants')
            ->select(
                'impetrants.id', 
                'impetrants.sexe', 
                'impetrants.nom', 
                'impetrants.prenom',
                'impetrants.date_naissance',
                'pays.lib_pays AS nationalite',
                DB::raw('(SELECT photo FROM demandes WHERE demandes.impetrants_id = impetrants.id ORDER BY demandes.created_at DESC LIMIT 1) as photo')
            )
            ->leftJoin('pays', 'impetrants.nationalites_id', '=', 'pays.id')
            ->when($genre && $genre !== 'all_genre', function ($query) use ($genre) {
                return $query->where('impetrants.sexe', $genre);
            })
            ->when($etat_civil && $etat_civil !== 'all_etat_civil', function ($query) use ($etat_civil) {
                return $query->where('impetrants.etat_civil', $etat_civil);
            })
            ->when($categories_id && $categories_id !== 'all_categories_id', function ($query) use ($categories_id) {
                return $query->where('impetrants.categorie_socioprof_id', $categories_id);
            })
            ->when($pays_id && $pays_id !== 'all_pays_id', function ($query) use ($pays_id) {
                return $query->where('pays.id', $pays_id);
            })
            ->when($age_de !== null && $age_a !== null, function ($query) use ($age_de, $age_a) {
                return $query->whereRaw('YEAR(NOW()) - YEAR(impetrants.date_naissance) BETWEEN ? AND ?', [$age_de, $age_a]);
            })
            ->distinct()->get();
    
        // Gérer le cas où aucun impétrant n'est trouvé
        if ($impetrants->isEmpty()) {
            toastr()->info("Aucun impétrant trouvé.");
            return back();
        }
    
        // Récupération des données d'entête et de section
        $sectionsConfig = config('sections.sections');
        $division = null;
    
        foreach ($sectionsConfig as $div) {
            foreach ($div['sections'] as $sect) {
                if ($sect['name'] == $section) {
                    $division = $div['division'];
                    break;
                }
            }
        }
    
        // Génération du PDF
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Times');
    
        $html2pdf->writeHTML(
            view("admin.reporting.impetrant.pdf.listing_impetrant", compact(
                'impetrants', 
                'title', 
                'entete', 
                'section', 
                'division', 
                'signataire', 
                'commentaire'
            ))->render()
        );
    
        return $html2pdf->output($title . ".pdf");
    }    
    
    // Implementation du soit transmis
    public function soitTransmisPDF(Request $request)
    {
        $soit_transmis_id = $request->input('soit_transmis_id');
        $signataire = $request->input('signataire');
        $soit_transmis = SoitTransmis::withCount('demandes as demandes_count')->where('id', $soit_transmis_id)->first();
        $lettre_number = $this->returnLettreNumber($soit_transmis->demandes_count);
        $demandes = Demande::where("soit_transmis_id", $soit_transmis_id)->get();

        if ($demandes->count() == 0) {
            toastr()->info("Ce Soit-Transmis ne contient aucune demande");
            return back();
        }
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('times');
        $pdf = 'A';
        
        $html2pdf->writeHTML(view("admin.reporting.stransmis.stransmis", compact('soit_transmis', 'lettre_number', 'signataire', 'demandes'))->render());
    
        return $html2pdf->output($soit_transmis->numero . ".pdf");
    }


    public function returnLettreNumber($number)
    {
        $nombres = [
            1 => 'Un',
            2 => 'Deux',
            3 => 'Trois',
            4 => 'Quatre',
            5 => 'Cinq',
            6 => 'Six',
            7 => 'Sept',
            8 => 'Huit',
            9 => 'Neuf',
            10 => 'Dix',
            11 => 'Onze',
            12 => 'Douze',
            13 => 'Treize',
            14 => 'Quatorze',
            15 => 'Quinze',
            16 => 'Seize',
            17 => 'Dix-Sept',
            18 => 'Dix-Huit',
            19 => 'Dix-Neuf',
            20 => 'Vingt',
            21 => 'Vingt-et-Un',
            22 => 'Vingt-Deux',
            23 => 'Vingt-Trois',
            24 => 'Vingt-Quatre',
            25 => 'Vingt-Cinq',
            26 => 'Vingt-Six',
            27 => 'Vingt-Sept',
            28 => 'Vingt-Huit',
            29 => 'Vingt-Neuf',
            30 => 'Trente',
            31 => 'Trente-et-Un',
            32 => 'Trente-Deux',
            33 => 'Trente-Trois',
            34 => 'Trente-Quatre',
            35 => 'Trente-Cinq',
            36 => 'Trente-Six',
            37 => 'Trente-Sept',
            38 => 'Trente-Huit',
            39 => 'Trente-Neuf',
            40 => 'Quarante',
            41 => 'Quarante-et-Un',
            42 => 'Quarante-Deux',
            43 => 'Quarante-Trois',
            44 => 'Quarante-Quatre',
            45 => 'Quarante-Cinq',
            46 => 'Quarante-Six',
            47 => 'Quarante-Sept',
            48 => 'Quarante-Huit',
            49 => 'Quarante-Neuf',
            50 => 'Cinquante',
            51 => 'Cinquante-et-Un',
            52 => 'Cinquante-Deux',
            53 => 'Cinquante-Trois',
            54 => 'Cinquante-Quatre',
            55 => 'Cinquante-Cinq',
            56 => 'Cinquante-Six',
            57 => 'Cinquante-Sept',
            58 => 'Cinquante-Huit',
            59 => 'Cinquante-Neuf',
            60 => 'Soixante',
            61 => 'Soixante-et-Un',
            62 => 'Soixante-Deux',
            63 => 'Soixante-Trois',
            64 => 'Soixante-Quatre',
            65 => 'Soixante-Cinq',
            66 => 'Soixante-Six',
            67 => 'Soixante-Sept',
            68 => 'Soixante-Huit',
            69 => 'Soixante-Neuf',
            70 => 'Soixante-Dix',
            71 => 'Soixante-et-Onze',
            72 => 'Soixante-Douze',
            73 => 'Soixante-Treize',
            74 => 'Soixante-Quatorze',
            75 => 'Soixante-Quinze',
            76 => 'Soixante-Seize',
            77 => 'Soixante-Dix-Sept',
            78 => 'Soixante-Dix-Huit',
            79 => 'Soixante-Dix-Neuf',
            80 => 'Quatre-Vingt',
            81 => 'Quatre-Vingt-Un',
            82 => 'Quatre-Vingt-Deux',
            83 => 'Quatre-Vingt-Trois',
            84 => 'Quatre-Vingt-Quatre',
            85 => 'Quatre-Vingt-Cinq',
            86 => 'Quatre-Vingt-Six',
            87 => 'Quatre-Vingt-Sept',
            88 => 'Quatre-Vingt-Huit',
            89 => 'Quatre-Vingt-Neuf',
            90 => 'Quatre-Vingt-Dix',
            91 => 'Quatre-Vingt-Onze',
            92 => 'Quatre-Vingt-Douze',
            93 => 'Quatre-Vingt-Treize',
            94 => 'Quatre-Vingt-Quatorze',
            95 => 'Quatre-Vingt-Quinze',
            96 => 'Quatre-Vingt-Seize',
            97 => 'Quatre-Vingt-Dix-Sept',
            98 => 'Quatre-Vingt-Dix-Huit',
            99 => 'Quatre-Vingt-Dix-Neuf',
            100 => 'Cent',
        ];        
        if ($number != null) {
            $lettre_number = $nombres[$number];
            return $lettre_number;
        } else {
            return null;
        }
    }

    public function fluxMigratooire()
    {
        $departements = Departement::all();
        return view('admin.reporting.flux_migratoire.index', compact('departements'));        
    }
    
   public function fluxmigratoireReportingPdf(Request $request )
    {
        // dd($request);
        // $fronts = FrontiereCongo::take(5)->get();
        $fronts = FrontiereCongo::where("departements_id", $request->departement_id)->get();
        // dd($request->departement_id);
        $pays = Pays::whereHas("flux")->get();
        $dtone = request()->get("dtone") ?? Carbon::now();
        $dtwo = request()->get("dtwo") ?? Carbon::now();
        $html2pdf = new Html2Pdf('L', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $pdf = 'A';
        $html2pdf->writeHTML(view("admin.reporting.flux_migratoire.pdf.flux", compact("fronts", "pays", "dtone", "dtwo"))->render());
        return $html2pdf->output(time() . "temp.pdf");
    }
}
