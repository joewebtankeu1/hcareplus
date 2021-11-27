<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', function () {
    return Hash::make("ilovehcare");
});

Route::prefix("type")->group(function () {
    Route::apiResource("admin", "Type");
    Route::get('bycode/{code}', "Type@getByCode");
    Route::get('listchild/{code}', "Type@listChild");
    Route::get('onlypages', "Type@getOnlyParent");
});
Route::prefix("component")->group(function () {
    Route::apiResource("admin", "Component");
    Route::get('bycode/{code}', "Component@getByCode");
    Route::get('listchild/{code}', "Component@listChild");
    Route::get('onlypages', "Component@getOnlyPages");
});

Route::prefix("etablissement")->group(function () {
    Route::apiResource("admin", "EtablissementController");
    Route::get('bycode/{code}', "EtablissementController@getByCode");
    Route::get('listchild/{code}', "EtablissementController@listChild");
    Route::get('onlypages', "EtablissementController@getOnlyPages");
});

Route::prefix("personne")->group(function () {
    Route::apiResource("admin", "PersonneController");
});

Route::prefix("personnel")->group(function () {
    Route::Resource("admin", "PersonnelController");
    Route::get("bycode/{code}", "PersonnelController@getByCode");
});

Route::prefix("utilisateur")->group(function () {
    Route::apiResource("admin", "UtilisateurController");
});

Route::prefix("patient")->group(function () {
    Route::apiResource("admin", "PatientController");
    Route::get("bycode/{code}", "PatientController@getByCode");
});

Route::prefix("localisation")->group(function () {
    Route::apiResource("admin", "LocalisationController");
    Route::get('bycode/{code}', "LocalisationController@getByCode");
    Route::get('listchild/{code}', "LocalisationController@listChild");
    Route::get('onlypages', "LocalisationController@getOnlyPages");
});

Route::prefix("adresse")->group(function () {
    Route::apiResource("admin", "AdresseController");
});

Route::prefix("contact")->group(function () {
    Route::apiResource("admin", "ContactController");
});

