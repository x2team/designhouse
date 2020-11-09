<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositores\Contracts\{
    IDesign,
    IUser
};
use App\Repositores\Eloquent\{
    DesignRepository,
    UserRepository 
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(Idesign::class, DesignRepository::class);
        $this->app->bind(IUser::class, UserRepository::class);
    }
}
