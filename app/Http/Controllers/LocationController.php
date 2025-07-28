<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorApplication;

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
        $radius = 20; // Set your desired radius value here

        // When required multiple pagination in the same page
        $nearbyVendors = VendorApplication::where('is_approved', true)->nearby($latLng, $radius)->paginate(4);
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

        return view('nearby-homestaurants', compact(['location', 'latitude', 'longitude', 'nearbyVendors', 'numberOfRows']));
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
