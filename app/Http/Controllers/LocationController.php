<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorApplication;
use App\Models\Food;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    // After select location
    public function index()
    {
        $location = '';

        if (auth()->check()) {
            // User is registered, retrieve the location from the database
            $user = auth()->user();
            $locationData = DB::table('user_locations')->where('user_id', $user->id)->first();

            if ($locationData) {
                $location = $locationData->location;
                $latitude = $locationData->latitude;
                $longitude = $locationData->longitude;
            }
        } else {
            // User is not registered, retrieve the location from the session
            $location = session('location', '');
            $latitude = session('latitude', '');
            $longitude = session('longitude', '');
        }


        // Now write code for getting nearby vendors
        $userId = Auth::id();

        if ($userId) {
            // User is logged in, retrieve the user's location from the database
            $userLocation = DB::table('user_locations')
                            ->where('user_id', $userId)
                            ->first();

            if ($userLocation) {
                $userLatitude = $userLocation->latitude;
                $userLongitude = $userLocation->longitude;
            }
        } else {
            // User is not logged in, retrieve the user's location from the session
            $userLatitude = session('latitude');
            $userLongitude = session('longitude');
        }

        if (!$userLatitude || !$userLongitude) {
            return "You have not selected any location from the maps!";
        }

        $latLng = $userLatitude . ',' . $userLongitude;
        $radius = 30; // Set your desired radius value here
        $searchQuery = request('query');

        // When required multiple pagination in the same page
        $nearbyVendors = VendorApplication::with(['foods.category'])
                     ->when($searchQuery, function ($query, $searchQuery) {
                            $query->whereHas('foods', function ($q) use ($searchQuery) {
                                $q->where('name', 'LIKE', '%' . $searchQuery . '%');
                            });
                        })
                     ->where('is_approved', true)->nearby($latLng, $radius)->paginate(4);
        //return dd($nearbyVendors);

        // Calculate and add the distance to the vendor data
        foreach ($nearbyVendors as $vendor) {
            $vendorLatitude = $vendor->latitude;
            $vendorLongitude = $vendor->longitude;

            // Calculate the distance between the user and the vendor using the Haversine formula
            $distance = haversineDistance($userLatitude, $userLongitude, $vendorLatitude, $vendorLongitude);

            // Add the distance to the vendor's data
            $vendor->distance = $distance;
        }

        // Require for condition in blade file
        $numberOfRows = $nearbyVendors->count();

        $allFoodNames = Food::where('is_visible', true)->pluck('name')->unique();


        return view('nearby-homestaurants', compact(['location', 'latitude', 'longitude', 'nearbyVendors', 'numberOfRows', 'allFoodNames']));
    }
    // Show the vendor's menu card

    // public function show($id, $kitchen_name)
    // {
    //     $vendor = VendorApplication::with(['foods.category'])->findOrFail($id);

    //     $expectedSlug = Str::slug($vendor->kitchen_name);
    //     if ($expectedSlug !== $kitchen_name) {
    //         abort(404);
    //     }

    //     // Extract all foods
    //     $vendorFoods = $vendor->foods;

    //     // Build the category mapping: ['Category Name' => category_id]
    //     $vendorFoodCategories = $vendorFoods
    //         ->pluck('category') // Get all categories (some may be duplicate)
    //         ->unique('id') // Remove duplicates by ID
    //         ->mapWithKeys(function ($category) {
    //             return [$category->name => $category->id];
    //         });

    //     return view('homestaurant.menu-card', compact('vendor', 'vendorFoods', 'vendorFoodCategories'));
    // }

    public function show($id, $kitchen_name)
    {
        $vendor = VendorApplication::with(['foods.category'])->findOrFail($id);

        // ✅ Ensure correct slug in the URL
        $expectedSlug = Str::slug($vendor->kitchen_name);
        if ($expectedSlug !== $kitchen_name) {
            abort(404);
        }

        // ✅ Get all foods of the vendor
        $vendorFoods = $vendor->foods;

        // ✅ Build category mapping safely (ignore foods without category)
        $vendorFoodCategories = $vendorFoods
            ->pluck('category')
            ->filter() // remove null categories
            ->unique('id')
            ->mapWithKeys(function ($category) {
                return [$category->name ?? 'Uncategorized' => $category->id ?? 0];
            });

        return view('homestaurant.menu-card', [
            'vendor' => $vendor,
            'vendorFoods' => $vendorFoods,
            'vendorFoodCategories' => $vendorFoodCategories,
        ]);
    }

    
    public function storeOrUpdateLocation(Request $request)
    {
        $userId = auth()->id();

        // // Prevent naughty user to make fake order from different countries
        // // Check if the user has items in the cart
        // $cartItemCount = Cart::where('user_id', $userId)->count();

        // // If the user has items in the cart, prevent updating the location
        // if ($cartItemCount > 0) {
        //     return redirect()->back()->with('message', 'You cannot update the location once food items are added to the plate.');
        // }

        // Store location data in the session if the user is not registered
        if (!$userId) {
            session([
                'location' => $request->input('location'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);
        }

        $locationData = [
            'location' => $request->input('location'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'user_id' => $userId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        if ($userId) {
            // User is registered, retrieve the location from the database
            $existingLocation = DB::table('user_locations')->where('user_id', $userId)->first();

            if ($existingLocation) {
                // Update the existing location
                DB::table('user_locations')->where('user_id', $userId)->update($locationData);
            } else {
                // Insert a new location
                DB::table('user_locations')->insert($locationData);
            }
        }

        return redirect()->route('nearby.homestaurants');
    }

}
