<?php

use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
     Route::apiResource('blogs.posts', \App\Http\Controllers\Api\PostController::class)
          ->middleware('cache.headers:public;max_age=2628000;etag')
          ->shallow();
});