<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Middleware\Locale;
use Illuminate\Http\Request;
use Illuminate\Contracts\Session\Session;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function testLocaleChangeWithAcceptLanguageHeader()
    {
        $this->assertTrue(app()->isLocale('ko'));

        $localeMiddleware = app(Locale::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));
        $request->header('Accept-Language', 'en');

        $localeMiddleware->handle($request, function () {
            $this->assertTrue(app()->isLocale('en'));

            return response()->noContent();
        });
    }
}
