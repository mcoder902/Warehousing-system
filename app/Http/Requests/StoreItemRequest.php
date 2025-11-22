<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:items,serial_number,' . $this->item?->id,
            'inventory_code' => 'nullable|string|unique:items,inventory_code,' . $this->item?->id,
            'model' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ];
    }
}
