<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'code' => [
                'required',
                'exists:crypto_currencies,code',
                Rule::exists('crypto_wallets', 'code')->where('user_id', auth()->id()),
            ],
            'note' => ['string', 'nullable'],
        ];
    }
    public function messages()
    {
        return [
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'code.required' => 'The cryptocurrency code is required.',
            'code.exists' => 'The selected cryptocurrency code does not exist in the available cryptocurrencies.',
            'code.exists' => 'The selected cryptocurrency is not in your wallet.',
            'note.string' => 'The note must be a valid string.',
        ];
    }
}
