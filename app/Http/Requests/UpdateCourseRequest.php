<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
        $slug = $this->route('slug');

        return [
            'title' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('courses', 'slug')->ignore($slug, 'slug')
            ],
            'description' => 'sometimes|required|string|max:2000',
            'thumbnail_url' => 'sometimes|required|url|max:500',
            'is_published' => 'sometimes|boolean',
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
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'slug.unique' => 'This slug is already taken.',
            'slug.max' => 'The slug may not be greater than 255 characters.',
            'description.required' => 'The description field is required.',
            'description.max' => 'The description may not be greater than 2000 characters.',
            'thumbnail_url.required' => 'The thumbnail URL field is required.',
            'thumbnail_url.url' => 'The thumbnail URL must be a valid URL.',
            'thumbnail_url.max' => 'The thumbnail URL may not be greater than 500 characters.',
        ];
    }
}
