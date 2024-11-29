<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyCryptoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'exists:crypto_currencies,name'],
            'code' => ['nullable', 'string', 'exists:crypto_currencies,code'],
            'amount' => ['nullable', 'numeric'],
            'note' => ['string', 'nullable'],
        ];
    }
}
