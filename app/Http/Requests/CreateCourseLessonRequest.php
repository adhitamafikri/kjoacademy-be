<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCourseLessonRequest extends FormRequest
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
            'course_module_id' => [
                'required',
                'string',
                'ulid',
                Rule::exists('course_modules', 'id'),
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
            'lesson_type' => [
                'required',
                'string',
                Rule::in(['video', 'text', 'quiz', 'assignment', 'download']),
            ],
            'lesson_content_url' => [
                'required',
                'string',
                'max:1000',
                'url',
            ],
            'duration_seconds' => [
                'required',
                'integer',
                'min:0',
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
            'course_module_id.required' => 'Course module ID is required.',
            'course_module_id.exists' => 'The selected course module does not exist.',
            'title.required' => 'Lesson title is required.',
            'title.max' => 'Lesson title cannot exceed 255 characters.',
            'order.required' => 'Lesson order is required.',
            'order.integer' => 'Lesson order must be a number.',
            'order.min' => 'Lesson order must be at least 1.',
            'lesson_type.required' => 'Lesson type is required.',
            'lesson_type.in' => 'Lesson type must be one of: video, text, quiz, assignment, download.',
            'lesson_content_url.required' => 'Lesson content URL is required.',
            'lesson_content_url.url' => 'Lesson content URL must be a valid URL.',
            'lesson_content_url.max' => 'Lesson content URL cannot exceed 1000 characters.',
            'duration_seconds.required' => 'Lesson duration is required.',
            'duration_seconds.integer' => 'Lesson duration must be a number.',
            'duration_seconds.min' => 'Lesson duration must be at least 0 seconds.',
        ];
    }
}
