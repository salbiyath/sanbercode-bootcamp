<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function show()
    {
        $data['user'] = auth()->user();

        // Make response
        $response = [
            'response_code' => '00',
            'response_message' => 'profile berhasil ditampilkan',
            'data' => $data
        ];

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($request->hasFile('picture')) {

            $photo_profile = $request->file('picture');
            $photo_profile_extension = $photo_profile->getClientOriginalExtension();
            $photo_profile_name = Str::slug($user->name, '-') . '-' . $user->id . "." . $photo_profile_extension;
            $photo_profile_folder = '/photos/users/photo-profile/';
            $photo_profile_location = $photo_profile_folder . $photo_profile_name;
            $photo_profile->move(public_path($photo_profile_folder), $photo_profile_name);

            // delete old picture
            if ($user->picture !== null) {
                $old_photo_profile = public_path() . $user->picture;
                unlink($old_photo_profile);
            }

            $user->update([
                'picture' => $photo_profile_location
            ]);
        }

        $user->update([
            'name' => $request->name
        ]);

        $data['user'] = $user;

        // make response
        $response = [
            'response_code' => '00',
            'response_message' => 'Profile berhasil diupdate',
            'data' => [
                'user' => $user
            ]
        ];
        return response()->json($response, 200);
    }
}
