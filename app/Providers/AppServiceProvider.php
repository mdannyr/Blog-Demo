<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // This helps to organize our code from different folder/files to
        // use with less wording to make it more readable later in the future

        Blade::aliascomponent('componets.badge','badge');
        Blade::aliasComponent('componets.updated','updated');
        Blade::aliasComponent('componets.card','card');
        Blade::aliasComponent('componets.tags','tags');

        view()->composer('posts.index', ActivityComposer::class);
        //  Blade::component('componets.badge','badge');    //old laravel syntax

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
