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
            'price' => 'nullable|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'amount' => 'množství',
            'price' => 'cena',
            'product_uuid' => 'produkt',
            'warehouse_uuid' => 'sklad',
        ];
    }
}
