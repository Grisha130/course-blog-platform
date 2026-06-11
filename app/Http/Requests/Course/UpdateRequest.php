<?php

namespace App\Http\Requests\Course;

use App\Enums\CourseStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => 'sometimes|string|max:255',
            'description'  => 'sometimes|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price'        => 'sometimes|numeric|min:0',
            'status'       => ['sometimes', Rule::enum(CourseStatus::class)],
        ];
    }
}
