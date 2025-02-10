<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

     public function rules()
        {
            return [
                'title' => 'string|max:255',
                'description' => 'string',
                'due_date' => 'date|after_or_equal:today',
                'priority' => 'in:Urgent,High,Normal,Low',
                'tags' => 'array',
                'tags.*' => 'string|max:50',
                'existing_attachments' => 'array',
                'existing_attachments.*' => 'integer|exists:attachments,id',
                'attachments' => 'array',
                'attachments.*' => 'file|mimes:jpeg,png,jpg,svg,mp4,csv,txt,doc,docx|max:2048',
            ];
        }



}
