<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => [
                'required',
                'string',
                Rule::unique('products', 'name')->ignore($this->route('product')),
                'max:255',
            ],
            'description' => 'nullable|string|max:255',
            'amount'      => 'required|numeric|min:0.01',
            'quantity'    => 'required|integer|min:0',
        ];
    }
}
