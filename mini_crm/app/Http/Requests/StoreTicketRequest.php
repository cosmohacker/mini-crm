<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'subject'     => ['required', 'string', 'max:255'],
            'text'        => ['required', 'string'],
            'status'      => ['nullable', 'in:new,in progress,processed'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ];
    }
}

