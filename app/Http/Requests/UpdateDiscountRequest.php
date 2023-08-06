<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
            'warehouse_uuid' => 'required|string',
            'user_uuid' => 'required|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_uuid' => auth()->user()->uuid
        ]);
    }

    public function attributes(): array
    {
        return [
            'amount' => 'množství',
            'note' => 'poznámka',
            'warehouse_uuid' => 'sklad',
            'user_uuid' => 'uživatel',
        ];
    }
}
