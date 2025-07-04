<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'name' => 'required|string|max:255|unique:categories,name',
            'category_image' => 'required|nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB max
        ];
    }

     public function messages(): array
    {
        return [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name already exists.',
            'name.max' => 'The category name must not exceed 255 characters.',
            'category_image.image' => 'The file must be an image.',
            'category_image.mimes' => 'Only JPG, JPEG, PNG, and WEBP formats are allowed.',
            'category_image.max' => 'The category image must not be larger than 2MB.',
        ];
    }
}
