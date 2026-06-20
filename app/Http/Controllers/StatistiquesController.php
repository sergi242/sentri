<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\FluxMigratoire;
use App\Models\User;
use App\Models\SoitTransmis;
use App\Services\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class StatistiquesController extends Controller
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Dashboard principal des statistiques
     */
    public function index()
    {
        // Statistiques globales via API
        $comparaison = $this->api->getStatistiquesComparaison();
        $current     = $comparaison['current'] ?? [];

        $stats = [
            'total_demandes'       => Demande::count(),
            'demandes_mois'        => $current['demandes'] ?? Demande::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'demandes_approuvees'  => $current['approuvees'] ?? Demande::where('statut_demande', 'Approuvée')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'demandes_attente'     => $current['attente'] ?? Demande::where('statut_demande', 'LIKE', '%attente%')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'demandes_contentieux' => $current['contentieux'] ?? Demande::where('statut_demande', 'Envoyée au contentieux')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'attributions_mois'    => $current['attributions'] ?? Demande::where('attribue', 1)->whereMonth('date_attribution', now()->month)->whereYear('date_attribution', now()->year)->count(),
            'flux_entrees_mois'    => $current['flux_entrees'] ?? FluxMigratoire::whereMonth('date_movement', now()->month)->whereYear('date_movement', now()->year)->sum('total_entree'),
            'flux_sorties_mois'    => $current['flux_sorties'] ?? FluxMigratoire::whereMonth('date_movement', now()->month)->whereYear('date_movement', now()->year)->sum('total_sortie'),
        ];

        // Agents actifs via API
        $agentsResponse = $this->api->getStatistiquesDemandesParAgent([
            'mois'  => now()->month,
            'annee' => now()->year,
        ]);
        $agentsRaw = $agentsResponse['data'] ?? (isset($agentsResponse['error']) ? [] : $agentsResponse);
        $agents    = collect($agentsRaw)->map(fn($a) => (object) $a);

        // If agents from API are empty, fall back to local
        if ($agents->isEmpty()) {
            $agents = User::withCount([
                    'demandes as demandes_count' => function ($q) {
                        $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    }
                ])->having('demandes_count', '>', 0)
                ->orderBy('demandes_count', 'desc')
                ->limit(10)
                ->get();
        }

        return view('admin.statistiques.dashboard', compact('stats', 'agents'));
    }

    /**
     * API : Données graphique demandes par jour
     */
    public function apiDemandesParJour(Request $request)
    {
        $params = array_filter([
            'mois'  => $request->get('mois', now()->month),
            'annee' => $request->get('annee', now()->year),
        ]);

        $result = $this->api->getStatistiquesDemandesParJour($params);

        if (!empty($result['error'])) {
            // Fallback to local query
            $mois  = $request->get('mois', now()->month);
            $annee = $request->get('annee', now()->year);

            $data = Demande::select(
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

            return response()->json($data);
        }

        $data = $result['data'] ?? (isset($result[0]) ? $result : []);
        return response()->json($data);
    }

    /**
     * API : Données graphique demandes par type
     */
    public function apiDemandesParType(Request $request)
    {
        $params = array_filter([
            'mois'  => $request->get('mois', now()->month),
            'annee' => $request->get('annee', now()->year),
        ]);

        $result = $this->api->getStatistiquesDemandesParType($params);

        if (!empty($result['error'])) {
            $mois  = $request->get('mois', now()->month);
            $annee = $request->get('annee', now()->year);

            $data = Demande::select('type_demande', DB::raw('COUNT(*) as total'))
                ->whereMonth('created_at', $mois)
                ->whereYear('created_at', $annee)
                ->groupBy('type_demande')
                ->orderBy('total', 'desc')
                ->get();

            return response()->json($data);
        }

        $data = $result['data'] ?? (isset($result[0]) ? $result : []);
        return response()->json($data);
    }

    /**
     * API : Données graphique demandes par statut
     */
    public function apiDemandesParStatut(Request $request)
    {
        $params = array_filter([
            'mois'  => $request->get('mois', now()->month),
            'annee' => $request->get('annee', now()->year),
        ]);

        $result = $this->api->getStatistiquesDemandesParStatut($params);

        if (!empty($result['error'])) {
            $mois  = $request->get('mois', now()->month);
            $annee = $request->get('annee', now()->year);

            $data = Demande::select('statut_demande', DB::raw('COUNT(*) as total'))
                ->whereMonth('created_at', $mois)
                ->whereYear('created_at', $annee)
                ->groupBy('statut_demande')
                ->orderBy('total', 'desc')
                ->get();

            return response()->json($data);
        }

        $data = $result['data'] ?? (isset($result[0]) ? $result : []);
        return response()->json($data);
    }

    /**
     * API : Données graphique demandes par agent
     */
    public function apiDemandesParAgent(Request $request)
    {
        $params = array_filter([
            'mois'  => $request->get('mois', now()->month),
            'annee' => $request->get('annee', now()->year),
        ]);

        $result = $this->api->getStatistiquesDemandesParAgent($params);

        if (!empty($result['error'])) {
            $mois  = $request->get('mois', now()->month);
            $annee = $request->get('annee', now()->year);

            $data = Demande::select('created_by', DB::raw('COUNT(*) as total'))
                ->with('createur:id,nom,prenom')
                ->whereMonth('created_at', $mois)
                ->whereYear('created_at', $annee)
                ->groupBy('created_by')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'agent' => $item->createur ? $item->createur->getNomPrenom() : 'Non défini',
                        'total' => $item->total,
                    ];
                });

            return response()->json($data);
        }

        $data = $result['data'] ?? (isset($result[0]) ? $result : []);
        return response()->json($data);
    }

    /**
     * API : Flux migratoires par jour
     */
    public function apiFluxParJour(Request $request)
    {
        $params = array_filter([
            'mois'  => $request->get('mois', now()->month),
            'annee' => $request->get('annee', now()->year),
        ]);

        $result = $this->api->getStatistiquesFluxParJour($params);

        if (!empty($result['error'])) {
            $mois  = $request->get('mois', now()->month);
            $annee = $request->get('annee', now()->year);

            $data = FluxMigratoire::select(
                    DB::raw('DATE(date_movement) as date'),
                    DB::raw('SUM(total_entree) as entrees'),
                    DB::raw('SUM(total_sortie) as sorties')
                )
                ->whereMonth('date_movement', $mois)
                ->whereYear('date_movement', $annee)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return response()->json($data);
        }

        $data = $result['data'] ?? (isset($result[0]) ? $result : []);
        return response()->json($data);
    }

    /**
     * API : Flux par frontière
     */
    public function apiFluxParFrontiere(Request $request)
    {
        $params = array_filter([
            'mois'  => $request->get('mois', now()->month),
            'annee' => $request->get('annee', now()->year),
        ]);

        $result = $this->api->getStatistiquesFluxParFrontiere($params);

        if (!empty($result['error'])) {
            $mois  = $request->get('mois', now()->month);
            $annee = $request->get('annee', now()->year);

            $data = FluxMigratoire::select(
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
                ->map(function ($item) {
                    return [
                        'frontiere' => $item->frontiere ? $item->frontiere->nom : 'Non défini',
                        'entrees'   => $item->entrees ?? 0,
                        'sorties'   => $item->sorties ?? 0,
                        'total'     => ($item->entrees ?? 0) + ($item->sorties ?? 0),
                    ];
                });

            return response()->json($data);
        }

        $data = $result['data'] ?? (isset($result[0]) ? $result : []);
        return response()->json($data);
    }

    /**
     * API : Flux par nationalité
     */
    public function apiFluxParNationalite(Request $request)
    {
        $params = array_filter([
            'mois'  => $request->get('mois', now()->month),
            'annee' => $request->get('annee', now()->year),
        ]);

        $result = $this->api->getStatistiquesFluxParNationalite($params);

        if (!empty($result['error'])) {
            $mois  = $request->get('mois', now()->month);
            $annee = $request->get('annee', now()->year);

            $data = FluxMigratoire::select(
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
                ->map(function ($item) {
                    return [
                        'nationalite' => $item->pays ? $item->pays->nom_fr_fr : 'Non défini',
                        'entrees'     => $item->entrees ?? 0,
                        'sorties'     => $item->sorties ?? 0,
                        'total'       => $item->total ?? 0,
                    ];
                });

            return response()->json($data);
        }

        $data = $result['data'] ?? (isset($result[0]) ? $result : []);
        return response()->json($data);
    }

    /**
     * API : Comparaison périodique
     */
    public function apiComparaison(Request $request)
    {
        $result = $this->api->getStatistiquesComparaison();

        if (!empty($result['error'])) {
            // Fallback to local
            $currentMonth = now()->month;
            $currentYear  = now()->year;
            $previousDate = now()->copy()->subMonth();
            $lastMonth    = $previousDate->month;
            $lastYear     = $previousDate->year;

            $current = [
                'demandes'     => Demande::whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count(),
                'approuvees'   => Demande::where('statut_demande', 'Approuvée')->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count(),
                'flux_entrees' => FluxMigratoire::whereMonth('date_movement', $currentMonth)->whereYear('date_movement', $currentYear)->sum('total_entree') ?? 0,
                'flux_sorties' => FluxMigratoire::whereMonth('date_movement', $currentMonth)->whereYear('date_movement', $currentYear)->sum('total_sortie') ?? 0,
            ];

            $previous = [
                'demandes'     => Demande::whereMonth('created_at', $lastMonth)->whereYear('created_at', $lastYear)->count(),
                'approuvees'   => Demande::where('statut_demande', 'Approuvée')->whereMonth('created_at', $lastMonth)->whereYear('created_at', $lastYear)->count(),
                'flux_entrees' => FluxMigratoire::whereMonth('date_movement', $lastMonth)->whereYear('date_movement', $lastYear)->sum('total_entree') ?? 0,
                'flux_sorties' => FluxMigratoire::whereMonth('date_movement', $lastMonth)->whereYear('date_movement', $lastYear)->sum('total_sortie') ?? 0,
            ];

            return response()->json([
                'current'        => $current,
                'previous'       => $previous,
                'current_label'  => Carbon::create($currentYear, $currentMonth)->locale('fr')->isoFormat('MMMM YYYY'),
                'previous_label' => Carbon::create($lastYear, $lastMonth)->locale('fr')->isoFormat('MMMM YYYY'),
            ]);
        }

        return response()->json($result);
    }

    /**
     * Export PDF des statistiques
     */
    public function exportPDF(Request $request)
    {
        $mois  = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);

        // Try to get stats from API
        $comparaison = $this->api->getStatistiquesComparaison(['mois' => $mois, 'annee' => $annee]);
        $apiCurrent  = $comparaison['current'] ?? [];

        $data = [
            'periode' => Carbon::create($annee, $mois)->locale('fr')->isoFormat('MMMM YYYY'),
            'stats'   => [
                'demandes_total'   => $apiCurrent['demandes']     ?? Demande::whereMonth('created_at', $mois)->whereYear('created_at', $annee)->count(),
                'demandes_approuvees' => $apiCurrent['approuvees'] ?? Demande::where('statut_demande', 'Approuvée')->whereMonth('created_at', $mois)->whereYear('created_at', $annee)->count(),
                'flux_entrees'     => $apiCurrent['flux_entrees'] ?? FluxMigratoire::whereMonth('date_movement', $mois)->whereYear('date_movement', $annee)->sum('total_entree') ?? 0,
                'flux_sorties'     => $apiCurrent['flux_sorties'] ?? FluxMigratoire::whereMonth('date_movement', $mois)->whereYear('date_movement', $annee)->sum('total_sortie') ?? 0,
            ],
        ];

        $pdf = PDF::loadView('admin.statistiques.export-pdf', $data);
        return $pdf->stream('statistiques-' . $annee . '-' . $mois . '.pdf');
    }
}