Route::post("signup", "NouveauPersonnelController@store");
Route::post("login", "NouveauPersonnelController@login");
Route::get("authentication", "AuthenticationController@task");
Route::get("get_personnel", "AuthenticationController@getPersonnel");
Route::get("get_patient", "AuthenticationController@getPatient");
Route::get("get_etab", "AuthenticationController@getEtab");
Route::prefix("session")->group(function () {
    Route::prefix("personnel")->group(function () {
        Route::get("", "SessionController@getPersonnels");
        Route::get("all", "SessionController@personnelGetAll");
        Route::get("/{code}", "SessionController@personnelGet");
        Route::put("/{code}", "SessionController@lockPersonnel");
        Route::prefix("address")->group(function () {
            Route::get("{code}", "SessionController@personnelGetAdresse");
            Route::post("{code}", "SessionController@personnelCreateAdresse");
            Route::post("{code}/{id}", "SessionController@personnelUpdateAdresse");
            Route::get("contact/{code}/{id}", "SessionController@personnelGetContact");
            Route::post("contact/{code}/{id}", "SessionController@personnelCreateContact");
        });
        Route::post("{code}/profile", "SessionController@createPersonnelProfile");
        Route::get("{code}/profile", "SessionController@getPersonnelProfile");
        Route::post("{code}/profile/delete", "SessionController@deletePersonnelProfile");
        Route::post("{code}/profession", "SessionController@createPersonnelProfession");
        Route::get("{code}/profession", "SessionController@getPersonnelProfession");
        Route::post("{code}/profession/delete", "SessionController@deletePersonnelProfession");
        Route::prefix("update")->group(function () {
            Route::put("/{code}", "SessionController@updatePersonnel");
            Route::post("/avatar/{code}", "SessionController@updateAvatarPersonnel");
        });
        Route::prefix("user")->group(function () {
            Route::put("{code}/", "SessionController@updateLoginInfo");
        });
    });
    Route::prefix("profile")->group(function () {
        Route::get("", "SessionController@indexProfile");
        Route::post("", "SessionController@createProfile");
        Route::put("component", "SessionController@updateProfileComponent");
        Route::put("fonction", "SessionController@updateProfileFonction");
        Route::get("/{code}/component", "SessionController@getProfileComponents");
        Route::get("/{code}/fonction", "SessionController@getProfileFonctions");
        Route::put("/{code}", "SessionController@updateProfile");
        Route::post("/{code}/component", "SessionController@addProfileComponent");
    });
    Route::prefix("profession")->group(function () {
        Route::get("", "SessionController@indexProfession");
        Route::post("", "SessionController@createProfession");
        Route::put("/{code}", "SessionController@updateProfession");
        Route::post("/{code}", "SessionController@deleteProfession");
    });
    Route::prefix("fonction")->group(function () {
        Route::get("", "SessionController@indexFonction");
        Route::post("", "SessionController@createFonction");
    });
    Route::prefix("etablissement")->group(function () {
        Route::post("", "SessionController@createEtablissement");
        Route::get("", "SessionController@getAllEtablissement");
        Route::get("onlyparent", "SessionController@indexEtablissement");
        Route::get("/{code}", "SessionController@showEtablissement");
        Route::get("/{code}/children", "SessionController@getEtablissementChild");
        Route::put("/{code}", "SessionController@updateEtablissement");
        Route::delete("/{code}", "SessionController@deleteEtablissement");
        Route::post("/{code}/logo", "SessionController@updateLogoEtablissement");
        Route::post("/service", "SessionController@newEtablissementServicePersonnel");
        Route::post("/personnel", "SessionController@newEtablissementServicePersonnel");
        Route::post("/directeur", "SessionController@newEtablissementServicePersonnel");
        Route::put("/adresse/{code}", "SessionController@updateEtabAdress");
        Route::get("/bypersonnel/{code}", "SessionController@getEtabByPersonnel");
    });
    Route::prefix("type_etab")->group(function () {
        Route::get('', "SessionController@getAllTypeEtablissement");
        Route::get('{code}', "SessionController@getTypeEtablissement");
        Route::post('', "SessionController@newTypeEtablissement");
        Route::put('{code}', "SessionController@updateTypeEtablissement");
        Route::delete('{code}', "SessionController@deleteTypeEtablissement");
    });
    Route::prefix("detail-etablissement")->group(function () {
        Route::get('', "SessionController@getAllDetailEtablissement");
        Route::get('{code}', "SessionController@getDetailEtablissement");
        Route::post('', "SessionController@newDetailEtablissement");
        Route::put('{code}', "SessionController@updateDetailEtablissement");
        Route::delete('{code}', "SessionController@deleteDetailEtablissement");
    });
    Route::prefix("component")->group(function () {
        Route::get("", "SessionController@getComponents");
        Route::get("onlyparent", "SessionController@getComponentPages");
        Route::get("{code}", "SessionController@getComponentByCode");
        Route::get("{code}/children", "SessionController@getComponentChild");
    });

    Route::prefix("patient")->group(function () {
        Route::get("", "SessionController@getPatients");
        Route::post("", "SessionController@createPatient");
        Route::get("/{code}", "SessionController@getPatient");
        Route::prefix("address")->group(function () {
            Route::get("{code}", "SessionController@getPatientAddress");
            Route::post("{code}", "SessionController@createPatientAdresse");
            Route::post("{code}/{id}", "SessionController@updatePatientAdresse");
            Route::get("contact/{code}/{id}", "SessionController@getPatientContact");
            Route::post("contact/{code}/{id}", "SessionController@createPatientContact");
        });
        Route::prefix("update")->group(function () {
            Route::put("/{code}", "SessionController@updatePatientPersonne");
            Route::post("/avatar/{code}", "SessionController@updatePatientAvatar");
        });
    });
    Route::prefix("type")->group(function () {
        Route::get('address', "SessionController@getTypeAddress");
        Route::get('location', "SessionController@getTypeLocation");
        Route::get('contact', "SessionController@getTypeContact");
        Route::get('urgence', "SessionController@getTypeUrgence");
        Route::post('urgence', "SessionController@newTypeUrgence");
        Route::put('/{code}', "SessionController@lockProfile");
    });
    Route::prefix("couleur")->group(function () {
        Route::post('', "SessionController@newCouleur");
        Route::get('', "SessionController@getAllCouleurs");
        Route::get('{code}', "SessionController@getCouleur");
        Route::put('/{code}', "SessionController@updateCouleur");
        Route::delete('/{code}', "SessionController@deleteCouleur");
    });
    Route::prefix("autosuggest")->group(function () {
        Route::get("location", "SessionController@autoSuggestLocation");
        Route::get("personne", "SessionController@autoSuggestPersonne");
        Route::get("personnel", "SessionController@autoSuggestPersonnel");
        Route::get("patient", "SessionController@autoSuggestPatient");
        Route::get("etablissement", "SessionController@autoSuggestEtablissement");
    });
    Route::prefix("stats")->group(function () {
        Route::get("", "SessionController@getStats");
    });
});
