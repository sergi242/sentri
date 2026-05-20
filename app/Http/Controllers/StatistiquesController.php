<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\FluxMigratoire;
use App\Models\User;
use App\Models\SoitTransmis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class StatistiquesController extends Controller
{
    /**
     * Dashboard principal des statistiques
     */
    public function index()
    {
        // Statistiques globales
        $stats = [
            'total_demandes' => Demande::count(),
            'demandes_mois' => Demande::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'demandes_approuvees' => Demande::where('statut_demande', 'Approuvée')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'demandes_attente' => Demande::where('statut_demande', 'LIKE', '%attente%')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'demandes_contentieux' => Demande::where('statut_demande', 'Envoyée au contentieux')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'attributions_mois' => Demande::where('attribue', 1)->whereMonth('date_attribution', now()->month)->whereYear('date_attribution', now()->year)->count(),
            'flux_entrees_mois' => FluxMigratoire::whereMonth('date_movement', now()->month)->whereYear('date_movement', now()->year)->sum('total_entree'),
            'flux_sorties_mois' => FluxMigratoire::whereMonth('date_movement', now()->month)->whereYear('date_movement', now()->year)->sum('total_sortie'),
        ];
        
        // Agents actifs
        $agents = User::withCount([
                'demandes as demandes_count' => function($q) {
                $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            }
        ])->having('demandes_count', '>', 0)
        ->orderBy('demandes_count', 'desc')
        ->limit(10)
        ->get();
        
        return view('admin.statistiques.dashboard', compact('stats', 'agents'));
    }
    
    /**
     * API : Données graphique demandes par jour
     */
    public function apiDemandesParJour(Request $request)
    {
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);
        
        $demandes = Demande::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN statut_demande = 'Approuvée' THEN 1 ELSE 0 END) as approuvees"),
                DB::raw("SUM(CASE WHEN statut_demande LIKE '%attente%' THEN 1 ELSE 0 END) as attente"),
                DB::raw("SUM(CASE WHEN statut_demande = 'Envoyée au contentieux' THEN 1 ELSE 0 END) as contentieux")
            )
            ->whereMonth('created_at', $mois)
            ->whereYear('created_at', $annee)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return response()->json($demandes);
    }
    
    /**
     * API : Données graphique demandes par type
     */
    public function apiDemandesParType(Request $request)
    {
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);
        
        $types = Demande::select('type_demande', DB::raw('COUNT(*) as total'))
            ->whereMonth('created_at', $mois)
            ->whereYear('created_at', $annee)
            ->groupBy('type_demande')
            ->orderBy('total', 'desc')
            ->get();
        
        return response()->json($types);
    }
    
    /**
     * API : Données graphique demandes par statut
     */
    public function apiDemandesParStatut(Request $request)
    {
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);
        
        $statuts = Demande::select('statut_demande', DB::raw('COUNT(*) as total'))
            ->whereMonth('created_at', $mois)
            ->whereYear('created_at', $annee)
            ->groupBy('statut_demande')
            ->orderBy('total', 'desc')
            ->get();
        
        return response()->json($statuts);
    }
    
    /**
     * API : Données graphique demandes par agent
     */
    public function apiDemandesParAgent(Request $request)
    {
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);
        
        $agents = Demande::select('created_by', DB::raw('COUNT(*) as total'))
            ->with('createur:id,nom,prenom')
            ->whereMonth('created_at', $mois)
            ->whereYear('created_at', $annee)
            ->groupBy('created_by')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'agent' => $item->createur ? $item->createur->getNomPrenom() : 'Non défini',
                    'total' => $item->total
                ];
            });
        
        return response()->json($agents);
    }
    
    /**
     * API : Flux migratoires par jour
     */
    public function apiFluxParJour(Request $request)
    {
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);
        
        $flux = FluxMigratoire::select(
                DB::raw('DATE(date_movement) as date'),
                DB::raw('SUM(total_entree) as entrees'),
                DB::raw('SUM(total_sortie) as sorties')
            )
            ->whereMonth('date_movement', $mois)
            ->whereYear('date_movement', $annee)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return response()->json($flux);
    }
    
    /**
     * API : Flux par frontière
     */
    public function apiFluxParFrontiere(Request $request)
    {
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);
        
        $frontieres = FluxMigratoire::select(
                'frontieres_id',
                DB::raw('SUM(total_entree) as entrees'),
                DB::raw('SUM(total_sortie) as sorties')
            )
            ->with('frontiere:id,nom')
            ->whereMonth('date_movement', $mois)
            ->whereYear('date_movement', $annee)
            ->groupBy('frontieres_id')
            ->orderByRaw('(SUM(total_entree) + SUM(total_sortie)) DESC')
            ->get()
            ->map(function($item) {
                return [
                    'frontiere' => $item->frontiere ? $item->frontiere->nom : 'Non défini',
                    'entrees' => $item->entrees ?? 0,
                    'sorties' => $item->sorties ?? 0,
                    'total' => ($item->entrees ?? 0) + ($item->sorties ?? 0)
                ];
            });
        
        return response()->json($frontieres);
    }
    
    /**
     * API : Flux par nationalité
     */
    public function apiFluxParNationalite(Request $request)
    {
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);
        
        $nationalites = FluxMigratoire::select(
                'pays_id',
                DB::raw('SUM(total_entree) as entrees'),
                DB::raw('SUM(total_sortie) as sorties'),
                DB::raw('(SUM(total_entree) + SUM(total_sortie)) as total')
            )
            ->with('pays:id,nom_fr_fr')
            ->whereMonth('date_movement', $mois)
            ->whereYear('date_movement', $annee)
            ->groupBy('pays_id')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get()
            ->map(function($item) {
                return [
                    'nationalite' => $item->pays ? $item->pays->nom_fr_fr : 'Non défini',
                    'entrees' => $item->entrees ?? 0,
                    'sorties' => $item->sorties ?? 0,
                    'total' => $item->total ?? 0
                ];
            });
        
        return response()->json($nationalites);
    }
    
    /**
     * API : Comparaison périodique
     */
    public function apiComparaison(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $previousDate = now()->copy()->subMonth();
        $lastMonth = $previousDate->month;
        $lastYear = $previousDate->year;
        
        $current = [
            'demandes' => Demande::whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count(),
            'approuvees' => Demande::where('statut_demande', 'Approuvée')->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count(),
            'flux_entrees' => FluxMigratoire::whereMonth('date_movement', $currentMonth)->whereYear('date_movement', $currentYear)->sum('total_entree') ?? 0,
            'flux_sorties' => FluxMigratoire::whereMonth('date_movement', $currentMonth)->whereYear('date_movement', $currentYear)->sum('total_sortie') ?? 0,
        ];
        
        $previous = [
            'demandes' => Demande::whereMonth('created_at', $lastMonth)->whereYear('created_at', $lastYear)->count(),
            'approuvees' => Demande::where('statut_demande', 'Approuvée')->whereMonth('created_at', $lastMonth)->whereYear('created_at', $lastYear)->count(),
            'flux_entrees' => FluxMigratoire::whereMonth('date_movement', $lastMonth)->whereYear('date_movement', $lastYear)->sum('total_entree') ?? 0,
            'flux_sorties' => FluxMigratoire::whereMonth('date_movement', $lastMonth)->whereYear('date_movement', $lastYear)->sum('total_sortie') ?? 0,
        ];
        
        return response()->json([
            'current' => $current,
            'previous' => $previous,
            'current_label' => Carbon::create($currentYear, $currentMonth)->locale('fr')->isoFormat('MMMM YYYY'),
            'previous_label' => Carbon::create($lastYear, $lastMonth)->locale('fr')->isoFormat('MMMM YYYY'),
        ]);
    }
    
    /**
     * Export PDF des statistiques
     */
    public function exportPDF(Request $request)
    {
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);
        
        // Collecter toutes les données
        $data = [
            'periode' => Carbon::create($annee, $mois)->locale('fr')->isoFormat('MMMM YYYY'),
            'stats' => [
                'demandes_total' => Demande::whereMonth('created_at', $mois)->whereYear('created_at', $annee)->count(),
                'demandes_approuvees' => Demande::where('statut_demande', 'Approuvée')->whereMonth('created_at', $mois)->whereYear('created_at', $annee)->count(),
                'flux_entrees' => FluxMigratoire::whereMonth('date_movement', $mois)->whereYear('date_movement', $annee)->sum('total_entree') ?? 0,
                'flux_sorties' => FluxMigratoire::whereMonth('date_movement', $mois)->whereYear('date_movement', $annee)->sum('total_sortie') ?? 0,
            ]
        ];
        
        $pdf = PDF::loadView('admin.statistiques.export-pdf', $data);
        return $pdf->stream('statistiques-' . $annee . '-' . $mois . '.pdf');
    }
}