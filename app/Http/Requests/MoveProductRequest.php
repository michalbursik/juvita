<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoveProductRequest extends FormRequest
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
            'source_warehouse_uuid' => 'required|string|different:receipt_warehouse_id',
            'target_warehouse_uuid' => 'required|string|different:issue_warehouse_id',
            'product_uuid' => 'required|string',
            'price_uuid' => 'required|string',
            'amount' => 'required|numeric|min:0.1',
        ];
    }

    public function attributes(): array
    {
        return [
            'source_warehouse_uuid' => 'sklad - výdej',
            'target_warehouse_uuid' => 'sklad - příjem',
            'product_uuid' => 'produkt',
            'price' => 'cena',
            'amount' => 'množství'
        ];
    }
}
