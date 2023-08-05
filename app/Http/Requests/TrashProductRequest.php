<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrashProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'warehouse_product_uuid' => 'required|string',
            'product_uuid' => 'required|string',
            'price_uuid' => 'required|string',
            'amount' => 'required|numeric|min:0.1',
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse_product_uuid' => 'produkt skladu',
            'product_uuid' => 'produkt',
            'price_uuid' => 'cena',
            'amount' => 'množství',
        ];
    }
}
