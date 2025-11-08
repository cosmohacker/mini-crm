<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketStatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = is_array($this->resource) ? $this->resource : $this->resource->toArray();

        return [
            'day' => [
                'total'       => $data['day']['total'] ?? 0,
                'new'         => $data['day']['new'] ?? 0,
                'in_progress' => $data['day']['in_progress'] ?? 0,
                'processed'   => $data['day']['processed'] ?? 0,
            ],
            'week' => [
                'total'       => $data['week']['total'] ?? 0,
                'new'         => $data['week']['new'] ?? 0,
                'in_progress' => $data['week']['in_progress'] ?? 0,
                'processed'   => $data['week']['processed'] ?? 0,
            ],
            'month' => [
                'total'       => $data['month']['total'] ?? 0,
                'new'         => $data['month']['new'] ?? 0,
                'in_progress' => $data['month']['in_progress'] ?? 0,
                'processed'   => $data['month']['processed'] ?? 0,
            ],
        ];
    }
}
