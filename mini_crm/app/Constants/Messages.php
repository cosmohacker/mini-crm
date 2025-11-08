<?php

namespace App\Constants;

class Messages
{
    // Ticket Validation Messages
    public const TICKET_VALDITION_ALREADY_SUBMITTED_TODAY           = 'You have already submitted a ticket today. Only one ticket per day is allowed.';
    public const TICKET_VALDITION_ALREADY_SUBMITTED_TODAY_BY_PHONE  = 'You have already submitted a ticket today using this phone number. Only one ticket per day is allowed per phone number.';
    public const TICKET_VALDITION_ALREADY_SUBMITTED_TODAY_BY_EMAIL  = 'You have already submitted a ticket today using this email address. Only one ticket per day is allowed per email address.';

    // Customer Validation Messages
    public const CUSTOMER_VALIDATION_PHONE_E164_FORMAT = 'The phone number must be in E.164 format (e.g., +1234567890).';

    // Ticket Success Messages
    public const TICKET_STATUS_UPDATED_SUCCESSFULLY = 'Ticket status updated successfully.';

    // Authentication Messages
    public const AUTH_CREDENTIALS_DO_NOT_MATCH = 'The provided credentials do not match our records.';
}

