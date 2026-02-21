<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|string',
            'products' => 'required|array',
            // It will be the same as root warehouse_id
            'products.*.warehouse_id' => 'required|string',
            'products.*.product_id' => 'required|string',
            'products.*.price_level_id' => 'required|string',
            'products.*.amount' => 'required|numeric',
        ];
    }
}
