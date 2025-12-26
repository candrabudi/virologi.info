<?php

namespace App\Providers;

use App\Models\FooterContact;
use App\Models\FooterQuickLink;
use App\Models\FooterSetting;
use App\Models\Website;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $data = Cache::remember('global_layout_data', 3600, function () {
                return [
                    'setting' => Website::query()->first(),
                    'footerSetting' => FooterSetting::query()->where('is_active', true)->first(),
                    'quickLinks' => FooterQuickLink::query()->where('is_active', true)->orderBy('sort_order')->get(),
                    'contacts' => FooterContact::query()->where('is_active', true)->orderBy('sort_order')->get(),
                ];
            });

            $view->with($data);
        });
    }
}
