<?php

namespace App\Http\Requests\Api\Category;

use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        $category = $this->route('category');
        $categoryId = $category ? $category->id : null;
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'active' => ['nullable', 'boolean'],
        ];
    }
}
