<?php

namespace App\Providers;

use App\Repositories\Package\PackageRepository;
use App\Repositories\Source\SourceRepository;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Repositories\Source\SourceRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    protected array $services = [
        SourceRepositoryInterface::class => SourceRepository::class,
        PackageRepositoryInterface::class => PackageRepository::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->services as $interface => $implement) {
            $this->app->bind($interface, $implement);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
