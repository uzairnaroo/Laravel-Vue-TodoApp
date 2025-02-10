<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateTaskRequest;

class UpdateTaskRequestTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_validates_update_task_request()
    {
        $data = [
            'title' => 'Updated Task Title',
            'description' => 'Updated Description',
            'due_date' => now()->addDays(1)->toDateString(),
            'priority' => 'High',
            'tags' => ['tag1', 'tag2'],
            'existing_attachments' => [1, 2],
            'attachments' => [],
        ];

        $request = new UpdateTaskRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }

    #[Test]
    public function it_fails_validation_if_due_date_is_in_the_past()
    {
        $data = [
            'due_date' => now()->subDays(1)->toDateString(),
        ];

        $request = new UpdateTaskRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('due_date', $validator->errors()->toArray());
    }
}
