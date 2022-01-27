<?php

namespace App\Http\Requests;

use App\Models\Movement;
use Illuminate\Foundation\Http\FormRequest;

class TrashTransmissionMovementRequest extends FormRequest
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
            'issue_warehouse_id' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'type' => Movement::TYPE_TRANSMISSION
        ]);
    }

    public function attributes()
    {
        return [
            'amount' => 'množství',
            'price_level_id' => 'cena',
            'type' => 'typ',
            'user_id' => 'uživatel',
            'product_id' => 'produkt',
            'issue_warehouse_id' => 'sklad',
        ];
    }
}
