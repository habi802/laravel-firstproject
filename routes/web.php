<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', \App\Http\Controllers\WelcomeController::class);

// 회원 가입
Route::controller(\App\Http\Controllers\Auth\RegisterController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', 'showRegistrationForm')
            ->name('register');
        Route::post('/register', 'register');
    });
});

// 이메일 인증
Route::controller(\App\Http\Controllers\Auth\EmailVerificationController::class)->group(function () {
	Route::name('verification.')->prefix('/email')->group(function () {
		Route::middleware('auth')->group(function () {
			Route::get('/verify', 'notice')
                 ->name('notice');
			Route::get('/verify/{id}/{hash}', 'verify')
                 ->middleware('signed')
                 ->name('verify');
			Route::post('/verification-notification', 'send')
                 ->name('send');
		});
	});
});

// 로그인 & 로그아웃
Route::controller(\App\Http\Controllers\Auth\LoginController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLoginForm')
             ->name('login');
        Route::post('/login', 'login');
    });
    Route::post('/logout', 'logout')
         ->name('logout')
         ->middleware('auth');
});

// 소셜 로그인
Route::controller(\App\Http\Controllers\Auth\SocialLoginController::class)->group(function () {
    Route::middleware('guest')->name('login.')->group(function () {
        Route::get('/login/{provider}', 'redirect')
             ->name('social');
        Route::get('/login/{provider}/callback', 'callback')
             ->name('social.callback');
    });
});

// 비밀번호 재설정
Route::controller(\App\Http\Controllers\Auth\PasswordResetController::class)->group(function () {
    Route::middleware('guest')->name('password.')->group(function () {
        Route::get('/forgot-password', 'request')
             ->name('request');
        Route::post('/forgot-password', 'email')
             ->name('email');
        Route::get('/reset-password/{token}', 'reset')
             ->name('reset');
        Route::post('/reset-password', 'update')
             ->name('update');
    });
});

// 비밀번호 확인
Route::controller(\App\Http\Controllers\Auth\PasswordConfirmController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/confirm-password', 'showPasswordConfirmationForm')
             ->name('password.confirm');
        Route::post('/confirm-password', 'confirm');
    });
});

// 마이페이지
Route::singleton('profile', \App\Http\Controllers\Auth\ProfileController::class)
	->middleware('password.confirm');

// 블로그
Route::resource('blogs', \App\Http\Controllers\BlogController::class);

// 구독
Route::controller(\App\Http\Controllers\SubscribeController::class)->group(function () {
    Route::post('subscribe', 'subscribe')
         ->name('subscribe');
    Route::post('unsubscribe', 'unsubscribe')
         ->name('unsubscribe');
});

// 블로그 글
Route::resource('blogs.posts', \App\Http\Controllers\PostController::class)->shallow();

// 댓글
Route::resource('posts.comments', \App\Http\Controllers\CommentController::class)
     ->shallow()
     ->only(['store', 'update', 'destroy']);

// 파일
Route::resource('posts.attachments', \App\Http\Controllers\AttachmentController::class)
     ->shallow()
     ->only(['store', 'destroy']);

// 검색
Route::get('/search', \App\Http\Controllers\SearchController::class)
     ->name('search');