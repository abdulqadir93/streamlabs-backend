<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TokenService;
use App\Services\GoogleClientService;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\TokenService', TokenService::class);
        $this->app->bind('App\Services\GoogleClientService', GoogleClientService::class);
        $this->app->when('App\Services\GoogleClientService')
            ->needs('$config')
            ->give([
                'GOOGLE_CLIENT_ID' => env('GOOGLE_CLIENT_ID'),
                'GOOGLE_CLIENT_SECRET' => env('GOOGLE_CLIENT_SECRET'),
                'GOOGLE_OAUTH2_CALLBACK' => env('GOOGLE_OAUTH2_CALLBACK')
            ]);
    }
}
