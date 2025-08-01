<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\NotificationComposer;
use Illuminate\Support\ServiceProvider;

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
        View::composer('layouts.sidebars.sidebar', NotificationComposer::class);
    }
}
