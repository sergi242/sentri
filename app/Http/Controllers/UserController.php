<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Traits\HasPerformanceOptimization;
use App\Models\User;
use App\Models\Grade;
use App\Models\Demande;
use App\Models\Impetrant;
use App\Services\ApiClient;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Models\SoitTransmis;

class UserController extends Controller
{
    use HasPerformanceOptimization;

    use ThrottlesLogins;

    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // ---------------------------------------------------------------
    // Helpers de niveau de rôle
    // ---------------------------------------------------------------

    /**
     * Retourne true si l'utilisateur connecté est SuperAdmin.
     */
    private function authIsSuperAdmin(): bool
    {
        return auth()->user()->role->lib_role === 'SuperAdmin';
    }

    /**
     * Retourne la liste des rôles autorisés selon le niveau de
     * l'utilisateur connecté.
     *
     * SuperAdmin → tous les rôles
     * Admin (et autres) → tous les rôles SAUF SuperAdmin
     */
    private function getRolesAutorisés()
    {
        if ($this->authIsSuperAdmin()) {
            return Role::all();
        }

        return Role::where('lib_role', '!=', 'SuperAdmin')->get();
    }

    // ---------------------------------------------------------------
    // Dashboard / Home
    // ---------------------------------------------------------------

    public function dashboard()
    {
        $demandes = collect(DB::select("select count(*) as nombre from demandes where year(date_demande) = year(curdate())"))->first();
        $today = collect(DB::select("select count(*) as nombre from demandes where day(date_demande) = day(curdate()) and month(date_demande)=month(curdate()) and year(date_demande)=year(curdate()) "))->first();
        $week = collect(DB::select("select count(*) as nombre from demandes where week(date_demande) =week(curdate()) and year(date_demande)=year(curdate()) "))->first();
        $month = collect(DB::select("select count(*) as nombre from demandes where month(date_demande) = month(curdate())  and year(date_demande)=year(curdate()) "))->first();
        $year = collect(DB::select("select count(*) as nombre from demandes where year(date_demande)=year(curdate()) "))->first();
        $approved = collect(DB::select("select count(*) as nombre from demandes where statut_demande = ? and year(date_demande) = year(curdate())", ["Approuvée"]))->first();
        $pending = collect(DB::select("select count(*) as nombre from demandes where statut_demande = ? and year(date_demande) = year(curdate())", ["En attente d'approbation"]))->first();
        $contentieux = collect(DB::select("select count(*) as nombre from demandes where statut_demande = ? and year(date_demande) = year(curdate())", ["Envoyée au contentieux"]))->first();
        $annee = collect(DB::select("select count(*) as nombre from demandes where year(date_demande) = year(curdate())"))->first();
        $impetrants = collect(DB::select("select count(*) as nombre from impetrants where year(created_at) = year(curdate())"))->first();
        $renouvellements = Demande::groupBy('impetrants_id')->havingRaw('COUNT(impetrants_id) > 1')->get();

        $todayAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and day(date_attribution) = day(curdate()) and month(date_attribution)=month(curdate()) and year(date_attribution)=year(curdate()) "))->first();
        $weekAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and week(date_attribution) =week(curdate()) and year(date_attribution)=year(curdate()) "))->first();
        $monthAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and month(date_attribution) = month(curdate())  and year(date_attribution)=year(curdate()) "))->first();
        $yearAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and year(date_attribution)=year(curdate()) "))->first();
        $flux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where year(date_movement) = year(curdate())"))->first();

        $todayFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where day(date_movement)=day(curdate()) and month(date_movement)=month(curdate()) and year(date_movement) = year(curdate())"))->first();
        $weekFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where week(date_movement)=week(curdate()) and year(date_movement) = year(curdate())"))->first();
        $monthFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where month(date_movement)=month(curdate()) and year(date_movement) = year(curdate())"))->first();
        $yearFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where year(date_movement) = year(curdate())"))->first();

        return view("admin.home.dashboard", compact("annee", "demandes", "impetrants", "renouvellements", "today", "month", "approved", "pending", "contentieux", "flux", "year", "week", "todayAtt", "weekAtt", "monthAtt", "yearAtt", "todayFlux", "weekFlux", "monthFlux", "yearFlux"));
    }

    public function home()
    {
        return view("admin.home.home");
    }

    // ---------------------------------------------------------------
    // CRUD Utilisateurs
    // ---------------------------------------------------------------

