<?php

namespace App\Providers;

use App\Projectors\ProductAmountProjector;
use Illuminate\Support\ServiceProvider;
use Spatie\EventSourcing\Facades\Projectionist;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Projectionist::addProjector(ProductAmountProjector::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
