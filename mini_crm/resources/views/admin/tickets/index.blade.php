@extends('layouts.layout')

@section('title', 'Tickets - ' . config('app.name'))

@section('content')

    <header class="bg-white shadow-md rounded-md w-full text-sm py-4 px-6 mb-6">
        <h1 class="text-2xl font-semibold text-gray-500">Tickets</h1>
    </header>

    <div class="card">
        <div class="card-body">
            <div class="flex justify-between items-center mb-5">
                <h4 class="text-gray-500 text-lg font-semibold">Tickets List</h4>
            </div>
        </div>
    </div>
    
@endsection
