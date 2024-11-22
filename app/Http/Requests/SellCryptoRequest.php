<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellCryptoRequest extends FormRequest
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
            'amount' => ['numeric', 'required'],
            'code' => ['required', 'exists:crypto_currencies,code'],
            'note' => ['string', 'nullable'],
        ];
    }
}
