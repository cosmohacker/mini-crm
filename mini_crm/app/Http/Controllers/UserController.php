<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->route('tickets.index');
        }

        $users = User::with('roles')->latest()->get();
        return view('admin.users.index', compact('users'));
    }
}
