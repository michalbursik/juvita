<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueWarehouseMovementRequest extends FormRequest
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
            'price_level_id' => 'required|integer',
            'type' => 'required|string',
            'user_id' => 'nullable|integer',
            'product_id' => 'required|integer',
            'warehouse_id' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'amount' => 'množství',
            'price_level_id' => 'cena',
            'type' => 'typ',
            'user_id' => 'uživatel',
            'product_id' => 'produkt',
            'warehouse_id' => 'sklad',
        ];
    }
}
