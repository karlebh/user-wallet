<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetFiatRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'exists:currencies,name'],
            'code' => ['required', 'string', 'exists:currencies,code'],
        ];
    }
}
