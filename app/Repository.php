<?php
namespace App;

use App\Models\ComponentModel;
use App\Models\EtablissementModel;
use App\Models\FichiersModel;
use App\Models\LocalisationModel;
use App\Models\PatientModel;
use App\Models\PersonnelProfession;
use App\Models\PersonnelProfilesModel;
use App\Models\PersonneModel;
use App\Models\ProfileComponentsModel;
use App\Models\ProfileFonctionsModel;
use App\Models\TypeModel;
use App\Repositories\Classes\ComponentRepository;
use App\Repositories\Classes\EtablissementRepository;
use App\Repositories\Classes\FichiersRepository;
use App\Repositories\Classes\LocalisationRepository;
use App\Repositories\Classes\PatientRepository;
use App\Repositories\Classes\PersonnelProfessionRepository;
use App\Repositories\Classes\PersonnelProfilesRepository;
use App\Repositories\Classes\PersonneRepository;
use App\Repositories\Classes\ProfileComponentsRepository;
use App\Repositories\Classes\ProfileFonctionsRepository;
use App\Repositories\Classes\TypeRepository;

class Repository {

    /**
     * @return TypeRepository
     */
    public static function type() {
        return new TypeRepository(new TypeModel());
    }

    /**
     * @return PersonnelProfilesRepository
     */
    public static function persoProfile(){
        return new PersonnelProfilesRepository(new PersonnelProfilesModel());
    } 

    /**
     * @return EtablissementRepository
     */
    public static function etablissement(){
        return new EtablissementRepository(new EtablissementModel());
    } 
    public static function profileFonctions() {
        return new ProfileFonctionsRepository(new ProfileFonctionsModel());
    }
    public static function profileComponent(){
        return new ProfileComponentsRepository(new ProfileComponentsModel());
    } 
    public static function component(){
        return new ComponentRepository(new ComponentModel());
    } 
    public static function personne(){
        return new PersonneRepository(new PersonneModel());
    }
    public static function patient(){
        return new PatientRepository(new PatientModel(), Repository::personne());
    }
    public static function localisation(){
        return new LocalisationRepository(new LocalisationModel(), Repository::type());
    }
    public static function fichiers(){
        return new FichiersRepository(new FichiersModel());
    }

    public static function personnelProfession(){
        return new PersonnelProfessionRepository(new PersonnelProfession());
    } 
}