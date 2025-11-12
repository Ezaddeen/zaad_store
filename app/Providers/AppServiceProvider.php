<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Product;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // [تمت الإضافة] إخبار Laravel بالمسار العام الجديد
        $this->app->bind('path.public', function() {
          
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
