<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_transforms_user_model_into_an_array()
    {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $userArray = $resource->toArray(request());

        $this->assertEquals($user->id, $userArray['id']);
        $this->assertEquals($user->name, $userArray['name']);
        $this->assertEquals($user->email, $userArray['email']);
        $this->assertEquals($user->created_at->toDateTimeString(), $userArray['created_at']);
        $this->assertEquals($user->updated_at->toDateTimeString(), $userArray['updated_at']);
    }
}
