<?php

namespace App\Providers;

use App\Repositories\BookRepository;
use App\Repositories\CartRepository;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\PayMobService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PayMobService::class, function ($app) {
            return new PayMobService();
        });
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
