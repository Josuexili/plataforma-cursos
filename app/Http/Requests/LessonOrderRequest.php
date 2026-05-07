<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonOrderRequest extends FormRequest
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
            'positions' => ['required', 'array', 'min:1'],
            'positions.*' => ['required', 'integer', 'min:1'],
            'lesson_ids' => ['required', 'array', 'min:1'],
            'lesson_ids.*' => [
                'required',
                'integer',
                Rule::exists('lessons', 'id')->whereNull('deleted_at'),
            ],
        ];
    }
}
