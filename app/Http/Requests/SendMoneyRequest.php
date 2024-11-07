<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMoneyRequest extends FormRequest
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
        return
            [
                'reciever_id' => ['required'],
                'amount' => ['required', 'min:1', 'max:2000000'],
                'currency' => ['nullable', 'string'],
                'note' => ['nullable', 'string'],
            ];
    }
}
