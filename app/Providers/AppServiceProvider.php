<?php

namespace App\Providers;

use App\Repositories\Classes\AdresseRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Classes\AppRepository;
use App\Repositories\Classes\AuthenticationRepository;
use App\Repositories\Classes\BasicRepository;
use App\Repositories\Classes\ComponentRepository;
use App\Repositories\Classes\ContactRepository;
use App\Repositories\Classes\CouleurRepository;
use App\Repositories\Classes\DetailEtablissementRepository;
use App\Repositories\Classes\EtablissementRepository;
use App\Repositories\Classes\FichiersRepository;
use App\Repositories\Classes\LocalisationRepository;
use App\Repositories\Classes\NouveauPersonnelRepository;
use App\Repositories\Classes\PatientRepository;
use App\Repositories\Classes\PersonnelProfilesRepository;
use App\Repositories\Classes\PersonnelRepository;
use App\Repositories\Classes\PersonneRepository as ClassesPersonneRepository;
use App\Repositories\Classes\ProfileComponentsRepository;
use App\Repositories\Classes\ProfileFonctionsRepository;
use App\Repositories\Classes\SessionRepository;
use App\Repositories\Classes\TypeDomaineRepository;
use App\Repositories\Classes\TypeRepository;
use App\Repositories\Classes\UtilisateurRepository;
use App\Repositories\Interfaces\IAdresseRepository;
use App\Repositories\Interfaces\IAppRepository;
use App\Repositories\Interfaces\IAuthenticationRepository;
use App\Repositories\Interfaces\IBasicRepository;
use App\Repositories\Interfaces\IComponentRepository;
use App\Repositories\Interfaces\IContactRepository;
use App\Repositories\Interfaces\ICouleurRepository;
use App\Repositories\Interfaces\IDetailEtablissementRepository;
use App\Repositories\Interfaces\IEtablissementRepository;
use App\Repositories\Interfaces\IFichiersRepository;
use App\Repositories\Interfaces\ILocalisationRepository;
use App\Repositories\Interfaces\INouveauPersonnelRepository;
use App\Repositories\Interfaces\IPatientRepository;
use App\Repositories\Interfaces\IPersonnelProfilesRepository;
use App\Repositories\Interfaces\IPersonnelRepository;
use App\Repositories\Interfaces\IPersonneRepository as InterfacesIPersonneRepository;
use App\Repositories\Interfaces\IProfileComponentsRepository;
use App\Repositories\Interfaces\IProfileFonctionsRepository;
use App\Repositories\Interfaces\ISessionRepository;
use App\Repositories\Interfaces\ITypeDomaineRepository;
use App\Repositories\Interfaces\ITypeRepository;
use App\Repositories\Interfaces\IUtilisateurRepository;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(IComponentRepository::class, ComponentRepository::class);
        $this->app->singleton(IAppRepository::class, AppRepository::class);
        $this->app->singleton(IBasicRepository::class, BasicRepository::class);
        $this->app->singleton(ITypeRepository::class, TypeRepository::class);
        $this->app->singleton(IEtablissementRepository::class, EtablissementRepository::class);
        $this->app->singleton(InterfacesIPersonneRepository::class, ClassesPersonneRepository::class);
        $this->app->singleton(IPersonnelRepository::class, PersonnelRepository::class);
        $this->app->singleton(IUtilisateurRepository::class, UtilisateurRepository::class);
        $this->app->singleton(IPatientRepository::class, PatientRepository::class);
        $this->app->singleton(ILocalisationRepository::class, LocalisationRepository::class);
        $this->app->singleton(IContactRepository::class, ContactRepository::class);
        $this->app->singleton(IAdresseRepository::class, AdresseRepository::class);
        $this->app->singleton(INouveauPersonnelRepository::class, NouveauPersonnelRepository::class);
        $this->app->singleton(ISessionRepository::class, SessionRepository::class);
        $this->app->singleton(IProfileComponentsRepository::class, ProfileComponentsRepository::class);
        $this->app->singleton(IPersonnelProfilesRepository::class, PersonnelProfilesRepository::class);
        $this->app->singleton(IFichiersRepository::class, FichiersRepository::class);
        $this->app->singleton(IProfileFonctionsRepository::class, ProfileFonctionsRepository::class);
        $this->app->singleton(IAuthenticationRepository::class, AuthenticationRepository::class);
        $this->app->singleton(IDetailEtablissementRepository::class, DetailEtablissementRepository::class);
        $this->app->singleton(ITypeDomaineRepository::class, TypeDomaineRepository::class);
        $this->app->singleton(ICouleurRepository::class, CouleurRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
