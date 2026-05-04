<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')->whereNull('deleted_at'),
            ],
            'titol' => ['required', 'string', 'max:255'],
            'ordre' => ['required', 'integer', 'min:1', 'max:9999'],
            'contingut' => ['required', 'string', 'max:8000'],
        ];
    }
}
