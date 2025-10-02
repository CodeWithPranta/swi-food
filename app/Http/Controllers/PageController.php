<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    // Controller methods can be added here as needed
    public function pageView($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('page-view', compact('page')); // Replace 'page-view' with your actual view name
    }
}
