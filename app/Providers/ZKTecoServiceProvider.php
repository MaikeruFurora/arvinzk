<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Config;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Auth;

class ZKTecoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ZKTeco::class, function ($app) {
            // Get the configuration based on the currently authenticated user
            $userId = Auth::id();
            $config = Config::where('user_id', $userId)
                            ->where('active_device', 1)
                            ->first();
            
            if ($config) {
                return new ZKTeco($config->ip, $config->port);
            } else {
                // Handle the case where no configuration is found
                abort(500, 'No configuration found for this user.');
            }
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
