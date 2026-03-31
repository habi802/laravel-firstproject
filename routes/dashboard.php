<?php

// 블로그
Route::get('/blogs', \App\Http\Controllers\Dashboard\BlogController::class)
     ->name('dashboard.blogs');

// 구독
Route::get('/subscribers', \App\Http\Controllers\Dashboard\SubscriberController::class)
     ->name('dashboard.subscribers');
Route::get('/subscriptions', \App\Http\Controllers\Dashboard\SubscriptionController::class)
     ->name('dashboard.subscriptions');