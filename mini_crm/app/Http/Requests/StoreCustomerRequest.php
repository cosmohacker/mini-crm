<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Constants\Messages;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^\+[1-9]\d{1,14}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => Messages::CUSTOMER_VALIDATION_PHONE_E164_FORMAT,
        ];
    }
}

