<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Category;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

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
  public function rules()
{
    $id = $this->route('subcategory'); // gets the id from route like subcategory.update/{id}
    return [
        'parent_id' => 'required|exists:categories,id',
        'name' => [
            'required',
            'string',
            'unique:categories,id',
            Rule::unique('categories')->where(function ($query) {
                return $query->where('parent_id', $this->parent_id);
            })->ignore($id),
        ],
    ];
}

}
