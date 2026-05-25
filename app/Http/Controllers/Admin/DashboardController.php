<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\MediaLibrary;
use App\Models\Performance;
use App\Models\VisitorStat;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'performances' => Performance::query()->count(),
            'blogs' => Blog::query()->count(),
            'media' => MediaLibrary::query()->count(),
            'contacts' => Contact::query()->count(),
            'visits' => VisitorStat::query()->sum('total_visits'),
        ];

        $recentMedia = MediaLibrary::query()->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'recentMedia'));
    }
}
