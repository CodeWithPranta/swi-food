<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorApplication;
use App\Models\Profession;

class VendorRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        $professions = Profession::all();
        return view('homestaurant.application', compact('professions'));
    }

    public function submitRegistrationForm(Request $request)
    {
        // Validate the request data
        $request->validate([
            'kitchen_name' => 'required|string|max:255',
            'chef_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'description' => 'required|string',
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'attachments.*' => 'file|max:4096',
            'location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'profession_id' => 'required|exists:professions,id',
            'links' => 'nullable|array',
            'links.*' => 'url',
        ]);


        $coverPhotoPath = $request->file('cover_photo')->store('cover_photos', 'public');

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('attachments', 'public');
            }
        }

        VendorApplication::create([
            'kitchen_name' => $request->kitchen_name,
            'chef_name' => $request->chef_name,
            'phone_number' => $request->phone_number,
            'description' => $request->description,
            'cover_photo' => $coverPhotoPath,
            'attachments' => $attachmentPaths,
            'user_id' => Auth::id(),
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'profession_id' => $request->profession_id,
            'is_approved' => 0,
        ]);

        return redirect()->back()->with('success', 'Your application has been submitted!');
    }
}
