<?php

namespace App\Http\Requests;

use App\Models\Movement;
use Illuminate\Foundation\Http\FormRequest;

class ReceiveProductRequest extends FormRequest
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
            'product_uuid' => 'required|string',
            'amount' => 'required|numeric|min:0.1',
            'price' => 'nullable|numeric', // receipt => price, issue => id of price level
//            'type' => 'required|string',
//            'user_id' => 'nullable|integer',
//            'product_id' => 'required|integer',
//            'receipt_warehouse_id' => 'required|integer',
        ];
    }

//    protected function prepareForValidation()
//    {
//        $this->merge([
//           'type' => Movement::TYPE_RECEIPT
//        ]);
//    }

    public function attributes()
    {
        return [
            'amount' => 'množství',
            'price' => 'cena',
//            'type' => 'typ',
//            'user_id' => 'uživatel',
//            'product_id' => 'produkt',
//            'receipt_warehouse_id' => 'sklad',
        ];
    }
}
