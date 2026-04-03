<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Kakao\KakaoExtendSocialite;
use SocialiteProviders\Naver\NaverExtendSocialite;
// use Illuminate\Support\Facades\Gate;
// use App\Models\User;
// use App\Models\Blog;
use App\Models\Post;
use App\Observers\PostObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            SocialiteWasCalled::class,
            KakaoExtendSocialite::class
        );
    
        Event::listen(
            SocialiteWasCalled::class,
            NaverExtendSocialite::class
        );

        // Gate::before(function (User $user, string $ability) {
        //     if ($user->isAdministrator()) {
        //         return true;
        //     }
        // });
    
        // Gate::define('update-blog', function (User $user, Blog $blog) {
        //     return $user->id === $blog->user_id;
        // });

        Post::observe(PostObserver::class);
    }
}
