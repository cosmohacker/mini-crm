<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

use App\Repositories\TicketRepository;
use App\Models\Ticket;

class TicketService
{
    public function __construct(private TicketRepository $ticketRepository)
    {
    }

    // Finding Ticket By ID
    public function findById($id): ?Ticket
    {
        return $this->ticketRepository->findById($id);
    }
    
    //Create a new ticket with optional image.
    public function create(array $data, ?UploadedFile $image = null)
    {
        $ticket = $this->ticketRepository->create($data);

        if ($image) {
            $ticket->addMedia($image)->toMediaCollection('images');
        }

        return $ticket->load('customer', 'media');
    }

    // Ticket Status Update
    public function updateStatus(Ticket $ticket, string $status)
    {
        $updateData = ['status' => $status];

        // Set manager_response_date when status changes to 'in progress' or 'processed'
        if (in_array($status, ['in progress', 'processed']) && !$ticket->manager_response_date) {
            $updateData['manager_response_date'] = now();
        }

        $this->ticketRepository->update($ticket, $updateData);

        return $ticket->fresh(['customer', 'media']);
    }

    // Get ticket statistics
    public function getStatistics()
    {
        return $this->ticketRepository->getStatistics();
    }

    // Get Filtered Tickets
    public function getFiltered(array $filters = [])
    {
        return $this->ticketRepository->getFiltered($filters);
    }
}

