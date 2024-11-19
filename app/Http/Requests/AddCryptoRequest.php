<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCryptoRequest extends FormRequest
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
            'name' => ['string', 'required', 'max:225'],
            'code' => ['string', 'required', 'max:225'],
            'exchange_rate' => ['integer', 'required',]
        ];
    }
}
