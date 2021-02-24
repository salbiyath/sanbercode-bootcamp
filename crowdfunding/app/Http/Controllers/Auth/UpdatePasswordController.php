<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UpdatePasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required|confirmed|min:6'
        ]);

        // 
        // Check user
        // 
        $check = User::where('email', $request->email)->first();

        if ($check === null) {
            // Make response 
            $response = [
                'response_code' => '01',
                'response_message' => 'User tidak ditemukan',
            ];

            return response()->json($response, 200);
        }

        User::where('email', $request->email)
            ->update(['password' => bcrypt(request('password'))]);

        // Make response 
        $response = [
            'response_code' => '00',
            'response_message' => 'Password berhasil dirubah',
        ];

        return response()->json($response, 200);
    }
}
