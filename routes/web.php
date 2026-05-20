<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GrapheController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\QuartierController;
use App\Http\Controllers\EmployeurController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ArrondissementController;
use App\Http\Controllers\FluxMigratoireController;
use App\Http\Controllers\FrontiereCongoController;
use App\Http\Controllers\MotifContentieuxController;
use App\Http\Controllers\CategorieSocioProfessionnelleController;
use App\Http\Controllers\ImpetrantController;
use App\Http\Controllers\ListeAlertController;
use App\Http\Controllers\SoitTransmisController;
use App\Http\Controllers\DemandeMergeController;
use App\Http\Controllers\TestIdentityController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\StatistiquesController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\CertificatHebergementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROUTES PUBLIQUES ---
Route::get('stats-migratoire', [DemandeController::class, 'testprint'])
    ->name('flux.stats.etat')
    ->middleware('can:flux.view');

Route::post('authenticate', [UserController::class, 'authenticate'])
    ->name('users.authenticate');

Auth::routes();

// LICENCE
Route::get('/license/locked', function () {
    return view('license.locked', ['reason' => session('reason', 'Licence invalide ou expirée')]);
})->name('license.locked');

// ============================================================
// API PASSEPORT (auth, hors groupe principal pour eviter conflits)
// ============================================================
Route::middleware(['auth', 'license'])->group(function () {

    // Mapping nationalites ISO -> id pays
    Route::get('/api/passport/pays', function () {
        $pays = \App\Models\Pays::select('id', 'code_iso', 'nationalite', 'lib_pays')
            ->whereNotNull('code_iso')
            ->get()
            ->keyBy('code_iso');
        return response()->json($pays);
    });

    // Verification numero passeport existant
    Route::get('/api/passport/check/{numero}', function ($numero) {
        $doc = \DB::table('document_demandes as dd')
            ->join('demandes as d', 'd.id', '=', 'dd.demandes_id')
            ->join('impetrants as i', 'i.id', '=', 'd.impetrants_id')
            ->leftJoin('pays as p', 'p.id', '=', 'i.nationalites_id')
            ->whereNull('dd.deleted_at')
            ->whereNull('d.deleted_at')
            ->where('dd.numero_document', $numero)
            ->select(
                'd.id as demande_id',
                'd.uuid',
                'd.statut_demande',
                'd.type_demande',
                'd.date_demande',
                'd.photo',
                'dd.numero_document',
                'dd.date_emission',
                'dd.date_expiration',
                'dd.type_document',
                'i.nom',
                'i.prenom',
                'i.date_naissance',
                'i.sexe',
                'p.lib_pays as nationalite'
            )
            ->orderBy('d.id', 'desc')
            ->first();

        if (!$doc) {
            return response()->json(['found' => false]);
        }
        return response()->json(['found' => true, 'demande' => $doc]);
    });
});

