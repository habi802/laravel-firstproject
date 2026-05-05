<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('blogs.posts', \App\Http\Controllers\Api\PostController::class)
     ->shallow();