<?php

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path(('routes/auth.php')));

            // JWT
            Route::withoutMiddleware('web')->middleware('api')->group(function () {
                Route::controller(\App\Http\Controllers\Auth\JwtLoginController::class)->group(function () {
                    Route::name('jwt.')->prefix('jwt')->group(function () {
                        Route::post('login', 'store')
                             ->name('login');
                        Route::middleware('auth:api')->group(function () {
                            Route::put('refresh', 'update')
                                 ->name('refresh');
                            Route::delete('logout', 'destroy')
                                 ->name('logout');
                        });
                    });
                });
            });
        });
    }
}