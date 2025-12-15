<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ✅ Ensure Sanctum always uses your MongoDB token model
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
