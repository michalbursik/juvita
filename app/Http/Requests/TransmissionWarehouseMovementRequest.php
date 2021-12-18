<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransmissionWarehouseMovementRequest extends FormRequest
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
            'user_id' => 'nullable|integer',
            'product_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
        ];
    }
}
