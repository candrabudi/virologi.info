<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\Website;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUs::where('is_active', true)->firstOrFail();
        $website = Website::first();

        return view('about.index', compact('about', 'website'));
    }
}
