<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketStatisticsResource;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketApiController extends Controller
{
    public function __construct(private TicketService $ticketService) {}

    // Store a newly created ticket
    public function store(StoreTicketRequest $request): JsonResponse
    {
        $data   = $request->validated();
        $image  = $request->file('image');

        $ticket = $this->ticketService->create($data, $image);

        return (new TicketResource($ticket))->response()->setStatusCode(201);
    }

    // Get ticket statistics
    public function statistics(): JsonResponse
    {
        $statistics = $this->ticketService->getStatistics();

        return (new TicketStatisticsResource($statistics))->response();
    }
}

