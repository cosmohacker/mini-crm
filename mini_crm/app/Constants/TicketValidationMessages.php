<?php

namespace App\Constants;

class TicketValidationMessages
{
    public const TICKET_VALDITION_ALREADY_SUBMITTED_TODAY = 'You have already submitted a ticket today. Only one ticket per day is allowed.';
    public const TICKET_VALDITION_ALREADY_SUBMITTED_TODAY_BY_PHONE = 'You have already submitted a ticket today using this phone number. Only one ticket per day is allowed per phone number.';
    public const TICKET_VALDITION_ALREADY_SUBMITTED_TODAY_BY_EMAIL = 'You have already submitted a ticket today using this email address. Only one ticket per day is allowed per email address.';
}

