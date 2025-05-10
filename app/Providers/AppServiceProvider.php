<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Composers\SidebarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Prevent error during artisan commands like package:discover
        if (App::runningInConsole()) {
            return;
        }

        // Register view composers
        $this->registerViewComposers();
    }

    /**
     * Register view composers.
     */
    protected function registerViewComposers(): void
    {

        // Registering specific view composers
        View::composer('TDEIS.auth.PM.body.sidebar', SidebarComposer::class);
    }

    /**
     * Policy bindings.
     */
    protected $policies = [
        Skill::class => SkillPolicy::class,
        Contribution::class => ContributionPolicy::class,
    ];
}
