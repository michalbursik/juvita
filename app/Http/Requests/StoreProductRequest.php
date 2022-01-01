<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string',
            'origin' => 'nullable|string',
            'order' => 'required|integer',
            'active' => 'required|boolean',
            'unit' => 'required|string|in:' . Product::getListOfAvailableUnits(),
        ];
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'active' => $this->active ? true : false
        ]);
    }

    public function attributes(): array
    {
        return [
            'name' => 'název',
            'origin' => 'původ',
            'order' => 'pořadí',
            'active' => 'aktivní',
            'unit' => 'jednotka',
        ];
    }
}
