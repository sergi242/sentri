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
use App\Models\CategorieSocioProfessionnelle;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ArrondissementController;
use App\Http\Controllers\FluxMigratoireController;
use App\Http\Controllers\FrontiereCongoController;
use App\Http\Controllers\MotifContentieuxController;
use App\Http\Controllers\CategorieSocioProfessionnelleController;
use App\Http\Controllers\ImpetrantController;
use App\Http\Controllers\ListeAlertController;
use App\Http\Controllers\SoitTransmisController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("stats-migratoire",[DemandeController::class,"testprint"])->name("flux.stats.etat")->middleware("can:flux.view");

Route::post("authenticate",[UserController::class,"authenticate"])->name("users.authenticate");

Route::middleware("auth")->group(function(){
    Route::get("/",[UserController::class,"home"])->name("users.home");
    Route::get("dashboard",[UserController::class,"dashboard"])->name("users.dashboard")->middleware("can:dashboard.view");
    Route::prefix("users")->group(function(){
        Route::controller(UserController::class)->group(function(){
            Route::get("/", "index")->name("users.index")->middleware("can:users.view"); // Page principale des utilisateurs
            Route::get("statistics", "activites")->name("users.activites")->middleware("can:users.view"); // Page des statistiques
            Route::get('/users/{id}/show', [UserController::class, 'show'])->name('users.show')->middleware('can:users.view');
            Route::get("create", "create")->name("users.create")->middleware("can:users.create");
            Route::get("change-password", "change_password_form")->name("users.password.form");
            Route::post("store", "store")->name("users.store")->middleware("can:users.create");
            Route::put("change-password", "change_password")->name("users.changepassword");
            Route::get("{id}/edit", "edit")->name("users.edit")->middleware("can:users.edit");
            Route::put("{id}/update", "update")->name("users.update")->middleware("can:users.edit");
            Route::delete("{id}/destroy", "destroy")->name("users.destroy")->middleware("can:users.destroy");

            Route::put('/users/{id}/reset-password', 'resetPassword')->name('users.reset-password');
            Route::get('/users/report','exportReportPdf')->name('users.report.pdf');
            Route::get('users/{id}/activities/pdf', 'exportUserActivitiesPdf')->name('users.activities.pdf')->middleware('can:users.view');
        });
    });
    
    Route::prefix("roles")->group(function(){
        Route::controller(RoleController::class)->group(function(){
            Route::get("/","index")->name("role.index")->middleware("can:roles.view");
            Route::get("create","create")->name("role.create")->middleware("can:roles.create");
            Route::post("store","store")->name("role.store")->middleware("can:roles.create");
            Route::get("{id}/edit","edit")->name("role.edit")->middleware("can:roles.edit");
            Route::put("{id}/update","update")->name("role.update")->middleware("can:roles.edit");
            Route::delete("{id}/destroy","destroy")->name("role.destroy")->middleware("can:roles.destroy");
        });
    });

    Route::prefix("grades")->group(function(){
        Route::controller(GradeController::class)->group(function(){
            Route::get("/","index")->name("grade.index");
            Route::get("create","create")->name("grade.create");
            Route::post("store","store")->name("grade.store");
            Route::get("{id}/edit","edit")->name("grade.edit");
            Route::put("{id}/update","update")->name("grade.update");
            Route::delete("{id}/destroy","destroy")->name("grade.destroy");
        });
    });

    Route::prefix("flux")->group(function(){
        Route::controller(FluxMigratoireController::class)->group(function(){
            Route::get("/","index")->name("flux.index")->middleware("can:flux.view");
            Route::get("stats-migration-entre","fluxmigratoiresatentre")->name("flux.stats.entre");
            Route::get("stats-migration-sortie","fluxmigratoiresatsortie")->name("flux.stats.sortie");
            Route::get("create","create")->name("flux.create")->middleware("can:flux.create");
            Route::post("store","store")->name("flux.store")->middleware("can:flux.create");
            Route::get("{id}/edit","edit")->name("flux.edit")->middleware("can:flux.edit");
            Route::put("{id}/update","update")->name("flux.update")->middleware("can:flux.edit");
            Route::delete("{id}/destroy","destroy")->name("flux.destroy")->middleware("can:flux.destroy");
        });
    });

    Route::prefix("demandes")->group(function(){
        Route::controller(DemandeController::class)->group(function(){
            Route::get("/","index")->name("demandes.index")->middleware("can:demandes.view.all");
            Route::get("{id}/photo-camera","takePhotoCamera")->name("takePhotoCamera.index");

            Route::get("stats-demandes","demandestats")->name("demandes.demandestats");
            Route::get("stats-attributions","demandesattribuees")->name("demandes.stats.attributions");
            Route::get("newdocument","newdocument")->name("demandes.newdocument")->middleware("can:demandes.create");
            Route::get("renouvellement","renouvellement")->name("demandes.renouvellement")->middleware("can:demandes.renew");
            Route::get("approuvees","approuvees")->name("demandes.approuvees")->middleware("can:demandes.view.approved");
            Route::get("impression-cartes","impressioncartes")->name("demandes.impressioncartes")->middleware("can:demandes.print.cards");
            Route::get("contentieux","contentieux")->name("demandes.contentieux")->middleware("can:demandes.view.contentieux");
            Route::get("attentes","attentes")->name("demandes.attentes")->middleware("can:demandes.view.pending");
            Route::get("attribuees","attribuees")->name("demandes.attribuees")->middleware("can:demandes.view.attribue");
            Route::get("attente-attributions","attente_attribution")->name("demandes.attente.attributions");
            Route::get("create","create")->name("demandes.create")->middleware("can:demandes.create");
            Route::get("new-visa","newvisa")->name("demandes.newvisa")->middleware("can:demandes.create");
            Route::get("new-crt","newcrt")->name("demandes.newcrt")->middleware("can:demandes.create");
            Route::get("new-diplomate","diplomate")->name("demandes.newdiplomate")->middleware("can:demandes.create");
            Route::get("recherche","search")->name("demandes.search.form");
            Route::get("proche-expiration","procheExpiration")->name("demandes.proche.expiration")->middleware("can:demandes.view.all");
            Route::get("export-to-json", "exportToJson")->name("demandes.exporttojson")->middleware("can:demandes.destroy");
            Route::get("{id}/edit","edit")->name("demandes.edit")->middleware("can:demandes.edit");
            Route::get("{id}/renouveler","renouveler")->name("demandes.renouveler")->middleware("can:demandes.renew");
            Route::get("{id}/show","show")->name("demandes.show")->middleware("can:demandes.details");
            Route::get("{id}/create/contentieux","createcontentieux")->name("demandes.create.contentieux")->middleware("can:demandes.contentieux.add");
            Route::get("{id}/fiche","fiche")->name("demandes.fiche")->middleware("can:demandes.print");
            Route::get("{id}/take-photo","takephoto")->name("demandes.takephoto");
            Route::get("{id}/information/document","remplirformation")->name("demandes.remplirformation")->middleware("can:demandes.grant");
            Route::get("{id}/similarites","similarities")->name("demandes.similarities")->middleware("can:demandes.view.similar");
            Route::get("compare-similarity","compareSimilarity")->name("demandes.compare.similarity");

            Route::post("store","store")->name("demandes.store")->middleware("can:demandes.create");
            Route::post("{id}/photo-store","storePhoto")->name("storePhoto.index");
            Route::post("{id}/renew-store","renewstore")->name("demandes.renewstore")->middleware("can:demandes.create");//can:demandes.view.renew
            Route::post("searchdocument","searchdocument")->name("demandes.searchdocument");
            Route::put("{id}/update","update")->name("demandes.update")->middleware("can:demandes.edit");
            Route::put("{id}/changestate","changestate")->name("demandes.changestate");
            Route::post("{id}/store/contentieux","storecontentieux")->name("demandes.storecontentieux")->middleware("can:demandes.contentieux.add");
            Route::post("{id}/renouveler-fiche","renouvelerFiche")->name("demandes.renouveler.fiche")->middleware("can:demandes.renew");
            Route::put("{id}/store/remplirformation","storeremplirformation")->name("demandes.storeremplirformation")->middleware("can:demandes.grant");
            Route::delete("{id}/destroy","destroy")->name("demandes.destroy")->middleware("can:demandes.destroy");
        });
    });

    Route::middleware("can:demandes.create")->group(function(){
        Route::prefix("categorie-socioprofessionnelle")->group(function(){
            Route::controller(CategorieSocioProfessionnelleController::class)->group(function(){
                Route::get("/","index")->name("categorie.socio.index");
                Route::get("create","create")->name("categorie.socio.create");
                Route::post("store","store")->name("categorie.socio.store");
                Route::get("{id}/edit","edit")->name("categorie.socio.edit");
                Route::put("{id}/update","update")->name("categorie.socio.update");
                Route::delete("{id}/destroy","destroy")->name("categorie.socio.destroy");
            });
        });

        Route::prefix("pays")->group(function(){
            Route::controller(PaysController::class)->group(function(){
                Route::get("/","index")->name("pays.index");
                Route::get("create","create")->name("pays.create");
                Route::post("store","store")->name("pays.store");
                Route::get("{id}/edit","edit")->name("pays.edit");
                Route::put("{id}/update","update")->name("pays.update");
                Route::delete("{id}/destroy","destroy")->name("pays.destroy");
            });
        });

        Route::prefix("frontieres")->group(function(){
            Route::controller(FrontiereCongoController::class)->group(function(){
                Route::get("/","index")->name("frontieres.index");
                Route::get("create","create")->name("frontieres.create");
                Route::post("store","store")->name("frontieres.store");
                Route::get("{id}/edit","edit")->name("frontieres.edit");
                Route::put("{id}/update","update")->name("frontieres.update");
                Route::delete("{id}/destroy","destroy")->name("frontieres.destroy");
            });
        });


        Route::prefix("departements")->group(function(){
            Route::controller(DepartementController::class)->group(function(){
                Route::get("/","index")->name("departements.index");
                Route::get("{id}/edit","edit")->name("departements.edit");
                Route::get("{id}/arrondissements","arrondissements")->name("departements.arrondissements");
                Route::post("store","store")->name("departements.store");
                Route::put("{id}/update","update")->name("departements.update");
                Route::delete("{id}/destroy")->name("departements.destroy");
            });
        });

        Route::prefix("arrondissements")->group(function(){
            Route::controller(ArrondissementController::class)->group(function(){
                Route::get("/","index")->name("arrondissements.index");
                Route::get("{id}/edit","edit")->name("arrondissements.edit");
                Route::get("{id}/quartiers","quartiers")->name("arrondissements.quartiers");
                Route::post("store","store")->name("arrondissements.store");
                Route::put("{id}/update","update")->name("arrondissements.update");
                Route::delete("{id}/destroy")->name("arrondissements.destroy");
            });
        });

        Route::prefix("quartiers")->group(function(){
            Route::controller(QuartierController::class)->group(function(){
                Route::get("/","index")->name("quartiers.index");
                Route::get("{id}/edit","edit")->name("quartiers.edit");
                Route::post("store","store")->name("quartiers.store");
                Route::put("{id}/update","update")->name("quartiers.update");
                Route::delete("{id}/destroy")->name("quartiers.destroy");
            });
        });

        Route::prefix("employeurs")->group(function(){
            Route::controller(EmployeurController::class)->group(function(){
                Route::get("/","index")->name("employeur.index");
                Route::get("{id}/edit","edit")->name("employeur.edit");
                Route::post("store","store")->name("employeur.store");
                Route::put("{id}/upadte","update")->name("employeur.update");
                Route::delete("{id}/destroy","destroy")->name("employeur.destroy");
            });
        });

        Route::prefix("motifs-contentieux")->group(function(){
            Route::controller(MotifContentieuxController::class)->group(function(){
                Route::get("/","index")->name("motifs.contentieux.index");
                Route::get("{id}/edit","edit")->name("motifs.contentieux.edit");
                Route::post("store","store")->name("motifs.contentieux.store");
                Route::put("{id}/upadte","update")->name("motifs.contentieux.update");
                Route::delete("{id}/destroy","destroy")->name("motifs.contentieux.destroy");
            });
        });
    });

    Route::prefix("graphes")->group(function(){
        Route::controller(GrapheController::class)->group(function(){
            Route::get("flux-demandes","demande")->name("graphes.flux-demande");
            Route::get("demandes","demandes")->name("graphes.demandes");
            Route::get("flux-migratoire","flux")->name("graphes.flux-migratoire");
            Route::get("migratoires","migratoires")->name("graphes.migratoires");
        });
    });

    Route::prefix("recherche")->group(function(){
        Route::controller(SearchController::class)->group(function(){
            Route::get("impetrant","searchByImpetrant")->name("search.impetrant");
            Route::get("type-document","searchByDocument")->name("search.type.docs");
            Route::get("demandes","demandes")->name("graphes.demandes");
            Route::get("flux-migratoire","flux")->name("graphes.flux-migratoire");
            Route::get("migratoires","migratoires")->name("graphes.migratoires");
        });
    });

    Route::prefix("reporting")->group(function(){
        Route::controller(ReportingController::class)->group(function(){
            Route::get("/","index")->name("reporting.index");
            Route::get("employeur","employeur")->name("reporting.employeur");
            Route::get("employeur-show","employeurShow")->name("reporting.employeur.show");
            Route::get("employeur-pdf","employeurReportingPdf")->name("reporting.employeur.pdf");
            Route::get("impetrant","impetrant")->name("reporting.impetrant");
            Route::get("impetrant-show","impetrantShow")->name("reporting.impetrant.show");
            Route::get("impetrant-pdf","impetrantReportingPdf")->name("reporting.impetrant.pdf");
            Route::get("impetrant-liste","impetrantListing")->name("reporting.impetrant.liste");
            Route::get("impetrant-liste-pdf","impetrantReportingListingPdf")->name("reporting.impetrant.liste.pdf");
            Route::get("soit-transmis-pdf","soitTransmisPDF")->name("soit.transmis.pdf");
            Route::get("flux-migratoire","fluxMigratooire")->name("reporting.flux.migratoire");
            Route::get("flux-migratoire-pdf","fluxmigratoireReportingPdf")->name("flux_migratoire.pdf");
        });
    });

    Route::prefix("impetrants")->group(function(){
        Route::controller(ImpetrantController::class)->group(function(){
            Route::get("/","index")->name("impetrants.index")->middleware("can:demandes.view.all");
            Route::get("{id}/demandes","demandes")->name("impetrants.demandes");//middleware("can:impetrant.demandes.view.all");
        });
    });

    Route::prefix("soit-transmis")->group(function(){
        Route::controller(SoitTransmisController::class)->group(function(){
            Route::get("/","index")->name("soit-transmis.index");
            Route::get("{id}/show","show")->name("soit-transmis.show"); //Affiche un soit-transmis
            Route::get("create","create")->name("soit-transmis.create"); // Page de creation d'un soit-transmis
            Route::post("store","store")->name("soit-transmis.store"); // Fonction de storage d'un soit-transmis
            Route::get('soit-transmis/{id}/edit', 'edit')->name('soit-transmis.edit');
            Route::put('soit-transmis/{id}/update', 'update')->name('soit-transmis.update');
            Route::delete('soit-transmis/{id}/destroy', 'destroy')->name('soit-transmis.destroy'); //Suppression d'un soit-transmis
            Route::get('soit-transmis/{id}/show-demande', 'showDemandes')->name('soit-transmis.demandes.show'); //Voir les demandes d'un soi-transmis
            Route::put('soit-transmis/add-demande', 'storeDemandes')->name('soit-transmis.demandes.store'); // Ajouter une demande dans un soit transmis
            Route::put('soit-transmis/drop-demande', 'dropDemandes')->name('soit-transmis.dropdemandes');

        });
    });

    //Route de ma liste d'alerte
    // Route::prefix("liste-alerte")->group(function(){
    //     Route::controller(ListeAlertController::class)->group(function(){
    //         Route::get("/","index")->name("liste-alerte.index");
    //         Route::get("/create","create")->name("liste-alerte.create");
    //         Route::get("/show/{id}","show")->name("liste-alerte.show");
    //         Route::post("/store","store")->name("liste-alerte.store");

    //     });
    // });
});

Route::get("flux/get-frontieres-by-departement/{id}",[FluxMigratoireController::class,"getFrontieresByDepartement"])->name("flux.getFrontieresByDepartement");

Auth::routes();


