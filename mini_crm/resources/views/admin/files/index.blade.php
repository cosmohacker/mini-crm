@extends('layouts.layout')

@section('title', 'File Management - ' . config('app.name'))

@section('content')
    <header class="bg-white shadow-md rounded-md w-full text-sm py-4 px-6 mb-6">
        <h1 class="text-2xl font-semibold text-gray-500">File Management</h1>
    </header>

    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h4 class="text-gray-500 text-lg font-semibold">Files</h4>
            </div>

            <div class="relative overflow-x-auto">
                <table id="filesTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Ticket</th>
                            <th>Customer</th>
                            <th>Uploaded At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($files) && $files->count() > 0)
                            @foreach($files as $file)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @if(isset($file['is_image']) && $file['is_image'])
                                                <img src="{{ $file['url'] ?? '#' }}"
                                                     class="w-10 h-10 object-cover rounded border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                                                     onclick="window.open('{{ $file['url'] ?? '#' }}', '_blank')" loading="lazy">
                                            @else
                                                <i class="ti ti-file text-lg text-gray-400"></i>
                                            @endif
                                            <span class="font-medium text-gray-500">{{ $file['file_name'] ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-500">{{ $file['ticket_subject'] ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-500">{{ $file['customer_name'] ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-500">
                                            {{ isset($file['created_at']) ? $file['created_at'] : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ $file['url'] ?? '#' }}" target="_blank" class="text-blue-600 hover:text-blue-700" title="View">
                                                <i class="ti ti-eye text-lg"></i>
                                            </a>
                                            <a href="{{ $file['url'] ?? '#' }}" download class="text-green-600 hover:text-green-700" title="Download">
                                                <i class="ti ti-download text-lg"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            @push('scripts')
            <script>
                $(document).ready(function() {
                    if ($.fn.DataTable.isDataTable('#filesTable')) {
                        $('#filesTable').DataTable().destroy();
                    }
                    $('#filesTable').DataTable({
                        order: [[3, 'desc']],
                        pageLength: 15,
                        responsive: true
                    });
                });
            </script>
            @endpush
        </div>
    </div>
@endsection

