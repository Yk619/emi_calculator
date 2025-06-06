<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\LoanRepository;
use App\Repositories\LoanRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoanRepositoryInterface::class, LoanRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
