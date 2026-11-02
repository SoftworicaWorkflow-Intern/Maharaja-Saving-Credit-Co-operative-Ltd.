<?php

namespace App\Providers;

use App\Models\ReportCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

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
        view()->composer('layouts.app', function (View $view): void {
            $view->with(
                'reportNavigationCategories',
                Schema::hasTable('report_categories')
                    ? ReportCategory::query()->where('is_active', true)->orderBy('display_order')->orderBy('name_en')->get()
                    : collect(),
            );
        });
    }
}
