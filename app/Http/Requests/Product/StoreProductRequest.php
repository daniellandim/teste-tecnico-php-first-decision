<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string|unique:products,name|max:255',
            'description' => 'nullable|string|max:255',
            'amount'      => 'required|numeric|min:0.01',
            'quantity'    => 'required|integer|min:0',
        ];
    }
}
