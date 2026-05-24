<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredWorkshops = TrainingSession::where('session_datetime', '>=', now())
            ->whereIn('status', ['open', 'coming_soon', 'ongoing'])
            ->orderBy('session_datetime')
            ->limit(3)
            ->get();

        $featuredProducts = Product::where('is_active', true)
            ->orderByDesc('added_at')
            ->limit(3)
            ->get();

        return view('home.index', compact('featuredWorkshops', 'featuredProducts'));
    }

    public function about()
    {
        return view('home.about');
    }

    public function services()
    {
        return view('home.services');
    }
}
