<?php

Route::get('/blogs', \App\Http\Controllers\Dashboard\BlogController::class)
    ->name('dashboard.blogs');