<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class VariantRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'sale' => 'nullable|numeric',
            'old_price' => 'nullable|numeric',
            'new_price' => 'nullable|numeric',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255'
        ];
    }
}
