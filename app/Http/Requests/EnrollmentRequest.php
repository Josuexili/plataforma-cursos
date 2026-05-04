<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnrollmentRequest extends FormRequest
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
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')->whereNull('deleted_at'),
            ],
            'data_inscripcio' => ['nullable', 'date'],
        ];
    }
}
