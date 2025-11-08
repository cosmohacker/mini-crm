@extends('layouts.layout')

@section('title', 'Ticket Details - ' . config('app.name'))

@section('content')
    <header class="bg-white shadow-md rounded-md w-full text-sm py-4 px-6 mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-500">Ticket Details</h1>
            <a href="{{ route('tickets.index') }}" class="text-blue-600 hover:text-blue-700">
                <i class="ti ti-arrow-left mr-2"></i>Back to Tickets
            </a>
        </div>
    </header>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border text-blue-600 rounded-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Attachments -->
            @if ($ticket->media->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-lg font-semibold text-gray-500 mb-4">Attachments</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($ticket->media as $media)
                                <div class="border border-gray-200 rounded-sm p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            @if (str_starts_with($media->mime_type ?? '', 'image/'))
                                                <i class="ti ti-photo text-lg text-gray-400"></i>
                                            @else
                                                <i class="ti ti-file text-lg text-gray-400"></i>
                                            @endif
                                            <span class="text-sm font-medium text-gray-500">{{ $media->file_name }}</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-400 mb-2">
                                        {{ number_format($media->size / 1024, 2) }} KB
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('file.details', $media->id) }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm">
                                            <i class="ti ti-eye mr-1"></i>View
                                        </a>
                                        <a href="{{ route('file.details', $media->id) }}" download class="text-green-600 hover:text-green-700 text-sm">
                                            <i class="ti ti-download mr-1"></i>Download
                                        </a>
                                    </div>
                                    @if (str_starts_with($media->mime_type ?? '', 'image/'))
                                        <div class="mt-3">
                                            <img src="{{ route('file.details', $media->id) }}" class="object-contain rounded-sm border border-gray-200"  style="width: 450px; height: 400px;">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Information -->
            <div class="card mb-6">
                <div class="card-body">
                    <h2 class="text-lg font-semibold text-gray-500 mb-4">Ticket Information</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Subject</label>
                            <p class="text-gray-500 font-medium">{{ $ticket->subject }}</p>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Message</label>
                            <p class="text-gray-500 whitespace-pre-wrap">{{ $ticket->text }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Status</label>
                                <span class="inline-flex items-center py-1 px-3 rounded-3xl font-semibold text-xs">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Created At</label>
                                <p class="text-gray-500">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>

                        @if ($ticket->manager_response_date)
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Manager Response Date</label>
                                <p class="text-gray-500">{{ $ticket->manager_response_date->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-lg font-semibold text-gray-500 mb-4">Customer Information</h2>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Name</label>
                            <p class="text-gray-500">{{ $ticket->customer->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Email</label>
                            <p class="text-gray-500">{{ $ticket->customer->email ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Phone</label>
                            <p class="text-gray-500">{{ $ticket->customer->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="space-y-6">
            <!-- Update Status -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-lg font-semibold text-gray-500 mb-4">Update Status</h2>

                    <form method="POST" action="{{ route('tickets.updateStatus', $ticket->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="status" class="block text-sm mb-2 text-gray-400">Status</label>
                            <select name="status" id="status"
                                class="py-2 px-4 block w-full border-gray-200 rounded-sm text-sm focus:border-blue-600 focus:ring-0" required>
                                <option value="new" {{ $ticket->status == 'new' ? 'selected' : '' }}>New</option>
                                <option value="in progress" {{ $ticket->status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="processed" {{ $ticket->status == 'processed' ? 'selected' : '' }}>Processed</option>
                            </select>
                        </div>

                        <button type="submit" class="btn py-[10px] px-6 text-base text-white font-medium hover:bg-blue-700 w-full">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
