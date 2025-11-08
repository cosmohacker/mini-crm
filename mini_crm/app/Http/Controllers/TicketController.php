<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateTicketStatusRequest;
use App\Services\TicketService;
use App\Constants\Messages;

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

        return view('admin.tickets.details', compact('ticket'));
    }

    public function updateStatus($id, UpdateTicketStatusRequest $request)
    {
        // Only managers can update ticket status
        if (!auth()->user()->hasRole('manager')) {
            return redirect()->route('tickets.details', $id)->with('error', Messages::TICKET_STATUS_UPDATE_FORBIDDEN);
        }

        $ticket = $this->ticketService->findById($id);

        if (!$ticket) {
            abort(404);
        }

        $this->ticketService->updateStatus($ticket, $request->validated()['status']);

        return redirect()->route('tickets.details', $id)->with('success', Messages::TICKET_STATUS_UPDATED_SUCCESSFULLY);
    }
}
