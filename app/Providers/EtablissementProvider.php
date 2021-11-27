<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Etablissement\EtablissementRepository;
use App\Repositories\Etablissement\IEtablissementRepository;
//use App\Repositories\EtablissementRepository;
class EtablissementProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
       // $this->app->bind('Repositories\IEtablissementRepository', 'App\Repositories\EtablissementRepository');

        $this->app->singleton(IEtablissementRepository::class, EtablissementRepository::class);
        
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
