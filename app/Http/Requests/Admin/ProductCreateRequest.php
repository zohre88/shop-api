<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id'=>'required|exists:categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'name' => 'required|string',
            'slug' =>  'required|unique:products,slug',
            'image' => 'required|image|mimes:png,jpg,jpeg,svg',
            'description' => 'required|string' ,
            'price' => 'required|integer',
            'quantity' => 'required|integer'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'validation error',
            'data' => $validator->errors(),
        ]));
    }

    // public function messages()
    // {
    //     return [
    //         'name.required' => 'فیلد مورد نظر الزامی است'
    //     ];
    // }
}
