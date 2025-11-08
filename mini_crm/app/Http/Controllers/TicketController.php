<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateTicketStatusRequest;
use App\Services\TicketService;

class TicketController extends Controller
{

    public function __construct(private TicketService $ticketService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'email', 'phone', 'date_from', 'date_to']);
        $tickets = $this->ticketService->getFiltered($filters);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function details($id)
    {
        $ticket = $this->ticketService->findById($id);

        if (!$ticket) {
            abort(404);
        }

        return view('admin.tickets.details', compact('id'));
    }

    public function updateStatus($id, UpdateTicketStatusRequest $request)
    {
        $ticket = $this->ticketService->findById($id);

        if (!$ticket) {
            abort(404);
        }

        $this->ticketService->updateStatus($ticket, $request->validated()['status']);

        return redirect()->route('tickets.show', $id)->with('success', 'Ticket status updated successfully.');
    }
}
