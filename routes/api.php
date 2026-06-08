<?php

use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
     Route::apiResource('blogs.posts', \App\Http\Controllers\Api\PostController::class)
          ->middleware('cache.headers:public;max_age=2628000;etag')
          ->shallow();
});

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