<?php

namespace App\Http\Requests;

use App\Enums\CourseLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
            'titol' => ['required', 'string', 'max:255'],
            'descripcio' => ['required', 'string', 'max:4000'],
            'nivell' => ['required', Rule::enum(CourseLevel::class)],
            'durada_hores' => ['required', 'integer', 'min:1', 'max:500'],
        ];
    }
}
