<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VerificationController extends Controller
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
            'otp' => 'required'
        ]);

        $otp_code = OtpCode::where('otp', $request->otp)->first();

        if (!$otp_code) {
            $response = [
                'response_code' => '01',
                'response_message' => 'Kode OTP tidak ditemukan',

            ];

            return response()->json($response, 200);
        }

        $now = Carbon::now();

        if ($now > $otp_code->valid_until) {
            $response = [
                'response_code' => '01',
                'response_message' => 'Kode OTP sudah tidak berlaku',

            ];

            return response()->json($response, 200);
        }

        // update user
        $user = User::find($otp_code->user_id);
        $user->email_verified_at = Carbon::now();
        $user->save();

        //delete otp
        $otp_code->delete();

        $data['user'] = $user;

        // Create response
        $response = [
            'response_code' => '00',
            'response_message' => "User berhasil diverifikasi",
            'data' => $data
        ];

        return response()->json($response, 200);
    }
}
