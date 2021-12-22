<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseMovementRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0.1',
            'price' => 'required|numeric|min:0.1',
            'type' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'product_id' => 'required|integer',
            'warehouse_id' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'amount' => 'množství',
            'type' => 'typ',
            'user_id' => 'uživatel',
            'product_id' => 'produkt',
            'warehouse_id' => 'sklad',
        ];
    }
}
