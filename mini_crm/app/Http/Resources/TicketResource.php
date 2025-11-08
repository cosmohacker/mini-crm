<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'customer_id'           => $this->customer_id,
            'customer'              => new CustomerResource($this->whenLoaded('customer')),
            'subject'               => $this->subject,
            'text'                  => $this->text,
            'status'                => $this->status,
            'manager_response_date' => $this->manager_response_date?->toISOString(),
            'created_at'            => $this->created_at?->toISOString(),
            'updated_at'            => $this->updated_at?->toISOString(),
            'media'                 => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}

