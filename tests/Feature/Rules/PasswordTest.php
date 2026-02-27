<?php

namespace Tests\Feature\Rules;

use Tests\TestCase;
use App\Rules\Password;
use Illuminate\Support\Facades\Validator;

class PasswordTest extends TestCase
{
    // 비밀번호 유효성 검사 성공 테스트
    public function testAcceptsValidPasswords(): void
    {
        $validator = Validator::make(['password' => 'aA1234**'], [
            'password' => new Password(),
        ]);

        $this->assertTrue($validator->passes());
    }

    // 비밀번호 유효성 검사 실패 테스트
    public function testRejectsInvalidPasswords(): void
    {
        $validator = Validator::make(['password' => 'password'], [
            'password' => new Password(),
        ]);

        $this->assertFalse($validator->passes());
    }
}
