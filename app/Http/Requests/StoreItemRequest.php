<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreItemRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',

            'serial_number' => [
                'required_without:serial_number_auto',
                'nullable',
                'string',
                'unique:items,serial_number'
            ],
            'serial_number_auto' => 'nullable|boolean',

            'inventory_code' => [
                'required_without:inventory_code_auto',
                'nullable',
                'string',
                'unique:items,inventory_code'
            ],
            'inventory_code_auto' => 'nullable|boolean',

            'model' => 'nullable|string|max:100',
            'status' => 'required|in:available,repair',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'serial_number.required_without' => 'لطفاً شماره سریال را وارد کنید یا گزینه تولید خودکار را تیک بزنید.',
            'inventory_code.required_without' => 'لطفاً کد اموال را وارد کنید یا گزینه تولید خودکار را تیک بزنید.',
            'serial_number.unique' => 'این شماره سریال قبلاً ثبت شده است.',
        ];
    }
}
