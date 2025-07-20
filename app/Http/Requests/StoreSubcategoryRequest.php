<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Category;
use Illuminate\Validation\Validator;

class StoreSubcategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'parent_id' => 'required|integer|exists:categories,id',
        ];
    }

     public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $name = $this->input('name');
            $parentId = $this->input('parent_id');

            $exists = Category::where('name', $name)
                ->where('parent_id', $parentId)
                ->exists();

            if ($exists) {
                $validator->errors()->add('name', 'Subcategory with this name already exists under the selected category.');
            }
        });
    }

       public function messages(): array
    {
        return [
            'name.required' => 'Subcategory name is required.',
            'name.string'   => 'Subcategory name must be a valid string.',
            'name.max'      => 'Subcategory name should not exceed 255 characters.',
            'parent_id.required' => 'Parent category is required.',
            'parent_id.integer'  => 'Parent category must be a valid ID.',
            'parent_id.exists'   => 'Selected parent category does not exist.',
        ];
    }
}
