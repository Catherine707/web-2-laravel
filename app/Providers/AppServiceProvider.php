<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;   // <-- FALTA EN TU CÓDIGO
use Illuminate\Support\Facades\Log;     // <-- FALTA EN TU CÓDIGO

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    return redirect()->to(route('home'));
                }
            };
        });

        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request, $user)
                {
                    return redirect()->to(route('home'));
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if (App::environment('production')) {
            URL::forceScheme('https');
        }

        if (App::environment('production') && Config::get('database.default') === 'sqlite') {
            $path = Config::get('database.connections.sqlite.database');

            if ($path && ! file_exists($path)) {
                @mkdir(dirname($path), 0777, true);
                @touch($path);
                Log::info('[auto-sqlite] database.sqlite creado en: ' . $path);
            }

            $flagKey = 'auto_migrated_sqlite_v1';
            if (! Cache::has($flagKey)) {
                try {
                    Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]);
                    Cache::put($flagKey, now()->toDateTimeString(), now()->addDays(7));
                    Log::info('[auto-sqlite] Migraciones ejecutadas OK.');
                } catch (\Throwable $e) {
                    Log::warning('[auto-sqlite] Falló migrate: ' . $e->getMessage());
                
                }
            }
        }
    }
}