    public function index(Request $request)
    {
        $filters = array_filter([
            'roles_id'  => $request->roles_id,
            'grades_id' => $request->grades_id,
            'active'    => ($request->filled('active') && $request->active !== '') ? $request->active : null,
            'search'    => $request->search,
        ], fn($v) => $v !== null && $v !== '');

        $response = $this->api->getUsers($filters);
        $usersRaw = $response['data'] ?? (isset($response['error']) ? [] : $response);
        $users    = collect($usersRaw)->map(fn($u) => (object) $u);

        $roles  = $this->getRolesAutorisés();
        $grades = Grade::orderBy('grade')->get();

        return view("admin.users.index", compact("users", "roles", "grades"));
    }

    public function create()
    {
        // Un Admin ne voit pas le rôle SuperAdmin dans le select
        $roles  = $this->getRolesAutorisés();
        $grades = Grade::all();
        return view("admin.users.create", compact("roles", "grades"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "nom"       => "required|string",
            "prenom"    => "required|string",
            "email"     => "required|email",
            "roles_id"  => "required|numeric",
            "grades_id" => "required|numeric",
            "active"    => "required|numeric",
            "password"  => "required|string|confirmed|min:6",
            "photo"     => "nullable|image|mimes:jpg,jpeg,png|max:2048",
        ]);

        // ── Protection de niveau ────────────────────────────────────
        if (!$this->authIsSuperAdmin()) {
            $roleChoisi = Role::find($request->roles_id);
            if ($roleChoisi && $roleChoisi->lib_role === 'SuperAdmin') {
                toastr()->error("Vous n'êtes pas autorisé à attribuer le rôle SuperAdmin.");
                return back()->withInput();
            }
        }

        try {
            $data = [
                'nom'       => $request->nom,
                'prenom'    => $request->prenom,
                'email'     => $request->email,
                'grades_id' => $request->grades_id,
                'roles_id'  => $request->roles_id,
                'active'    => $request->active,
                'password'  => $request->password,
            ];

            $result = $this->api->createUser($data);

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Une erreur est survenue");
                return back()->withInput();
            }

            // Handle photo upload locally if provided
            if ($request->hasFile('photo') && !empty($result['id'])) {
                $photo    = $request->file('photo');
                $filename = 'user_' . $result['id'] . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/users'), $filename);
                // Update photo via API
                $this->api->updateUser($result['id'], ['photo' => $filename]);
            }

            toastr()->success("Utilisateur ajouté avec succès");
            return redirect()->route("users.index");

        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $response = $this->api->getUser($id);

        if (empty($response) || !empty($response['error'])) {
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        $userData = $response['data'] ?? $response;
        $user     = (object) $userData;

        // ── Protection de niveau ────────────────────────────────────
        $roleLibelle = is_array($userData['role'] ?? null) ? ($userData['role']['lib_role'] ?? '') : ($userData['lib_role'] ?? '');
        if (!$this->authIsSuperAdmin() && $roleLibelle === 'SuperAdmin') {
            toastr()->error("Vous n'êtes pas autorisé à modifier un SuperAdmin.");
            return back();
        }

        $roles  = $this->getRolesAutorisés();
        $grades = Grade::all();

        return view("admin.users.edit", compact("user", "roles", "grades"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "nom"       => "required|string",
            "prenom"    => "required|string",
            "email"     => "email",
            "roles_id"  => "numeric",
            "active"    => "required|numeric",
            "grades_id" => "required",
            "photo"     => "nullable|image|mimes:jpg,jpeg,png|max:2048",
        ]);

        // ── Protection de niveau ────────────────────────────────────
        if (!$this->authIsSuperAdmin()) {
            if ($request->filled('roles_id')) {
                $roleChoisi = Role::find($request->roles_id);
                if ($roleChoisi && $roleChoisi->lib_role === 'SuperAdmin') {
                    toastr()->error("Vous n'êtes pas autorisé à attribuer le rôle SuperAdmin.");
                    return back()->withInput();
                }
            }
        }

        try {
            $data = [
                'nom'       => $request->nom,
                'prenom'    => $request->prenom,
                'email'     => $request->email,
                'grades_id' => $request->grades_id,
                'active'    => $request->active,
                'roles_id'  => $request->roles_id,
            ];

            // Handle photo upload locally
            if ($request->hasFile('photo')) {
                $photo    = $request->file('photo');
                $filename = 'user_' . $id . '_' . time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/users'), $filename);
                $data['photo'] = $filename;
            }

            $result = $this->api->updateUser($id, $data);

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Erreur lors de la modification");
                return back()->withInput();
            }

            toastr()->success("Utilisateur modifié avec succès");
            return back();

        } catch (Exception $e) {
            Log::error($e->getMessage());
            toastr()->error("Erreur lors de la modification");
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        // Local check for SuperAdmin protection
        $user = User::find($id);

        if ($user === null) {
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        if (!$this->authIsSuperAdmin() && $user->role->lib_role === 'SuperAdmin') {
            toastr()->error("Vous n'êtes pas autorisé à supprimer un SuperAdmin.");
            return back();
        }

        $result = $this->api->deleteUser($id);

        if (!empty($result['error'])) {
            toastr()->error($result['message'] ?? "Une erreur est survenue");
            return back();
        }

        toastr()->success("Utilisateur supprimé avec succès");
        return back();
    }

    // ---------------------------------------------------------------
    // Authentification
    // ---------------------------------------------------------------

    // ---------------------------------------------------------------
    // Toggle actif / inactif
    // ---------------------------------------------------------------
    public function toggleActive($id)
    {
        if ($id == Auth::id()) {
            toastr()->error("Vous ne pouvez pas modifier l'état de votre propre compte.");
            return back();
        }

        // Local SuperAdmin check
        $user = User::find($id);
        if ($user && !$this->authIsSuperAdmin() && $user->role->lib_role === 'SuperAdmin') {
            toastr()->error("Vous n'êtes pas autorisé à modifier l'état d'un SuperAdmin.");
            return back();
        }

        try {
            $result = $this->api->toggleUserActive($id);

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Erreur lors de la mise à jour.");
                return back();
            }

            $userData = $result['data'] ?? $result;
            $active   = $userData['active'] ?? null;
            $nom      = ($userData['prenom'] ?? '') . ' ' . ($userData['nom'] ?? '');
            $label    = $active ? 'activé' : 'désactivé';
            toastr()->success("Le compte de {$nom} a été {$label}.");
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            toastr()->error("Erreur lors de la mise à jour.");
        }

        return back();
    }

    // ---------------------------------------------------------------
    // PDF liste agents
    // ---------------------------------------------------------------
    public function listePdf(Request $request)
    {
        $filters = array_filter([
            'roles_id'  => $request->roles_id,
            'grades_id' => $request->grades_id,
            'active'    => ($request->filled('active') && $request->active !== '') ? $request->active : null,
            'search'    => $request->search,
        ], fn($v) => $v !== null && $v !== '');

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) {
                $filters['ids'] = implode(',', $ids);
            }
        }

