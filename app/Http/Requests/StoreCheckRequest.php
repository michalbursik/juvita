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
            'warehouse_uuid' => 'required|string',
            'products' => 'required|array',
            // It will be the same as root warehouse_id
//            'products.*.warehouse_uuid' => 'required|integer', // replace with warehouse_product_uuid
//            'products.*.product_uuid' => 'required|integer', // replace with warehouse_product_uuid

            'products.*.warehouse_product_uuid' => 'required|string',
            'products.*.price_uuid' => 'required|string',
            'products.*.amount' => 'required|numeric',
        ];
    }
}
