<?php

/*
 * This is a small Mini-CRM application I developed.
 * Repository: https://github.com/cosmohacker/mini-crm
 *
 * This file handles ticket creation validation using a Laravel FormRequest.
 * It demonstrates typical backend work I do, including:
 * - Laravel request validation
 * - Business logic implementation
 * - Handling edge cases (duplicate tickets by customer ID, phone, or email)
 * - Clear and maintainable coding style
 *
 * The full repository shows the complete project structure,
 * including models, controllers, routes, Docker setup, and other features.
 */

namespace App\Http\Requests;

use Carbon\Carbon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

use App\Models\Customer;
use App\Models\Ticket;
use App\Constants\Messages;

/**
 * Handles validation for creating new tickets.
 * Ensures that a customer cannot submit more than one ticket per day,
 * and validates basic ticket data such as subject, text, status, and optional image.
 */
class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'   => ['required', 'exists:customers,id'],
            'subject'       => ['required', 'string', 'max:255'],
            'text'          => ['required', 'string'],
            'status'        => ['nullable', 'in:new,in progress,processed'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $customerId = $this->input('customer_id');

            if (!$customerId) {
                return;
            }

            $customer = Customer::find($customerId);

            if (!$customer) {
                return;
            }

            // Check if customer has already created a ticket today
            $today = Carbon::today();

            // First check if this specific customer has already created a ticket today
            $existingTicketByCustomer = Ticket::where('customer_id', $customerId)
                ->whereDate('created_at', $today)
                ->exists();

            if ($existingTicketByCustomer) {
                $validator->errors()->add(
                    'customer_id',
                    Messages::TICKET_VALIDATION_ALREADY_SUBMITTED_TODAY
                );
                return;
            }

            // Check by phone number if phone exists
            if ($customer->phone) {
                $existingTicketByPhone = Ticket::whereHas('customer', function ($query) use ($customer) {
                    $query->where('phone', $customer->phone);
                })
                    ->whereDate('created_at', $today)
                    ->exists();

                if ($existingTicketByPhone) {
                    $validator->errors()->add(
                        'customer_id',
                        Messages::TICKET_VALIDATION_ALREADY_SUBMITTED_TODAY_BY_PHONE
                    );
                    return;
                }
            }

            // Check by email address
            if ($customer->email) {
                $existingTicketByEmail = Ticket::whereHas('customer', function ($query) use ($customer) {
                    $query->where('email', $customer->email);
                })
                    ->whereDate('created_at', $today)
                    ->exists();

                if ($existingTicketByEmail) {
                    $validator->errors()->add(
                        'customer_id',
                        Messages::TICKET_VALIDATION_ALREADY_SUBMITTED_TODAY_BY_EMAIL
                    );
                    return;
                }
            }
        });
    }
}
