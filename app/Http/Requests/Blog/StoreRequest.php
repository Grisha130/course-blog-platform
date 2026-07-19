<?php

namespace App\Http\Requests\Blog;

use App\Enums\BlogStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id'=>'required|exists:categories,id',
            'tags'=>'required|array',
            'tags.*'=>'exists:tags,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => ['sometimes', Rule::enum(BlogStatus::class)],
        ];
    }
}
