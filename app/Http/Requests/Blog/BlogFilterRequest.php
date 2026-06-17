<?php

namespace App\Http\Requests\Blog;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BlogFilterRequest extends FormRequest
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
            'sort' => 'nullable|string|in:latest,oldest',
            'search' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'tag_id' => 'nullable|exists:tags,id',
        ];
    }
}
