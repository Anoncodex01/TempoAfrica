<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;

class AccommodationController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::with(['photos', 'rooms', 'country'])->get();
        return view('dashboards.accommodations', compact('accommodations'));
    }
} 