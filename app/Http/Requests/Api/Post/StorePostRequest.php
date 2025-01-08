<?php

namespace App\Http\Requests\Api\Post;

use App\Models\Category;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    use ApiResponse;
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
            'title' => ['required', 'string', 'max:255', 'unique:'.Post::class],
            'text_content' => ['required', 'string'],
            'active' => ['nullable', 'boolean'],
            'categories' => ['required', 'array'],
            'categories.*' => ['exists:categories,id'],
        ];
    }
}
