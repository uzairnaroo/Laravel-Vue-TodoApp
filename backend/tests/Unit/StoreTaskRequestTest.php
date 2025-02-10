<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTaskRequest;

class StoreTaskRequestTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_validates_store_task_request()
    {
        $data = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'due_date' => now()->addDays(1)->toDateString(),
            'priority' => 'Normal',
            'tags' => ['tag1', 'tag2'],
            'attachments' => [],
        ];

        $request = new StoreTaskRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }

    #[Test]
    public function it_fails_validation_if_title_is_missing()
    {
        $data = [
            'description' => 'Task Description',
        ];

        $request = new StoreTaskRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }
}
