@extends('layouts.layout')

@section('title', 'Users - ' . config('app.name'))

@section('content')
    <header class="bg-white shadow-md rounded-md w-full text-sm py-4 px-6 mb-6">
        <h1 class="text-2xl font-semibold text-gray-500">Users</h1>
    </header>

    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h4 class="text-gray-500 text-lg font-semibold">Users List</h4>
            </div>

            <div class="relative overflow-x-auto">
                <table id="usersTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->count() > 0)
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <span class="font-medium text-gray-500">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="inline-flex items-center py-1 px-3 rounded-3xl font-semibold bg-blue-100 text-blue-600 text-xs">User</span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            @push('scripts')
                <script>
                    $(document).ready(function() {
                        if ($.fn.DataTable.isDataTable('#usersTable')) {
                            $('#usersTable').DataTable().destroy();
                        }
                        $('#usersTable').DataTable({
                            order: [
                                [3, 'desc']
                            ],
                            pageLength: 10,
                            responsive: true
                        });
                    });
                </script>
            @endpush

        </div>
    </div>
@endsection
