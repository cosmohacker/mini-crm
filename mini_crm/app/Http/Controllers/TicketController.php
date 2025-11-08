<?php

namespace App\Http\Controllers;

class TicketController extends Controller
{
    public function index()
    {
        return view('admin.tickets.index');
    }

    public function details($id)
    {
        return view('admin.tickets.details', compact('id'));
    }

    public function updateStatus() {
        
    }
}
