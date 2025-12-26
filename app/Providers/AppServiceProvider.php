<?php

namespace App\Providers;

use App\Models\FooterContact;
use App\Models\FooterQuickLink;
use App\Models\FooterSetting;
use App\Models\Website;
use App\Observers\GlobalLayoutObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Website::observe(GlobalLayoutObserver::class);
        FooterSetting::observe(GlobalLayoutObserver::class);
        FooterQuickLink::observe(GlobalLayoutObserver::class);
        FooterContact::observe(GlobalLayoutObserver::class);
    }
}
