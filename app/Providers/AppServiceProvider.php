<?php

namespace App\Providers;

use App\Hashing\SkaenergyHasher;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Setting::class, function (){
            $settings = new Setting();
            return $settings->getSettings();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Hash::extend('skaenergy', static function () {
            return new SkaenergyHasher();
        });
    }
}
