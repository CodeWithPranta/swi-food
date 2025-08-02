<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FoodController extends Controller
{
    public function foodDetails($id, $name)
    {
        // Retrieve food details by ID
        $food = Food::findOrFail($id);
        $expectedSlug = Str::slug($food->name);
        if ($expectedSlug !== $name) {
            abort(404);
        }
        //dd($food->user_id);
        $vendor = DB::table('vendor_applications')->where('user_id', $food->user_id)->first();
        //dd($vendor);

        // Return the food details view with the food data
        return view('homestaurant.food-details', compact('food', 'vendor'));

        if (!$food) {
            return redirect()->back();
        };
    }
}
