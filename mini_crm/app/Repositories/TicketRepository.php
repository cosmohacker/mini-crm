<?php

namespace App\Repositories;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class TicketRepository
{
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    public function update(Ticket $ticket, array $data): bool
    {
        return $ticket->update($data);
    }

    public function findById(int $id): ?Ticket
    {
        return Ticket::with(['customer', 'media'])->find($id);
    }

    public function getFiltered(array $filters = []): Collection
    {
        $query = Ticket::with('customer');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['email'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('email', 'like', '%' . $filters['email'] . '%');
            });
        }

        if (isset($filters['phone'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('phone', 'like', '%' . $filters['phone'] . '%');
            });
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->get();
    }

    public function getTodayTickets(): Collection
    {
        return Ticket::today()->get();
    }

    public function getThisWeekTickets(): Collection
    {
        return Ticket::thisWeek()->get();
    }

    public function getThisMonthTickets(): Collection
    {
        return Ticket::thisMonth()->get();
    }

    public function getStatistics(): array
    {
        $today     = Ticket::today()->get();
        $thisWeek  = Ticket::thisWeek()->get();
        $thisMonth = Ticket::thisMonth()->get();

        return [
            'day' => [
                'total'       => $today->count(),
                'new'         => $today->where('status', 'new')->count(),
                'in_progress' => $today->where('status', 'in progress')->count(),
                'processed'   => $today->where('status', 'processed')->count(),
            ],
            'week' => [
                'total'       => $thisWeek->count(),
                'new'         => $thisWeek->where('status', 'new')->count(),
                'in_progress' => $thisWeek->where('status', 'in progress')->count(),
                'processed'   => $thisWeek->where('status', 'processed')->count(),
            ],
            'month' => [
                'total'       => $thisMonth->count(),
                'new'         => $thisMonth->where('status', 'new')->count(),
                'in_progress' => $thisMonth->where('status', 'in progress')->count(),
                'processed'   => $thisMonth->where('status', 'processed')->count(),
            ],
        ];
    }
}

