<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\LoginRequest;

class LoginRequestTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_validates_login_request()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }

    #[Test]
    public function it_fails_validation_if_email_is_missing()
    {
        $data = [
            'password' => 'password123',
        ];

        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    #[Test]
    public function it_fails_validation_if_password_is_missing()
    {
        $data = [
            'email' => 'test@example.com',
        ];

        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }
}
