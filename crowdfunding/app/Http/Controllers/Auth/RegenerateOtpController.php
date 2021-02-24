<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegenerateOtpController extends Controller
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
            'email' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        $user->generate_otp_code();

        $data['user'] = $user;

        $response = [
            'response_code' => '00',
            'response_message' => 'Otp berhasil digenerate, silahkan check email untuk melihat code otp',
            'data' => $user
        ];

        return response()->json($response);
    }
}
