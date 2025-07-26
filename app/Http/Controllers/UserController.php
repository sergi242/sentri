<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Grade;
use App\Models\Demande;
use App\Models\Impetrant;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class UserController extends Controller
{
    use ThrottlesLogins;
    public function dashboard(){
        $demandes = collect(DB::select("select count(*) as nombre from demandes where year(date_demande) = year(curdate())"))->first();
        $today = collect(DB::select("select count(*) as nombre from demandes where day(date_demande) = day(curdate()) and month(date_demande)=month(curdate()) and year(date_demande)=year(curdate()) "))->first();
        $week = collect(DB::select("select count(*) as nombre from demandes where week(date_demande) =week(curdate()) and year(date_demande)=year(curdate()) "))->first();
        $month = collect(DB::select("select count(*) as nombre from demandes where month(date_demande) = month(curdate())  and year(date_demande)=year(curdate()) "))->first();
        $year = collect(DB::select("select count(*) as nombre from demandes where year(date_demande)=year(curdate()) "))->first();
        // $month = collect(DB::select("select count(*) as nombre from demandes where month(date_demande) = month(curdate())"))->first();
        $approved = collect(DB::select("select count(*) as nombre from demandes where statut_demande = ? and year(date_demande) = year(curdate())",["ApprouvÃ©e"]))->first();
        $pending = collect(DB::select("select count(*) as nombre from demandes where statut_demande = ? and year(date_demande) = year(curdate())",["En attente d'approbation"]))->first();
        $contentieux = collect(DB::select("select count(*) as nombre from demandes where statut_demande = ? and year(date_demande) = year(curdate())",["EnvoyÃ©e au contentieux"]))->first();
        $annee = collect(DB::select("select count(*) as nombre from demandes where year(date_demande) = year(curdate())"))->first();
        $impetrants = collect(DB::select("select count(*) as nombre from impetrants where year(created_at) = year(curdate())"))->first();
        $renouvellements = Demande::groupBy('impetrants_id')->havingRaw('COUNT(impetrants_id) > 1')->get();

        // attribution
        $todayAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and day(date_attribution) = day(curdate()) and month(date_attribution)=month(curdate()) and year(date_attribution)=year(curdate()) "))->first();
        $weekAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and week(date_attribution) =week(curdate()) and year(date_attribution)=year(curdate()) "))->first();
        $monthAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and month(date_attribution) = month(curdate())  and year(date_attribution)=year(curdate()) "))->first();
        $yearAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and year(date_attribution)=year(curdate()) "))->first();
        $flux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where year(date_movement) = year(curdate())"))->first();

         // flux
         $todayFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where day(date_movement)=day(curdate()) and month(date_movement)=month(curdate()) and year(date_movement) = year(curdate())"))->first();
         $weekFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where week(date_movement)=week(curdate()) and year(date_movement) = year(curdate())"))->first();
         $monthFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where month(date_movement)=month(curdate()) and year(date_movement) = year(curdate())"))->first();
         $yearFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where year(date_movement) = year(curdate())"))->first();
        // dd($yearFlux);
         return view("admin.home.dashboard",compact("annee","demandes","impetrants","renouvellements","today","month","approved","pending","contentieux","flux","year","week","todayAtt","weekAtt","monthAtt","yearAtt","todayFlux","weekFlux","monthFlux","yearFlux"));

    }

    public function home(){
        return view("admin.home.home");
    }

    public function index(){
        $users = User::all();
        return view("admin.users.index",compact("users"));
    }

    public function create(){
        $roles = Role::all();
        $grades = Grade::all();
        return view("admin.users.create",compact("roles","grades"));
    }

    public function store(Request $request){
        $request->validate([
            "nom"=>"required|string",
            "prenom"=>"required|string",
            "email"=>"email|unique:users",
            "roles_id"=>"numeric",
            "active"=>"required|numeric",
            "password"=>"string|confirmed",
            "grades_id"=>"required"
        ]);

        try {
            $user = new User;
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->grades_id = $request->grades_id;
            $user->email = $request->email;
            $user->active = $request->active;
            $user->password = Hash::make($request->password);
            $user->roles_id = $request->roles_id;
            $user->save();
            toastr()->success("Utilisateur ajoutÃ© avec succÃ¨s");
            return redirect()->route("users.index");
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id){
        $user = User::find($id);
        $roles = Role::all();
        $grades = Grade::all();
        if($user == null){
            toastr()->error("Impossible de traiter cette requÃªte");
            return back();
        }

        return view("admin.users.edit",compact("user","roles","grades"));
    }

    public function update(Request $request, $id){
        $request->validate([
            "nom"=>"required|string",
            "prenom"=>"required|string",
            "email"=>"email",
            "roles_id"=>"numeric",
            "active"=>"required|numeric",
            "grades_id"=>"required"
        ]);

        $user = User::find($id);
        if($user == null){
            toastr()->error("Impossible de traiter cette requÃªte");
            return back();
        }

        try {
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->grades_id = $request->grades_id;
            $user->active = $request->active;
            $user->roles_id = $request->roles_id;
            $user->password = Hash::make($request->password);
            $user->save();
            toastr()->success("Utilisateur modifiÃ© avec succÃ¨s");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id){
        $user = User::find($id);
        if($user == null){
            toastr()->error("Impossible de traiter cette requÃªte");
            return back();
        }

        $user->delete();
        toastr()->success("Utilisateur supprimÃ© avec succÃ¨s");
        return back();
    }

    public function username(){
        return "email";
    }

    public function authenticate(Request $request){
            $request->validate([
                "email"=>"string|required|email",
                "password"=>"required"
            ]);

            $user = User::whereEmail($request->email)->first();

            if($user == null){
                toastr()->error("Votre email est incorrect");
                return back()->withInput();
            }

            if(!Hash::check($request->password,$user->password)){
                toastr()->error("Votre mot de passe est incorrect");
                return back()->withInput();
            }

            if($user->active == 0){
                toastr()->error("Votre compte est dÃ©sactivÃ©");
                return back()->withInput();
            }

            Auth::login($user);

            toastr()->success("Connexion rÃ©ussie");
            return redirect()->route("users.home");
    }

    public function change_password_form(){
        return view("admin.users.profile");
    }

    public function change_password(Request $request){
        $request->validate([
            "oldpass"=>"required|string",
            "password"=>"required|confirmed|string"
        ]);

        $user = User::find(auth()->user()->id);
        if(!Hash::check($request->oldpass,$user->password)){
            toastr()->error("Ancien mot de passe incorrecte","Changement du mot de passe");
            return back();
        }

        try{
            $user->password = Hash::make($request->password);
            $user->save();
            toastr()->success("Mot de passe modifiÃ© avec succÃ¨s","Changement du mot de passe");
            return redirect()->route("users.dashboard");
        }catch(Exception $e){
            Log::channel("technodev")->error($e->getMessage());
            toastr()->error("Une erreur est survenue");
            return back();
        }
    }

    public function activites()
    {
        $today = now()->startOfDay();

        $users = User::withCount([
            'demandes as demandes_creees_today' => function ($query) use ($today) {
                $query->where('created_at', '>=', $today);
            },
            'soitTransmis as soit_transmis_today' => function ($query) use ($today) {
                $query->where('created_at', '>=', $today);
            },
        ])->get();

        return view('admin.users.activites', compact('users'));
    }



    public function show($id)
    {
        $user = User::withCount([
            'demandes as demandes_creees_count',
            'soitTransmis as soit_transmis_count'
        ])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function resetPassword($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->password = bcrypt('123456');
            $user->save();

            toastr()->success("Mot de passe renitialisÃ©");
            return back();
        } catch (\Throwable $th) {
            Log::channel("technodev")->error($th->getMessage());
        }
    }

    public function exportReportPdf(Request $request)
    {
        // Chope l'entÃªte
        $entete = $request->input('entete', 1);
        
        // RÃ©cupÃ©rer les dates
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfYear();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfYear();
        
        // RÃ©cupÃ©rer les autres champs
        $title = $request->input('title', 'Rapport des Utilisateurs');
        $commentaires = $request->input('commentaire', ''); 
        $selectedSection = $request->input('section', 'Toutes les sections');
        $signataireId = $request->input('signataire');
        $signataire = $signataireId ? User::find($signataireId) : null;

        // RÃ©cupÃ©rer les donnÃ©es de configuration
        $sectionsConfig = config('sections.sections');

        // Trouver la division et section correspondantes
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

        // RÃ©cupÃ©rer les utilisateurs et leurs statistiques
        $users = User::withCount([
            'demandes' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            },
            'demandes as demandes_visa_count' => function ($query) use ($startDate, $endDate) {
                $query->where('type_demande', 'Visa')->whereBetween('created_at', [$startDate, $endDate]);
            },
            'demandes as demandes_crt_count' => function ($query) use ($startDate, $endDate) {
                $query->where('type_demande', 'Carte de résident temporaire')->whereBetween('created_at', [$startDate, $endDate]);
            },
            'soitTransmis' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            },
        ])->get();

        // Filtrer les utilisateurs sans activitÃ©
        $users = $users->filter(function ($user) {
            return $user->demandes_count > 0 
                || $user->demandes_visa_count > 0 
                || $user->demandes_crt_count > 0 
                || $user->soit_transmis_count > 0;
        });

        // GÃ©nÃ©rer le contenu HTML pour le PDF
        $html = view('admin.reporting.users.user', compact('entete',
            'users', 'startDate', 'endDate', 'title', 'commentaires', 'division', 'section', 'signataire'
        ))->render();

        // GÃ©nÃ©rer le PDF
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Times');
        $html2pdf->writeHTML($html);

        // TÃ©lÃ©charger le PDF
        return $html2pdf->output('rapport_utilisateurs.pdf', 'I');
    }

    


    //Exportation des details d'activitÃ© des utilisateurs 
    public function exportUserActivitiesPdf($id)
    {
        $user = User::with(['demandes', 'soitTransmis'])
            ->findOrFail($id);

        $html = view('admin.reporting.users.activite', compact('user'))->render();

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Times');
        $html2pdf->writeHTML($html);

        return $html2pdf->output('details_utilisateur.pdf', 'I');
    }
}
