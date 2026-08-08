<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // if (request()->isSecure()) {
            URL::forceScheme('https');
        // }

        View::composer('partials.header', function ($view) {
            $menus = \App\Menu::whereNull('parent_id')
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->with(['children' => function ($query) {
                    $query->where('is_active', 1)->orderBy('sort_order');
                }])
                ->get();

            $view->with('navMenus', $menus);
        });
    }
}
