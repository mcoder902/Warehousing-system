<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return Auth::User()->name === "admin2";
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $itemId = $this->route('item')->id;


        return [
            'name' => 'required|string|max:255',

            'serial_number' => [
                'nullable',
                'string',
                Rule::unique('items', 'serial_number')->ignore($this->route('item')),
            ],

            'inventory_code' => [
                'nullable',
                'string',
                'unique:items,inventory_code,' . $itemId
            ],

            'model' => 'nullable|string|max:100',
            'status' => 'required|in:available,repair',
            'description' => 'nullable|string',
        ];
    }
}
