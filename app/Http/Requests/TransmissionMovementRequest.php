<?php

namespace App\Http\Requests;

use App\Models\Movement;
use Illuminate\Foundation\Http\FormRequest;

class TransmissionMovementRequest extends FormRequest
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
            'issue_warehouse_id' => 'required|integer|different:receipt_warehouse_id',
            'receipt_warehouse_id' => 'required|integer|different:issue_warehouse_id',
            'type' => 'required|string',
            'user_id' => 'nullable|integer',
            'product_id' => 'required|integer',
            'amount' => 'required|numeric|min:0.1',
            'price_level_id' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'type' => Movement::TYPE_TRANSMISSION
        ]);
    }

    public function attributes(): array
    {
        return [
            'issue_warehouse_id' => 'sklad - výdej',
            'receipt_warehouse_id' => 'sklad - příjem',
            'user_id' => 'uživatel',
            'product_id' => 'produkt',
            'price_level_id' => 'cena',
            'amount' => 'množství'
        ];
    }
}
