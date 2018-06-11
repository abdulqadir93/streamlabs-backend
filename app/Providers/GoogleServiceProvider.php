<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TokenService;
use App\Services\GoogleClientService;
use App\Services\GoogleUserService;
use App\Services\GoogleVideoService;
use App\Services\GoogleLiveChatService;

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
        $this->app->bind('App\Services\GoogleUserService', GoogleUserService::class);
        $this->app->bind('App\Services\GoogleVideoService', GoogleVideoService::class);
        $this->app->bind('App\Services\GoogleLiveChatService', GoogleLiveChatService::class);
    }
}
