<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
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
         
            'name'      => 'required|array|min:1',
            'name.*'    => 'required|string|max:255',
            'name.*' => [
            'required',
            'string',
            'max:255',
            Rule::unique('brands', 'name'),
        ],
            'description'     => 'required|nullable|array',
            'description.*'   => 'required|nullable|string',

            // 'product_image'   => 'required|nullable|array',
            // 'product_image.*' => 'required|nullable|mimes:.jpg,.jpeg,.png,.webp|max:2048',
       
        ];
    }

      public function messages()
    {
        return [
            'name.*.required'     => 'Please enter brand.',
             'name.*.unique' => 'Brand name ":input" already exists.',
        //     'product_image.*.required'     => 'Please choose brand logo.',
        //   //  'product_image.*.image'     => 'Product image must be a valid image file.',
        //     'product_image.*.mimes'     => 'Images must be in jpg, jpeg, png, or webp format.',
        //     'product_image.*.max'       => 'Image size must not exceed 2MB.',
            'description.*.required'      =>'Please enter description.',
            'description.*.string'      => 'Description must be a valid string.',
            'description.*.max'         => 'Description may not be greater than 1000 characters.',
        ];
    }
}
