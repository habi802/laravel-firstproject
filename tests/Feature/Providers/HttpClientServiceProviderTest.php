<?php

namespace Tests\Feature\Providers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Request;

class HttpClientServiceProviderTest extends TestCase
{
    public function testApiMacro()
    {
        Http::fake();

        Http::api('')->get('/');

        Http::assertSent(function (Request $request) {
            return $request->url() === config('app.url').'/api'.'/'
                && $request->hasHeader('Authorization')
                && $request->isJson();
        });
    }
}
