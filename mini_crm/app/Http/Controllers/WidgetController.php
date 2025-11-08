<?php

namespace App\Http\Controllers;

class WidgetController extends Controller
{
    public function index()
    {
        $response = response()->view('widgets.index');
        $response->headers->remove('X-Frame-Options');

        return $response;
    }
}
