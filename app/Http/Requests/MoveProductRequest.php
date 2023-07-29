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
            // 'type' => 'required|string',
            // 'user_id' => 'nullable|integer',
            'product_uuid' => 'required|string',
            'amount' => 'required|numeric|min:0.1',
            'price' => 'required|numeric',
        ];
    }

//    protected function prepareForValidation()
//    {
//        $this->merge([
//            'type' => Movement::TYPE_TRANSMISSION
//        ]);
//    }

    public function attributes(): array
    {
        return [
            'source_warehouse_uuid' => 'sklad - výdej',
            'target_warehouse_uuid' => 'sklad - příjem',
            // 'user_id' => 'uživatel',
            'product_uuid' => 'produkt',
            'price' => 'cena',
            'amount' => 'množství'
        ];
    }
}
