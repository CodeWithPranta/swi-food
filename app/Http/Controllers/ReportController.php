<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Show the form to create a new report.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store the report in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_no' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Report::create([
            'user_id' => Auth::id(),
            'order_no' => $request->order_no,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Report submitted successfully.');
    }
}
