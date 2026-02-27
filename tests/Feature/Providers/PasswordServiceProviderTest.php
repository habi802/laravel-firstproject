<?php

namespace Tests\Feature\Providers;

use Tests\TestCase;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PasswordServiceProviderTest extends TestCase
{
    // 개발, 프로덕션 모드에 따라 규칙을 다르게 적용.. 했나?
    public function testPasswordRule(): void
    {
        $validator = Validator::make(['password' => 'password'], [
            'password' => Password::default(),
        ]);

        $this->assertTrue($validator->passes());
    }

    public function testPasswordRuleInProduction(): void
    {
        $this->app->bind('env', function () {
            return 'production';
        });

        $this->mock(UncompromisedVerifier::class, function ($mock) {
            $mock->shouldReceive('verify')
                 ->once()
                 ->andReturn(true);
        });

        $validator = Validator::make(['password' => 'password'], [
            'password' => Password::default(),
        ]);

        $this->assertFalse($validator->passes());

        $validator->setData(['password' => 'aA1234**']);

        $this->assertTrue($validator->passes());
    }
}
