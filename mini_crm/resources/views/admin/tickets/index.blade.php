@extends('layouts.layout')

@section('title', 'Tickets - ' . config('app.name'))

@section('content')
    <header class="bg-white shadow-md rounded-md w-full text-sm py-4 px-6 mb-6">
        <h1 class="text-2xl font-semibold text-gray-500">Tickets</h1>
    </header>

    <!-- Ticket Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Today Statistics -->
        <div class="card">
            <div class="card-body">
                <h4 class="text-gray-500 text-lg font-semibold mb-4">Today's Tickets</h4>
                <div id="today-stats" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Total:</span>
                        <span class="font-semibold text-gray-700" id="today-total">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">New:</span>
                        <span class="font-semibold text-blue-600" id="today-new">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">In Progress:</span>
                        <span class="font-semibold text-yellow-600" id="today-in-progress">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Processed:</span>
                        <span class="font-semibold text-green-600" id="today-processed">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Week Statistics -->
        <div class="card">
            <div class="card-body">
                <h4 class="text-gray-500 text-lg font-semibold mb-4">This Week's Tickets</h4>
                <div id="week-stats" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Total:</span>
                        <span class="font-semibold text-gray-700" id="week-total">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">New:</span>
                        <span class="font-semibold text-blue-600" id="week-new">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">In Progress:</span>
                        <span class="font-semibold text-yellow-600" id="week-in-progress">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Processed:</span>
                        <span class="font-semibold text-green-600" id="week-processed">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month Statistics -->
        <div class="card">
            <div class="card-body">
                <h4 class="text-gray-500 text-lg font-semibold mb-4">This Month's Tickets</h4>
                <div id="month-stats" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Total:</span>
                        <span class="font-semibold text-gray-700" id="month-total">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">New:</span>
                        <span class="font-semibold text-blue-600" id="month-new">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">In Progress:</span>
                        <span class="font-semibold text-yellow-600" id="month-in-progress">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Processed:</span>
                        <span class="font-semibold text-green-600" id="month-processed">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h4 class="text-gray-500 text-lg font-semibold">Tickets List</h4>
            </div>

            <!-- Filters -->
            <div class="card mb-5 bg-gray-50">
                <div class="card-body">
                    <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm mb-2 text-gray-400">Status</label>
                            <select name="status" id="status" class="py-2 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0">
                                <option value="">All Statuses</option>
                                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                                <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                            </select>
                        </div>

                        <!-- Email Filter -->
                        <div>
                            <label for="email" class="block text-sm mb-2 text-gray-400">Email</label>
                            <input type="email" name="email" id="email" value="{{ request('email') }}"
                                class="py-2 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0" placeholder="Customer email">
                        </div>

                        <!-- Phone Filter -->
                        <div>
                            <label for="phone" class="block text-sm mb-2 text-gray-400">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ request('phone') }}"
                                class="py-2 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0" placeholder="Customer phone">
                        </div>

                        <!-- Date From Filter -->
                        <div>
                            <label for="date_from" class="block text-sm mb-2 text-gray-400">Date From</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                class="py-2 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0">
                        </div>

                        <!-- Date To Filter -->
                        <div>
                            <label for="date_to" class="block text-sm mb-2 text-gray-400">Date To</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                class="py-2 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0">
                        </div>

                        <!-- Filter Buttons -->
                        <div class="md:col-span-2 lg:col-span-5 flex gap-2">
                            <button type="submit" class="btn hover:bg-blue-700">
                                Apply Filters
                            </button>
                            <a href="{{ route('tickets.index') }}" class="btn-outline-primary hover:bg-blue-50">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tickets Table -->
            <div class="relative overflow-x-auto">
                <table id="ticketsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($tickets->count() > 0)
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>{{ $ticket->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $ticket->customer->email ?? 'N/A' }}</td>
                                    <td>{{ $ticket->customer->phone ?? 'N/A' }}</td>
                                    <td>
                                        <span class="inline-flex items-center py-1 px-3 rounded-3xl font-semibold text-xs">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('tickets.details', $ticket->id) }}" class="text-blue-600 hover:text-blue-700" title="View">
                                                <i class="ti ti-eye text-lg"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Load ticket statistics
            fetch('/api/tickets/statistics')
                .then(response => response.json())
                .then(data => {
                    const stats = data.data || data;
                    
                    // Today's statistics
                    $('#today-total').text(stats.day?.total || 0);
                    $('#today-new').text(stats.day?.new || 0);
                    $('#today-in-progress').text(stats.day?.in_progress || 0);
                    $('#today-processed').text(stats.day?.processed || 0);
                    
                    // This week's statistics
                    $('#week-total').text(stats.week?.total || 0);
                    $('#week-new').text(stats.week?.new || 0);
                    $('#week-in-progress').text(stats.week?.in_progress || 0);
                    $('#week-processed').text(stats.week?.processed || 0);
                    
                    // This month's statistics
                    $('#month-total').text(stats.month?.total || 0);
                    $('#month-new').text(stats.month?.new || 0);
                    $('#month-in-progress').text(stats.month?.in_progress || 0);
                    $('#month-processed').text(stats.month?.processed || 0);
                })
                .catch(error => {
                    console.error('Error loading statistics:', error);
                });

            // Destroy existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('#ticketsTable')) {
                $('#ticketsTable').DataTable().destroy();
            }
            
            // Initialize DataTable
            $('#ticketsTable').DataTable({
                order: [[6, 'desc']],
                pageLength: 10,
                responsive: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ tickets",
                    infoEmpty: "No tickets available",
                    infoFiltered: "(filtered from _MAX_ total tickets)"
                }
            });
        });
    </script>
    @endpush
@endsection
