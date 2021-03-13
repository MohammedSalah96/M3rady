<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Backend\Admin\AdminRepositoryInterface',
            'App\Repositories\Backend\Admin\AdminRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\Group\GroupRepositoryInterface',
            'App\Repositories\Backend\Group\GroupRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\Setting\SettingRepositoryInterface',
            'App\Repositories\Backend\Setting\SettingRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\SettingTranslation\SettingTranslationRepositoryInterface',
            'App\Repositories\Backend\SettingTranslation\SettingTranslationRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\ContactMessage\ContactMessageRepositoryInterface',
            'App\Repositories\Backend\ContactMessage\ContactMessageRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\Location\LocationRepositoryInterface',
            'App\Repositories\Backend\Location\LocationRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\LocationTranslation\LocationTranslationRepositoryInterface',
            'App\Repositories\Backend\LocationTranslation\LocationTranslationRepository'
        );

       
        $this->app->bind(
            'App\Repositories\Backend\Category\CategoryRepositoryInterface',
            'App\Repositories\Backend\Category\CategoryRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\CategoryTranslation\CategoryTranslationRepositoryInterface',
            'App\Repositories\Backend\CategoryTranslation\CategoryTranslationRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\Package\PackageRepositoryInterface',
            'App\Repositories\Backend\Package\PackageRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\PackageTranslation\PackageTranslationRepositoryInterface',
            'App\Repositories\Backend\PackageTranslation\PackageTranslationRepository'
        );

       
  
        
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