        $response    = $this->api->getUsers($filters);
        $usersRaw    = $response['data'] ?? (isset($response['error']) ? [] : $response);
        $users       = collect($usersRaw)->map(fn($u) => (object) $u);
        $filtreLabel = $this->buildFiltreLabel($request);
        $dateGen     = now()->setTimezone('Africa/Brazzaville')->isoFormat('D MMMM YYYY [à] HH:mm');

        $html = view('admin.users.liste_pdf', compact('users', 'filtreLabel', 'dateGen'))->render();

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Times');
        $html2pdf->writeHTML($html);

        return $html2pdf->output('liste_agents_' . date('Ymd') . '.pdf', 'I');
    }

    private function buildFiltreLabel(Request $request): string
    {
        $parts = [];
        if ($request->filled('roles_id')) {
            $role = Role::find($request->roles_id);
            if ($role) $parts[] = 'Rôle : ' . $role->lib_role;
        }
        if ($request->filled('grades_id')) {
            $grade = Grade::find($request->grades_id);
            if ($grade) $parts[] = 'Grade : ' . $grade->grade;
        }
        if ($request->filled('active') && $request->active !== '') {
            $parts[] = 'État : ' . ((int)$request->active ? 'Actifs' : 'Inactifs');
        }
        if ($request->filled('search')) {
            $parts[] = 'Recherche : "' . $request->search . '"';
        }
        return empty($parts) ? 'Tous les agents' : implode(' — ', $parts);
    }

    public function username()
    {
        return "email";
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            "email"    => "string|required|email",
            "password" => "required"
        ]);

        $user = User::whereEmail($request->email)->first();

        if ($user === null) {
            toastr()->error("Votre email est incorrect");
            return back()->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            toastr()->error("Votre mot de passe est incorrect");
            return back()->withInput();
        }

        if ($user->active == 0) {
            toastr()->error("Votre compte est désactivé");
            return back()->withInput();
        }

        Auth::login($user);
        toastr()->success("Connexion réussie");
        return redirect()->route("users.home");
    }

    public function change_password_form()
    {
        return view("admin.users.profile");
    }

    public function change_password(Request $request)
    {
        $request->validate([
            "oldpass"  => "required|string",
            "password" => "required|confirmed|string"
        ]);

        $user = User::find(auth()->user()->id);

        if (!Hash::check($request->oldpass, $user->password)) {
            toastr()->error("Ancien mot de passe incorrecte", "Changement du mot de passe");
            return back();
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();
            toastr()->success("Mot de passe modifié avec succès", "Changement du mot de passe");
            return redirect()->route("users.dashboard");
        } catch (Exception $e) {
            Log::channel("technodev")->error($e->getMessage());
            toastr()->error("Une erreur est survenue");
            return back();
        }
    }

    // ---------------------------------------------------------------
    // Activités & Reporting
    // ---------------------------------------------------------------

    public function activites()
    {
        $response = $this->api->getUsers();
        $usersRaw = $response['data'] ?? (isset($response['error']) ? [] : $response);

        // The API may return user activity counts; if not, we fall back to local
        $users = collect($usersRaw)->map(fn($u) => (object) $u);

        return view('admin.users.activites', compact('users'));
    }

    public function show($id)
    {
        $response = $this->api->getUser($id);

        if (empty($response) || !empty($response['error'])) {
            toastr()->error("Utilisateur introuvable");
            return back();
        }

        $userData = $response['data'] ?? $response;
        $user     = (object) $userData;

        return view('admin.users.show', compact('user'));
    }

    public function resetPassword($id)
    {
        try {
            $result = $this->api->resetUserPassword($id, '123456');

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Erreur lors de la réinitialisation");
                return back();
            }

            toastr()->success("Mot de passe réinitialisé");
            return back();
        } catch (\Throwable $th) {
            Log::channel("technodev")->error($th->getMessage());
            toastr()->error("Une erreur est survenue");
            return back();
        }
    }

    public function getCalendarData(Request $request)
    {
        $start = Carbon::parse($request->input('start'));
        $end   = Carbon::parse($request->input('end'));

        $demandes = Demande::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get();

        $events = [];
        foreach ($demandes as $dem) {
            $events[] = [
                'title'           => $dem->total . ' demande' . ($dem->total > 1 ? 's' : ''),
                'start'           => $dem->date,
                'backgroundColor' => $this->getColorByCount($dem->total),
                'borderColor'     => '#1a3a6b',
                'textColor'       => '#ffffff',
            ];
        }

        return response()->json($events);
    }

    public function getDemandesJour($date)
    {
        $demandes = Demande::with(['impetrant', 'createur'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($dem) {
                return [
                    'uuid'             => $dem->uuid,
                    'impetrant_nom'    => $dem->impetrant->nom ?? '',
                    'impetrant_prenom' => $dem->impetrant->prenom ?? '',
                    'type_demande'     => $dem->type_demande,
                    'statut_demande'   => $dem->statut_demande,
                    'agent_nom'        => $dem->createur ? $dem->createur->getNomPrenom() : 'Non défini',
                    'heure'            => Carbon::parse($dem->created_at)->format('H:i'),
                ];
            });

        return response()->json([
            'demandes'     => $demandes,
            'total'        => $demandes->count(),
            'agents_count' => $demandes->pluck('agent_nom')->unique()->count(),
        ]);
    }

    private function getColorByCount($count)
    {
        if ($count >= 20) return '#c0392b';
        if ($count >= 10) return '#e67e22';
        if ($count >= 5)  return '#2980b9';
        return '#16a085';
    }

    public function exportReportPdf(Request $request)
    {
        $entete          = $request->input('entete', 1);
        $startDate       = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfYear();
        $endDate         = $request->input('end_date')   ? Carbon::parse($request->input('end_date'))   : now()->endOfYear();
        $title           = $request->input('title', 'Rapport des Utilisateurs');
        $commentaires    = $request->input('commentaire', '');
        $selectedSection = $request->input('section', 'Toutes les sections');
        $signataireId    = $request->input('signataire');

        // Fetch signataire via API
        $signataire = null;
        if ($signataireId) {
            $sigRes     = $this->api->getUser($signataireId);
            $signataire = !empty($sigRes['error']) ? null : (object) ($sigRes['data'] ?? $sigRes);
        }

        $sectionsConfig = config('sections.sections');
        $division = null;
        $section  = null;

        foreach ($sectionsConfig as $div) {
            foreach ($div['sections'] as $sect) {
                if ($sect['name'] == $selectedSection) {
                    $division = $div['division'];
                    $section  = $sect['name'];
                    break 2;
                }
            }
        }

        // Fetch users via API
        $response = $this->api->getUsers([
            'start_date' => $startDate->toDateString(),
            'end_date'   => $endDate->toDateString(),
            'with_stats' => 1,
        ]);
        $usersRaw = $response['data'] ?? (isset($response['error']) ? [] : $response);
        $users    = collect($usersRaw)
            ->map(fn($u) => (object) $u)
            ->filter(function ($user) {
                return ($user->demandes_count ?? 0) > 0
                    || ($user->demandes_visa_count ?? 0) > 0
                    || ($user->demandes_crt_count ?? 0) > 0
                    || ($user->soit_transmis_count ?? 0) > 0;
            });

        $html = view('admin.reporting.users.user', compact(
            'entete', 'users', 'startDate', 'endDate',
            'title', 'commentaires', 'division', 'section', 'signataire'
        ))->render();

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Times');
        $html2pdf->writeHTML($html);

        return $html2pdf->output('rapport_utilisateurs.pdf', 'I');
    }

    public function exportUserActivitiesPdf($id, Request $request)
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfYear();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $user = User::with([
            'demandes'        => fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]),
            'soitTransmis'    => fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]),
            'fluxMigratoires' => fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]),
        ])->findOrFail($id);

        $user->dossiers_attribues_count = Demande::where('attribue', 1)
            ->where('attribue_par', $id)
            ->whereBetween('date_attribution', [$startDate, $endDate])
            ->count();

        $user->stats_par_statut = Demande::where('created_by', $id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('statut_demande, count(*) as total')
            ->groupBy('statut_demande')
            ->pluck('total', 'statut_demande');

        if ($request->boolean('include_detail')) {
            $user->demandes_detail = Demande::with('impetrant')
                ->where('created_by', $id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $user->demandes_detail = collect();
        }

        $includeDetail = $request->boolean('include_detail');
        $analysis      = null;
        $variations    = null;

        if ($request->boolean('include_analysis')) {
            $nbJours       = $startDate->diffInDays($endDate) + 1;
            $previousStart = $startDate->copy()->subDays($nbJours);
            $previousEnd   = $startDate->copy()->subDay();

            $currentStats = [
                'demandes'      => Demande::where('created_by', $id)->whereBetween('created_at', [$startDate, $endDate])->count(),
                'approuvees'    => Demande::where('created_by', $id)->where('statut_demande', 'Approuvée')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'contentieux'   => Demande::where('created_by', $id)->where('statut_demande', 'LIKE', '%contentieux%')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'attente'       => Demande::where('created_by', $id)->where('statut_demande', "En attente d'approbation")->whereBetween('created_at', [$startDate, $endDate])->count(),
                'soit_transmis' => SoitTransmis::where('created_by', $id)->whereBetween('created_at', [$startDate, $endDate])->count(),
            ];

            $previousStats = [
                'demandes'      => Demande::where('created_by', $id)->whereBetween('created_at', [$previousStart, $previousEnd])->count(),
                'approuvees'    => Demande::where('created_by', $id)->where('statut_demande', 'Approuvée')->whereBetween('created_at', [$previousStart, $previousEnd])->count(),
                'contentieux'   => Demande::where('created_by', $id)->where('statut_demande', 'LIKE', '%contentieux%')->whereBetween('created_at', [$previousStart, $previousEnd])->count(),
                'attente'       => Demande::where('created_by', $id)->where('statut_demande', "En attente d'approbation")->whereBetween('created_at', [$previousStart, $previousEnd])->count(),
                'soit_transmis' => SoitTransmis::where('created_by', $id)->whereBetween('created_at', [$previousStart, $previousEnd])->count(),
            ];

            $variations = [];
            foreach ($currentStats as $key => $value) {
                $prev = $previousStats[$key];
                if ($prev > 0) {
                    $pct = round((($value - $prev) / $prev) * 100);
                    $variations[$key] = [
                        'current'  => $value,
                        'previous' => $prev,
                        'diff'     => $value - $prev,
                        'pct'      => abs($pct),
                        'trend'    => $pct > 0 ? 'hausse' : ($pct < 0 ? 'baisse' : 'stable'),
                    ];
                } else {
                    $variations[$key] = [
                        'current'  => $value,
                        'previous' => 0,
                        'diff'     => $value,
                        'pct'      => $value > 0 ? 100 : 0,
                        'trend'    => $value > 0 ? 'hausse' : 'stable',
                    ];
                }
            }

            $analysis = $this->generateAnalysisText($variations, $user, $currentStats);
        }

        $html = view('admin.reporting.users.activite', compact(
            'user', 'startDate', 'endDate', 'includeDetail', 'analysis', 'variations'
        ))->render();

        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Times');
        $html2pdf->writeHTML($html);

        return $html2pdf->output(
            'rapport_' . $user->nom . '_' . $startDate->format('d-m-Y') . '_au_' . $endDate->format('d-m-Y') . '.pdf',
            'I'
        );
    }

    private function generateAnalysisText($variations, $user, $currentStats)
    {
        $texts = [];

        $nomPrenom = method_exists($user, 'getNomPrenom') ? $user->getNomPrenom() : (($user->prenom ?? '') . ' ' . ($user->nom ?? ''));

        if ($variations['demandes']['trend'] == 'hausse') {
            $texts[] = "L'agent {$nomPrenom} a enregistré une hausse significative de {$variations['demandes']['pct']}% du volume de demandes traitées par rapport à la période précédente ({$variations['demandes']['diff']} demandes supplémentaires). Cette progression témoigne d'une productivité accrue.";
        } elseif ($variations['demandes']['trend'] == 'baisse') {
            $texts[] = "On constate une baisse de {$variations['demandes']['pct']}% du nombre de demandes créées par rapport à la période précédente ({$variations['demandes']['diff']} demandes en moins). Cette diminution peut s'expliquer par une réaffectation temporaire ou une période d'activité réduite.";
        } else {
            $texts[] = "Le volume de demandes est resté stable par rapport à la période précédente, témoignant d'une charge de travail constante.";
        }

        if ($currentStats['demandes'] > 0 && $currentStats['approuvees'] > 0) {
            $tauxApprobation = round(($currentStats['approuvees'] / $currentStats['demandes']) * 100);
            if ($tauxApprobation >= 80) {
                $texts[] = "Le taux d'approbation de {$tauxApprobation}% reflète une excellente qualité de traitement des dossiers.";
            } elseif ($tauxApprobation >= 60) {
                $texts[] = "Le taux d'approbation de {$tauxApprobation}% est satisfaisant et dans la moyenne institutionnelle.";
            } else {
                $texts[] = "Le taux d'approbation de {$tauxApprobation}% suggère la nécessité d'un renforcement de l'accompagnement ou de la formation sur les critères d'éligibilité.";
            }
        }

        if ($variations['contentieux']['trend'] == 'hausse' && $currentStats['contentieux'] > 0) {
            $texts[] = "⚠️ On note une augmentation des dossiers envoyés au contentieux (+{$variations['contentieux']['pct']}%), ce qui nécessite une vigilance accrue sur les critères de recevabilité.";
        } elseif ($variations['contentieux']['trend'] == 'baisse' && $variations['contentieux']['previous'] > 0) {
            $texts[] = "✓ La diminution des dossiers contentieux (-{$variations['contentieux']['pct']}%) témoigne d'une amélioration de la qualité du travail en amont.";
        }

        if ($currentStats['attente'] > 0 && $variations['attente']['trend'] == 'hausse') {
            $texts[] = "Le nombre de dossiers en attente a augmenté de {$variations['attente']['pct']}%, ce qui peut indiquer un engorgement temporaire nécessitant un suivi rapproché.";
        }

        if ($currentStats['soit_transmis'] > 0 && $variations['soit_transmis']['trend'] == 'hausse') {
            $texts[] = "La production de soit-transmis a connu une hausse de {$variations['soit_transmis']['pct']}%, indiquant une activité soutenue dans la transmission des dossiers aux autorités compétentes.";
        }

        $performanceGlobale = $variations['demandes']['trend'] == 'hausse' ? 'en progression' :
                             ($variations['demandes']['trend'] == 'baisse' ? 'en régression' : 'stable');

        $qualite = ($currentStats['demandes'] > 0 && ($currentStats['approuvees'] / $currentStats['demandes']) >= 0.7) ? 'positifs' : 'à surveiller';

        $texts[] = "Conclusion : L'agent maintient un niveau de performance {$performanceGlobale}. Les indicateurs sont globalement {$qualite}.";

        return implode("\n\n", $texts);
    }
}
