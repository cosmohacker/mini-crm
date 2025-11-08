@extends('layouts.layout')

@section('title', 'Customers - ' . config('app.name'))

@section('content')

    <header class="bg-white shadow-md rounded-md w-full text-sm py-4 px-6 mb-6">
        <h1 class="text-2xl font-semibold text-gray-500">Customers</h1>
    </header>

    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h4 class="text-gray-500 text-lg font-semibold">Customers List</h4>
            </div>

            <div class="relative overflow-x-auto">
                <table id="customersTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($customers) && $customers->count() > 0)
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            @push('scripts')
                <script>
                    $(document).ready(function() {
                        if ($.fn.DataTable.isDataTable('#customersTable')) {
                            $('#customersTable').DataTable().destroy();
                        }
                        $('#customersTable').DataTable({
                            order: [
                                [3, 'desc']
                            ],
                            pageLength: 15,
                            responsive: true
                        });
                    });
                </script>
            @endpush
        </div>
    </div>

@endsection
