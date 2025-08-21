<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCourseModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => [
                'required',
                'string',
                'ulid',
                Rule::exists('courses', 'id'),
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'order' => [
                'required',
                'integer',
                'min:1',
            ],
            'is_published' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'course_id.required' => 'Course ID is required.',
            'course_id.exists' => 'The selected course does not exist.',
            'title.required' => 'Module title is required.',
            'title.max' => 'Module title cannot exceed 255 characters.',
            'order.required' => 'Module order is required.',
            'order.integer' => 'Module order must be a number.',
            'order.min' => 'Module order must be at least 1.',
        ];
    }
}
