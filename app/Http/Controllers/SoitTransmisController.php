<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Demande;
use App\Models\SoitTransmis;
use App\Services\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SoitTransmisController extends Controller
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        $response    = $this->api->getSoitTransmisList();
        $raw         = $response['data'] ?? (isset($response['error']) ? [] : $response);
        $soit_transmis = collect($raw)->map(fn($s) => json_decode(json_encode($s)));

        return view("admin.soittransmis.index", compact("soit_transmis"));
    }

    /**
     * Formulaire d'attribution en masse
     */
    public function attributionMasseForm()
    {
        $soitTransmis = SoitTransmis::with('demandes')
            ->orderBy('created_at', 'desc')
            ->get();

        $users = User::orderBy('nom')->orderBy('prenom')->get();

        return view('admin.soittransmis.attribution-masse', compact('soitTransmis', 'users'));
    }

    /**
     * Recherche avancée de Soit-Transmis
     */
    public function rechercheAvancee(Request $request)
    {
        $query = SoitTransmis::with(['demandes', 'createur', 'commanditaire', 'user']);

        if ($request->filled('numero')) {
            $query->where('numero', 'LIKE', '%' . $request->numero . '%');
        }
        if ($request->filled('destination')) {
            $query->where('description', 'LIKE', '%' . $request->destination . '%');
        }
        if ($request->filled('commanditaire')) {
            $query->where('commanditaire_id', $request->commanditaire);
        }
        if ($request->filled('signataire')) {
            $query->where('users_id', $request->signataire);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $soitTransmis = $query->withCount('demandes')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($st) {
                $demandesAttribuees = $st->demandes->where('attribue', 1)->count();
                $totalDemandes      = $st->demandes->count();

                if ($totalDemandes == 0) {
                    $statut      = 'vide';
                    $statutLabel = 'Aucune demande';
                    $statutColor = 'secondary';
                } elseif ($demandesAttribuees == 0) {
                    $statut      = 'non_attribue';
                    $statutLabel = 'Non attribué';
                    $statutColor = 'danger';
                } elseif ($demandesAttribuees < $totalDemandes) {
                    $statut      = 'partiel';
                    $statutLabel = "Partiel ({$demandesAttribuees}/{$totalDemandes})";
                    $statutColor = 'warning';
                } else {
                    $statut      = 'complet';
                    $statutLabel = 'Complet';
                    $statutColor = 'success';
                }

                return [
                    'id'                    => $st->id,
                    'numero_soit_transmis'  => $st->numero ?? 'Sans numéro',
                    'destination'           => $st->description ?? 'Non défini',
                    'commanditaire'         => $st->commanditaire ? $st->commanditaire->getNomPrenom() : 'Non défini',
                    'signataire_nom'        => $st->user ? $st->user->getNomPrenom() : 'Non défini',
                    'demandes_count'        => $totalDemandes,
                    'demandes_attribuees'   => $demandesAttribuees,
                    'statut_attribution'    => $statut,
                    'statut_label'          => $statutLabel,
                    'statut_color'          => $statutColor,
                    'date_creation'         => $st->created_at->format('d/m/Y'),
                ];
            });

        if ($request->filled('statut')) {
            $soitTransmis = $soitTransmis->filter(function ($st) use ($request) {
                return $st['statut_attribution'] == $request->statut;
            })->values();
        }

        return response()->json([
            'soitTransmis' => $soitTransmis,
            'total'        => $soitTransmis->count(),
        ]);
    }

    /**
     * Récupérer les demandes d'un Soit-Transmis pour attribution
     */
    public function getDemandesAttribution($id)
    {
        $soitTransmis = SoitTransmis::with(['demandes.impetrant'])->findOrFail($id);

        $demandes = $soitTransmis->demandes->map(function ($dem) {
            return [
                'id'               => $dem->id,
                'uuid'             => $dem->uuid,
                'impetrant_nom'    => $dem->impetrant->nom ?? '',
                'impetrant_prenom' => $dem->impetrant->prenom ?? '',
                'type_demande'     => $dem->type_demande,
                'attribue'         => $dem->attribue,
                'numero_document'  => $dem->numero_document,
                'date_attribution' => $dem->date_attribution ? Carbon::parse($dem->date_attribution)->format('d/m/Y') : null,
            ];
        });

        return response()->json([
            'demandes' => $demandes,
            'total'    => $demandes->count(),
        ]);
    }

    /**
     * Attribuer en masse
     */
    public function attribuerMasse(Request $request)
    {
        $attributions = $request->input('attributions');
        $count        = 0;

        DB::beginTransaction();

        try {
            foreach ($attributions as $attr) {
                $demande = Demande::find($attr['demande_id']);

                if ($demande && $demande->attribue != 1) {
                    $demande->numero_document  = $attr['numero_document'];
                    $demande->date_attribution = $attr['date_sortie'];
                    $demande->attribue         = 1;
                    $demande->attribue_par     = auth()->id();
                    $demande->save();
                    $count++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$count demande(s) attribuée(s) avec succès",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur attribution masse: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => "Erreur lors de l'attribution",
            ], 500);
        }
    }

    public function show($id)
    {
        $response = $this->api->getSoitTransmis($id);

        if (!empty($response['error'])) {
            toastr()->error("Impossible de charger ce soit-transmis");
            return back();
        }

        $stData       = $response['data'] ?? $response;
        $soit_transmis = json_decode(json_encode($stData));

        // Load demandes locally (complex view may need Eloquent relations)
        $demandes = Demande::where("soit_transmis_id", $id)->get();

        return view("admin.soittransmis.show", compact("soit_transmis", "demandes"));
    }

    public function edit($id)
    {
        $response = $this->api->getSoitTransmis($id);

        if (!empty($response['error'])) {
            toastr()->error("Impossible de charger ce soit-transmis");
            return back();
        }

        $stData       = $response['data'] ?? $response;
        $soit_transmis = json_decode(json_encode($stData));
        $users        = User::all();

        return view("admin.soittransmis.edit", compact("soit_transmis", "users"));
    }

    public function update(Request $request, $id)
    {
        try {
            $data = [
                'users_id'    => $request->users_id,
                'description' => $request->description,
            ];

            $result = $this->api->updateSoitTransmis($id, $data);

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Une erreur est survenue");
                return back()->withInput();
            }

            toastr()->success("Soit-Transmis modifié avec succès");
            return redirect()->route('soit-transmis.edit', compact('id'));
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->api->deleteSoitTransmis($id);

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Une erreur est survenue");
                return back();
            }

            toastr()->success("Soit-Transmis supprimé avec succès");
            return redirect()->route('soit-transmis.index');
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function create()
    {
        $users = User::all();
        return view("admin.soittransmis.create", compact("users"));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                "users_id" => "required",
            ]);

            $data = [
                'users_id'         => $request->users_id,
                'description'      => $request->description,
                'commanditaire_id' => $request->commanditaire_id,
                'created_by'       => auth()->user()->id,
            ];

            $result = $this->api->createSoitTransmis($data);

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Une erreur est survenue");
                return back()->withInput();
            }

            $id = $result['data']['id'] ?? $result['id'] ?? null;

            toastr()->success("Soit-Transmis ajouté avec succès");

            if ($id) {
                return redirect()->route('soit-transmis.demandes.show', compact('id'));
            }

            return redirect()->route('soit-transmis.index');
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function showDemandes(Request $request)
    {
        $soit_transmis_id = $request->id;
        $soit_transmis    = SoitTransmis::where("id", $soit_transmis_id)->withCount('demandes as demandes_count')->first();

        $demandes = Demande::where("statut_demande", "Approuvée")
            ->whereNull('soit_transmis_id')
            ->where("statut_demande", "!=", "Envoyée au contentieux")
            ->orderBy("updated_at", "desc")
            ->get();

        return view("admin.soittransmis.storedemandes", compact("demandes", "soit_transmis"));
    }

    public function storeDemandes(Request $request)
    {
        try {
            $soit_transmis_id = $request->soit_transmis_id;
            $demande_id       = $request->demande_id;

            $demande = Demande::find($demande_id);

            if ($demande->statut_demande === "Envoyée au contentieux") {
                toastr()->error("Les demandes envoyées au contentieux ne peuvent pas être ajoutées à un soit transmis.");
                return back()->withInput();
            }

            $demande->soit_transmis_id = $soit_transmis_id;
            $demande->save();

            $id = $soit_transmis_id;

            toastr()->success("Demande ajoutée avec succès");
            return redirect()->route('soit-transmis.demandes.show', compact('id'));
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function dropDemandes(Request $request)
    {
        try {
            $demande_id = $request->demande_id;
            $demande    = Demande::find($demande_id);
            $id         = $demande->soit_transmis_id;
            $demande->soit_transmis_id = null;
            $demande->save();
            toastr()->success("Demande retirée avec succès");
            return redirect()->route('soit-transmis.show', compact('id'));
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function generateNumber()
    {
        $aujourdHui = now()->format('Y-m-d');

        $dernierSoitTransmis = SoitTransmis::whereDate('created_at', $aujourdHui)
            ->orderBy('id', 'desc')
            ->first();

        $numeroProduction = null;

        if ($dernierSoitTransmis) {
            $derniersChiffres = substr($dernierSoitTransmis->numero, -3);
            $numeroProduction = (int) $derniersChiffres + 1;

            if ($numeroProduction > 999) {
                toastr()->error("Le nombre de numero de production est supérieur à 999");
                return back()->withInput();
            }
        }

        $numeroProductionFormate = sprintf('%03d', $numeroProduction);
        $annee       = substr(date('Y'), 2);
        $moisEnLettre = chr(date('n') + 64);
        $jour        = date('d');

        $numeroUnique = $annee . $moisEnLettre . $jour . $numeroProductionFormate;

        return $numeroUnique;
    }
}