// ===================================================================
// GROUPE PRINCIPAL -- AUTHENTIFIE
// ===================================================================
Route::middleware(['auth', 'license'])->group(function () {

    // --- ACCUEIL / DASHBOARD ---
    Route::get('/', [UserController::class, 'home'])->name('users.home');
    Route::get('dashboard', [UserController::class, 'dashboard'])
        ->name('users.dashboard')
        ->middleware('can:dashboard.view');

    // --- TEST APPROBATION ---
    Route::post('/approuver-demande/{id}', function ($id) {
        try {
            $demande = \App\Models\Demande::findOrFail($id);
            $demande->statut_demande = 'Approuvee';
            $demande->approved_by    = auth()->id();
            $demande->approval_date  = now();
            $demande->save();
            return redirect()->back()->with('success', 'Demande approuvee !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    })->name('approuver.simple');

    // --- VERIFICATION PERMISSIONS ---
    Route::get('/admin/check-permissions', function () {
        $existing = \DB::table('fonctionnalites')->pluck('unique_key_string')->toArray();
        $needed   = [
            'demandes.menu', 'demandes.view-all', 'demandes.view-pending', 'demandes.view-approved',
            'demandes.view-contentieux', 'demandes.create', 'demandes.edit', 'demandes.delete',
            'demandes.approve', 'demandes.reset-document', 'demandes.edit-document-number',
            'users.menu', 'users.view', 'users.create', 'users.edit', 'users.view-activities',
            'users.export-activity-report', 'monitor.view', 'monitor.feed', 'contentieux.create-global',
        ];
        $missing = array_diff($needed, $existing);
        return view('admin.check-permissions', compact('existing', 'needed', 'missing'));
    })->name('check.permissions');

    // ============================================================
    // CERTIFICATS D'HEBERGEMENT
    // ============================================================
    Route::prefix('certificats-hebergement')->name('certificats-hebergement.')->group(function () {

        // Routes statiques en premier (avant {id})
        Route::get('relations',             [CertificatHebergementController::class, 'relations'])            ->name('relations');
        Route::get('creer',                 [CertificatHebergementController::class, 'create'])               ->name('create');
        Route::get('statistiques/index',    [CertificatHebergementController::class, 'statistiques'])         ->name('statistiques');
        Route::get('statistiques-avancees', [CertificatHebergementController::class, 'statistiquesAvancees']) ->name('statistiques-avancees');

        // API AJAX (avant {id})
        Route::get('api/code',              [CertificatHebergementController::class, 'apiRechercherParCode'])   ->name('api.code');
        Route::get('api/hebergeur',         [CertificatHebergementController::class, 'apiRechercherHebergeur']) ->name('api.hebergeur');
        Route::get('api/heberge',           [CertificatHebergementController::class, 'apiRechercherHeberge'])   ->name('api.heberge');
        Route::get('api/certificat',        [CertificatHebergementController::class, 'apiVerifierCertificat'])  ->name('api.certificat');

        // Index & store
        Route::get('/',  [CertificatHebergementController::class, 'index'])->name('index');
        Route::post('/', [CertificatHebergementController::class, 'store'])->name('store');

        // Routes avec {id} en dernier
        Route::get('{id}',         [CertificatHebergementController::class, 'show'])   ->name('show');
        Route::get('{id}/imprimer',[CertificatHebergementController::class, 'imprimer'])->name('imprimer');
        Route::post('{id}/valider',[CertificatHebergementController::class, 'valider'])->name('valider');
        Route::post('{id}/rejeter',[CertificatHebergementController::class, 'rejeter'])->name('rejeter');
        Route::delete('{id}',      [CertificatHebergementController::class, 'destroy'])->name('destroy');
    });

    // ============================================================
    // WATCHLIST
    // ============================================================
    Route::resource('watchlist', WatchlistController::class);
    Route::get('/admin/watchlist/{id}/details', [WatchlistController::class, 'getDetailsAjax']);
    Route::get('/watchlist/check/{id}',         [WatchlistController::class, 'check']);

    // ============================================================
    // UTILISATEURS
    // ============================================================
    Route::prefix('users')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/',                   'index')               ->name('users.index')        ->middleware('can:users.view');
            Route::get('statistics',          'activites')           ->name('users.activites')     ->middleware('can:users.view');
            Route::get('create',              'create')              ->name('users.create')        ->middleware('can:users.create');
            Route::get('change-password',     'change_password_form')->name('users.password.form');
            Route::get('report',              'exportReportPdf')     ->name('users.report.pdf');
            Route::post('store',              'store')               ->name('users.store')         ->middleware('can:users.create');
            Route::put('change-password',     'change_password')     ->name('users.changepassword');
            Route::get('{id}/show',           'show')                ->name('users.show')          ->middleware('can:users.view');
            Route::get('{id}/edit',           'edit')                ->name('users.edit')          ->middleware('can:users.edit');
            Route::put('{id}/update',         'update')              ->name('users.update')        ->middleware('can:users.edit');
            Route::delete('{id}/destroy',     'destroy')             ->name('users.destroy')       ->middleware('can:users.destroy');
            Route::put('{id}/reset-password', 'resetPassword')       ->name('users.reset-password');
            Route::get('{id}/activities/pdf', 'exportUserActivitiesPdf')->name('users.activities.pdf')->middleware('can:users.view');
        });
    });

    // ============================================================
    // ROLES
    // ============================================================
    Route::prefix('roles')->group(function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('/',               'index') ->name('role.index')   ->middleware('can:roles.view');
            Route::get('create',          'create')->name('role.create')  ->middleware('can:roles.create');
            Route::post('store',          'store') ->name('role.store')   ->middleware('can:roles.create');
            Route::get('{id}/edit',       'edit')  ->name('role.edit')    ->middleware('can:roles.edit');
            Route::put('{id}/update',     'update')->name('role.update')  ->middleware('can:roles.edit');
            Route::delete('{id}/destroy', 'destroy')->name('role.destroy')->middleware('can:roles.destroy');
        });
    });

    // ============================================================
    // GRADES
    // ============================================================
    Route::prefix('grades')->group(function () {
        Route::controller(GradeController::class)->group(function () {
            Route::get('/',               'index') ->name('grade.index');
            Route::get('create',          'create')->name('grade.create');
            Route::post('store',          'store') ->name('grade.store');
            Route::get('{id}/edit',       'edit')  ->name('grade.edit');
            Route::put('{id}/update',     'update')->name('grade.update');
            Route::delete('{id}/destroy', 'destroy')->name('grade.destroy');
        });
    });

    // ============================================================
    // FLUX MIGRATOIRES
    // ============================================================
    Route::prefix('flux')->group(function () {
        Route::controller(FluxMigratoireController::class)->group(function () {
            Route::get('/',                      'index')                  ->name('flux.index')        ->middleware('can:flux.view');
            Route::get('stats-migration-entre',  'fluxmigratoiresatentre') ->name('flux.stats.entre');
            Route::get('stats-migration-sortie', 'fluxmigratoiresatsortie')->name('flux.stats.sortie');
            Route::get('create',                 'create')                 ->name('flux.create')       ->middleware('can:flux.create');
            Route::post('store',                 'store')                  ->name('flux.store')        ->middleware('can:flux.create');
            Route::get('{id}/edit',              'edit')                   ->name('flux.edit')         ->middleware('can:flux.edit');
            Route::put('{id}/update',            'update')                 ->name('flux.update')       ->middleware('can:flux.edit');
            Route::delete('{id}/destroy',        'destroy')                ->name('flux.destroy')      ->middleware('can:flux.destroy');
        });
    });

    Route::get('flux/get-frontieres-by-departement/{id}',
        [FluxMigratoireController::class, 'getFrontieresByDepartement'])
        ->name('flux.getFrontieresByDepartement');

    // ============================================================
    // DEMANDES
    // ============================================================
    Route::prefix('demandes')->group(function () {
        Route::controller(DemandeController::class)->group(function () {

            // -- Listes -------------------------------------------
            Route::get('/',                       'index')               ->name('demandes.index')             ->middleware('can:demandes.view.all');
            Route::get('approuvees',              'approuvees')          ->name('demandes.approuvees')        ->middleware('can:demandes.view.approved');
            Route::get('contentieux',             'contentieux')         ->name('demandes.contentieux')       ->middleware('can:demandes.view.contentieux');
            Route::get('attentes',                'attentes')            ->name('demandes.attentes')          ->middleware('can:demandes.view.pending');
            Route::get('attribuees',              'attribuees')          ->name('demandes.attribuees')        ->middleware('can:demandes.view.attribue');
            Route::get('attente-attributions',    'attente_attribution') ->name('demandes.attente.attributions');
            Route::get('impression-cartes',       'impressioncartes')    ->name('demandes.impressioncartes')  ->middleware('can:demandes.print.cards');
            Route::get('retirees',                'retirees')            ->name('demandes.retirees')          ->middleware('can:demandes.destroy');
            Route::get('proche-expiration',       'procheExpiration')    ->name('demandes.proche.expiration') ->middleware('can:demandes.view.all');
            Route::get('renouvellements-bloques', 'renouvellementsBloques')->name('demandes.renouvellements-bloques');

            // -- Stats --------------------------------------------
            Route::get('stats-demandes',          'demandestats')        ->name('demandes.demandestats');
            Route::get('stats-attributions',      'demandesattribuees')  ->name('demandes.stats.attributions');

            // -- Creation -----------------------------------------
            Route::get('create',       'create')    ->name('demandes.create')      ->middleware('can:demandes.create');
            Route::get('newdocument',  'newdocument')->name('demandes.newdocument') ->middleware('can:demandes.create');
            Route::get('new-visa',     'newvisa')    ->name('demandes.newvisa')     ->middleware('can:demandes.create');
            Route::get('new-crt',      'newcrt')     ->name('demandes.newcrt')      ->middleware('can:demandes.create');
            Route::get('new-diplomate','diplomate')  ->name('demandes.newdiplomate')->middleware('can:demandes.create');
            Route::get('renouvellement','renouvellement')->name('demandes.renouvellement')->middleware('can:demandes.renew');

            // -- Recherche / outils -------------------------------
            Route::get('recherche',              'search')              ->name('demandes.search.form');
            Route::get('search-for-contentieux', 'searchForContentieux')->name('demandes.search.contentieux');
            Route::get('export-to-json',         'exportToJson')        ->name('demandes.exporttojson')->middleware('can:demandes.destroy');

            // -- compareSimilarity --------------------------------
            // DEUX noms pour la meme methode :
            //   'demandes.compare.similarity' (kebab)   -- utilise dans certaines vues
            //   'demandes.compareSimilarity'  (camelCase) -- utilise dans similarity.blade.php ligne 314
            Route::get('compare-similarity',     'compareSimilarity')->name('demandes.compare.similarity');
            Route::get('compare-similarity-v2',  'compareSimilarity')->name('demandes.compareSimilarity');

            // -- Store / Actions globales -------------------------
            Route::post('store',                  'store')               ->name('demandes.store')             ->middleware('can:demandes.create');
            Route::post('searchdocument',         'searchdocument')      ->name('demandes.searchdocument');
            Route::post('store-contentieux-global','storeContentieuxGlobal')->name('demandes.store.contentieux.global');
            Route::post('check-quittance',        'checkQuittance')      ->name('demandes.checkQuittance');
            Route::post('precheck',               'precheck')            ->name('demandes.precheck');
            Route::post('merge',                  [DemandeMergeController::class, 'merge'])->name('demandes.merge');

            // -- Actions sur une demande {id} ---------------------
            Route::get('{id}/show',                'show')               ->name('demandes.show')              ->middleware('can:demandes.details');
            Route::get('{id}/edit',                'edit')               ->name('demandes.edit')              ->middleware('can:demandes.edit');
            Route::get('{id}/fiche',               'fiche')              ->name('demandes.fiche')             ->middleware('can:demandes.print');
            Route::get('{id}/renouveler',          'renouveler')         ->name('demandes.renouveler')        ->middleware('can:demandes.renew');
            Route::get('{id}/create/contentieux',  'createcontentieux')  ->name('demandes.create.contentieux')->middleware('can:demandes.contentieux.add');
            Route::get('{id}/information/document','remplirformation')   ->name('demandes.remplirformation')  ->middleware('can:demandes.grant');
            Route::get('{id}/similarites',         'similarities')       ->name('demandes.similarities')      ->middleware('can:demandes.view.similar');
            Route::get('{id}/take-photo',          'takephoto')          ->name('demandes.takephoto');
            Route::get('{id}/photo-camera',        'takePhotoCamera')    ->name('takePhotoCamera.index');
            Route::get('{id}/generate-pdf',        'generatePdf')        ->name('demandes.generate-pdf');

            Route::put('{id}/update',              'update')             ->name('demandes.update')            ->middleware('can:demandes.edit');
            Route::put('{id}/changestate',         'changestate')        ->name('demandes.changestate');
            Route::put('{id}/store/remplirformation','storeremplirformation')->name('demandes.storeremplirformation')->middleware('can:demandes.grant');
            Route::put('{id}/reset-document',      'resetDocument')      ->name('demandes.reset-document')    ->middleware('can:demandes.grant');

            Route::post('{id}/photo-store',        'storePhoto')         ->name('storePhoto.index');
            Route::post('{id}/renew-store',        'renewstore')         ->name('demandes.renewstore')        ->middleware('can:demandes.create');
            Route::post('{id}/store/contentieux',  'storecontentieux')   ->name('demandes.storecontentieux')  ->middleware('can:demandes.contentieux.add');
            Route::post('{id}/renouveler-fiche',   'renouvelerFiche')    ->name('demandes.renouveler.fiche')  ->middleware('can:demandes.renew');
            Route::post('{id}/restaurer',          'restaurer')          ->name('demandes.restaurer')         ->middleware('can:demandes.destroy');

            Route::post('{demande}/similarites/{similaire}/reject', 'rejectSimilarity')
                ->name('demandes.similarities.reject');
            Route::get('{id}/similarities/rejected', 'rejectedSimilarities')
                ->name('demandes.similarities.rejected');

            Route::delete('{id}/destroy',          'destroy')            ->name('demandes.destroy')           ->middleware('can:demandes.destroy');
            Route::delete('{id}/force-delete',     'forceDelete')        ->name('demandes.forceDelete');
            Route::delete('{demande}/similarities/{rejection}/restore', 'restoreSimilarity')
                ->name('demandes.similarities.restore');
        });

        // -- Tests identite ---------------------------------------
        Route::prefix('tests')->group(function () {
            Route::get('/identity',                      [TestIdentityController::class, 'index'])    ->name('tests.identity');
            Route::post('/identity/duplicate/{demande}', [TestIdentityController::class, 'duplicate'])->name('tests.identity.duplicate');
        });
    });

    // ============================================================
    // PARAMETRES ET CONFIGURATIONS
    // ============================================================
    Route::middleware('can:demandes.create')->group(function () {

        Route::prefix('categorie-socioprofessionnelle')->group(function () {
            Route::controller(CategorieSocioProfessionnelleController::class)->group(function () {
                Route::get('/',               'index') ->name('categorie.socio.index');
                Route::get('create',          'create')->name('categorie.socio.create');
                Route::post('store',          'store') ->name('categorie.socio.store');
                Route::get('{id}/edit',       'edit')  ->name('categorie.socio.edit');
                Route::put('{id}/update',     'update')->name('categorie.socio.update');
                Route::delete('{id}/destroy', 'destroy')->name('categorie.socio.destroy');
            });
        });

        Route::prefix('pays')->group(function () {
            Route::controller(PaysController::class)->group(function () {
                Route::get('/',               'index') ->name('pays.index');
                Route::get('create',          'create')->name('pays.create');
                Route::post('store',          'store') ->name('pays.store');
                Route::get('{id}/edit',       'edit')  ->name('pays.edit');
                Route::put('{id}/update',     'update')->name('pays.update');
                Route::delete('{id}/destroy', 'destroy')->name('pays.destroy');
            });
        });

        Route::prefix('frontieres')->group(function () {
            Route::controller(FrontiereCongoController::class)->group(function () {
                Route::get('/',               'index') ->name('frontieres.index');
                Route::get('create',          'create')->name('frontieres.create');
                Route::post('store',          'store') ->name('frontieres.store');
                Route::get('{id}/edit',       'edit')  ->name('frontieres.edit');
                Route::put('{id}/update',     'update')->name('frontieres.update');
                Route::delete('{id}/destroy', 'destroy')->name('frontieres.destroy');
            });
        });

        Route::prefix('departements')->group(function () {
            Route::controller(DepartementController::class)->group(function () {
                Route::get('/',                    'index')          ->name('departements.index');
                Route::get('{id}/edit',            'edit')           ->name('departements.edit');
                Route::get('{id}/arrondissements', 'arrondissements')->name('departements.arrondissements');
                Route::post('store',               'store')          ->name('departements.store');
                Route::put('{id}/update',          'update')         ->name('departements.update');
                Route::delete('{id}/destroy',      'destroy')        ->name('departements.destroy');
            });
        });

        Route::prefix('arrondissements')->group(function () {
            Route::controller(ArrondissementController::class)->group(function () {
                Route::get('/',               'index')    ->name('arrondissements.index');
                Route::get('{id}/edit',       'edit')     ->name('arrondissements.edit');
                Route::get('{id}/quartiers',  'quartiers')->name('arrondissements.quartiers');
                Route::post('store',          'store')    ->name('arrondissements.store');
                Route::put('{id}/update',     'update')   ->name('arrondissements.update');
                Route::delete('{id}/destroy', 'destroy')  ->name('arrondissements.destroy');
            });
        });

        Route::prefix('quartiers')->group(function () {
            Route::controller(QuartierController::class)->group(function () {
                Route::get('/',               'index') ->name('quartiers.index');
                Route::get('{id}/edit',       'edit')  ->name('quartiers.edit');
                Route::post('store',          'store') ->name('quartiers.store');
                Route::put('{id}/update',     'update')->name('quartiers.update');
                Route::delete('{id}/destroy', 'destroy')->name('quartiers.destroy');
            });
        });

        Route::prefix('employeurs')->group(function () {
            Route::controller(EmployeurController::class)->group(function () {
                Route::get('/',               'index') ->name('employeur.index');
                Route::get('{id}/edit',       'edit')  ->name('employeur.edit');
                Route::post('store',          'store') ->name('employeur.store');
                Route::put('{id}/update',     'update')->name('employeur.update');
                Route::delete('{id}/destroy', 'destroy')->name('employeur.destroy');
            });
        });

        Route::prefix('motifs-contentieux')->group(function () {
            Route::controller(MotifContentieuxController::class)->group(function () {
                Route::get('/',               'index') ->name('motifs.contentieux.index');
                Route::get('{id}/edit',       'edit')  ->name('motifs.contentieux.edit');
                Route::post('store',          'store') ->name('motifs.contentieux.store');
                Route::put('{id}/update',     'update')->name('motifs.contentieux.update'); // corrige: upadte -> update
                Route::delete('{id}/destroy', 'destroy')->name('motifs.contentieux.destroy');
            });
        });
    });

    // ============================================================
    // GRAPHES ET STATISTIQUES
    // ============================================================
    Route::prefix('graphes')->group(function () {
        Route::controller(GrapheController::class)->group(function () {
            Route::get('flux-demandes',   'demande')    ->name('graphes.flux-demande');
            Route::get('demandes',        'demandes')   ->name('graphes.demandes');
            Route::get('flux-migratoire', 'flux')       ->name('graphes.flux-migratoire');
            Route::get('migratoires',     'migratoires')->name('graphes.migratoires');
        });
    });

    // ============================================================
    // RECHERCHE
    // ============================================================
    Route::prefix('recherche')->group(function () {
        Route::controller(SearchController::class)->group(function () {
            Route::get('impetrant',       'searchByImpetrant')->name('recherche.impetrant');
            Route::get('type-document',   'searchByDocument') ->name('recherche.type.docs');
            Route::get('demandes',        'demandes')         ->name('recherche.demandes');
            Route::get('flux-migratoire', 'flux')             ->name('recherche.flux-migratoire');
            Route::get('migratoires',     'migratoires')      ->name('recherche.migratoires');
        });
    });

    // ============================================================
    // REPORTING
    // ============================================================
    Route::prefix('reporting')->group(function () {
        Route::controller(ReportingController::class)->group(function () {
            Route::get('/',                   'index')                       ->name('reporting.index');
            Route::get('employeur',           'employeur')                   ->name('reporting.employeur');
            Route::get('employeur-show',      'employeurShow')               ->name('reporting.employeur.show');
            Route::get('employeur-pdf',       'employeurReportingPdf')       ->name('reporting.employeur.pdf');
            Route::get('impetrant',           'impetrant')                   ->name('reporting.impetrant');
            Route::get('impetrant-show',      'impetrantShow')               ->name('reporting.impetrant.show');
            Route::get('impetrant-pdf',       'impetrantReportingPdf')       ->name('reporting.impetrant.pdf');
            Route::get('impetrant-liste',     'impetrantListing')            ->name('reporting.impetrant.liste');
            Route::get('impetrant-liste-pdf', 'impetrantReportingListingPdf')->name('reporting.impetrant.liste.pdf');
            Route::get('soit-transmis-pdf',   'soitTransmisPDF')             ->name('soit.transmis.pdf');
            Route::get('flux-migratoire',     'fluxMigratooire')             ->name('reporting.flux.migratoire');
            Route::get('flux-migratoire-pdf', 'fluxmigratoireReportingPdf')  ->name('flux_migratoire.pdf');
        });
    });

    // -- Rapport global -------------------------------------------
    Route::get('/rapports/global',      [ReportingController::class, 'showGlobalForm'])   ->name('rapports.global.form');
    Route::post('/rapports/global/pdf', [ReportingController::class, 'generateGlobalPDF'])->name('rapports.global.pdf');

    // ============================================================
    // IMPETRANTS
    // ============================================================
    Route::prefix('impetrants')->group(function () {

        Route::controller(ImpetrantController::class)->group(function () {

            // -- Liste --------------------------------------------
            Route::get('/', 'index')->name('impetrants.index');

            // -- Enregistrement direct ----------------------------
            Route::get('creer',  'create')->name('impetrants.create');
            Route::post('creer', 'store') ->name('impetrants.store');

            // -- API AJAX (avant les routes {id}) -----------------
            Route::post('api/check-doublon',  'checkDoublon')  ->name('impetrants.api.check-doublon');
            Route::get('api/check-document',  'checkDocument') ->name('impetrants.api.check-document');
            Route::get('api/document-lookup', 'documentLookup')->name('api.document-lookup');

            // -- Search AJAX --------------------------------------
            Route::get('search-ajax', [WatchlistController::class, 'searchAjax'])->name('impetrants.search.ajax');

            // -- Casier global (avant {id}) -----------------------
            Route::get('casier-judiciaire', 'casierGlobal')->name('casier.global');

            // -- Routes avec {id} ---------------------------------
            Route::get('{id}/show',     'show')    ->name('impetrants.show');
            Route::get('{id}/modifier', 'edit')    ->name('impetrants.edit');
            Route::put('{id}/modifier', 'update')  ->name('impetrants.update');
            Route::get('{id}/demandes', 'demandes')->name('impetrants.demandes');
            Route::delete('{id}/destroy','destroy')->name('impetrants.destroy');

            // -- Infractions --------------------------------------
            Route::post('{id}/infractions',           'storeInfraction')       ->name('impetrants.infractions.store');
            Route::patch('infractions/{id}/statut',   'updateStatutInfraction')->name('impetrants.infractions.statut');
            Route::delete('infractions/{id}',         'deleteInfraction')      ->name('impetrants.infractions.delete');
            Route::post('infractions/{id}/preuves',   'storePreuve')           ->name('impetrants.infractions.preuves.store');
            Route::delete('infractions/preuves/{id}', 'deletePreuve')          ->name('impetrants.infractions.preuves.delete');

            // -- Documents ----------------------------------------
            Route::post('{impetrant}/documents', 'storeDocument')->name('impetrants.documents.store');

            // -- Casier individuel --------------------------------
            Route::get('{id}/casier',             'casier')    ->name('impetrants.casier');
            Route::post('{id}/casier/note',       'storeNote') ->name('impetrants.casier.note');
            Route::delete('casier/note/{noteId}', 'deleteNote')->name('impetrants.casier.note.delete');
        });

        // -- Archives -------------------------------------------------
        Route::get('archivage',                     [ArchiveController::class, 'index'])  ->name('archives.index');
        Route::get('archivage/{id}',                [ArchiveController::class, 'show'])   ->name('archives.show');
        Route::post('archivage/{id}/store',         [ArchiveController::class, 'store'])  ->name('archives.store');
        Route::delete('archivage/document/{id}',    [ArchiveController::class, 'destroy'])->name('archives.destroy');
        Route::get('archivage/document/{id}/print', [ArchiveController::class, 'print'])  ->name('archives.print');

        // -- Toggle layout (session) ----------------------------------
        Route::post('set-layout', function (\Illuminate\Http\Request $request) {
            session(['impetrants_layout' => $request->layout]);
            return response()->json(['ok' => true]);
        })->name('impetrants.set.layout');
    });

    // ============================================================
    // SOIT-TRANSMIS
    // ============================================================
    Route::prefix('soit-transmis')->group(function () {
        Route::controller(SoitTransmisController::class)->group(function () {
            Route::get('/',                  'index')               ->name('soit-transmis.index');
            Route::get('create',             'create')              ->name('soit-transmis.create');
            Route::post('store',             'store')               ->name('soit-transmis.store');
            Route::get('attribution-masse',  'attributionMasseForm')->name('soit-transmis.attribution.masse.form');
            Route::get('recherche-avancee',  'rechercheAvancee')    ->name('soit-transmis.recherche.avancee');
            Route::put('add-demande',        'storeDemandes')       ->name('soit-transmis.demandes.store');
            Route::put('drop-demande',       'dropDemandes')        ->name('soit-transmis.dropdemandes');
            Route::post('attribuer-masse',   'attribuerMasse')      ->name('soit-transmis.attribuer.masse');
            Route::get('{id}/show',          'show')                ->name('soit-transmis.show');
            Route::get('{id}/edit',          'edit')                ->name('soit-transmis.edit');
            Route::put('{id}/update',        'update')              ->name('soit-transmis.update');
            Route::delete('{id}/destroy',    'destroy')             ->name('soit-transmis.destroy');
            Route::get('{id}/show-demande',  'showDemandes')        ->name('soit-transmis.demandes.show');
            Route::get('{id}/demandes-attribution','getDemandesAttribution')->name('soit-transmis.demandes.attribution');
        });
    });

    // ============================================================
    // STATISTIQUES AVANCEES
    // ============================================================
    Route::prefix('statistiques')->group(function () {
        Route::controller(StatistiquesController::class)->group(function () {
            Route::get('/',                        'index')                ->name('statistiques.index');
            Route::get('api/demandes-par-jour',    'apiDemandesParJour')   ->name('statistiques.api.demandes.jour');
            Route::get('api/demandes-par-type',    'apiDemandesParType')   ->name('statistiques.api.demandes.type');
            Route::get('api/demandes-par-statut',  'apiDemandesParStatut') ->name('statistiques.api.demandes.statut');
            Route::get('api/demandes-par-agent',   'apiDemandesParAgent')  ->name('statistiques.api.demandes.agent');
            Route::get('api/flux-par-jour',        'apiFluxParJour')       ->name('statistiques.api.flux.jour');
            Route::get('api/flux-par-frontiere',   'apiFluxParFrontiere')  ->name('statistiques.api.flux.frontiere');
            Route::get('api/flux-par-nationalite', 'apiFluxParNationalite')->name('statistiques.api.flux.nationalite');
            Route::get('api/comparaison',          'apiComparaison')       ->name('statistiques.api.comparaison');
            Route::get('export-pdf',               'exportPDF')            ->name('statistiques.export.pdf');
        });
    });

    // ============================================================
    // API CALENDRIER
    // ============================================================
    Route::get('/api/demandes/calendar',    [UserController::class, 'getCalendarData'])->name('api.demandes.calendar');
    Route::get('/api/demandes/jour/{date}', [UserController::class, 'getDemandesJour'])->name('api.demandes.jour');

    // ============================================================
    // MONITOR
    // ============================================================
    Route::get('/monitor',                        [MonitorController::class, 'index'])       ->name('monitor.index')       ->middleware('can:dashboard.view');
    Route::get('/monitor/feed',                   [MonitorController::class, 'feed'])        ->name('monitor.feed');
    Route::get('/monitor/ping',                   [MonitorController::class, 'ping'])        ->name('monitor.ping');
    Route::get('/monitor/user/{userId}/activity', [MonitorController::class, 'userActivity'])->name('monitor.user.activity');

}); // fin middleware('auth')
// LICENCE GRACE
Route::post('/license/grace', function (\Illuminate\Http\Request $request) {
    $user = \App\Models\User::where('email', $request->email)
        ->whereHas('role', function($q) { $q->whereIn('lib_role', ['Admin', 'SuperAdmin']); })
        ->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return back()->with('grace_error', 'Identifiants incorrects ou utilisateur non Admin.');
    }

    $license = \App\Models\License::where('status', 'active')
        ->orWhere('status', 'expired')
        ->first();

    if (!$license) {
        return back()->with('grace_error', 'Aucune licence trouvée.');
    }

    if ($license->grace_used) {
        return back()->with('grace_error', 'La période de grâce a déjà été utilisée. Contactez l\'administrateur.');
    }

    $license->expires_at = now()->addDays(2);
    $license->status = 'active';
    $license->grace_used = 1;
    $license->grace_used_at = now();
    $license->save();
    \Illuminate\Support\Facades\Cache::forget('dmce_license_validation');

    return redirect('/')->with('success', 'Période de grâce de 48h activée.');
})->name('license.grace');
