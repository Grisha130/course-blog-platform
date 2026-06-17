<?php

namespace App\Http\Requests\Blog;

use App\Enums\BlogStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Contracts\Service\Attribute\Required;

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
            'content'  => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'tags'        => 'sometimes|array',
            'tags.*'      => 'exists:tags,id',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'       => ['sometimes', Rule::enum(BlogStatus::class)],
        ];
    }
}
