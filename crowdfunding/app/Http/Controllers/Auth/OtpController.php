<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OtpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email:rfc,dns']
            ]);

            // get user data 
            $user = User::where('email', '=', $request->email)->firstOrFail();

            // Set expired time for OTP
            $valid_until = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime(now())));

            // Store OTP
            $otp_code = OtpCode::where('user_id', '=', $user->id)->update([
                'otp' => rand(0, 100000),
                'valid_until' => $valid_until
            ]);

            // make response
            $response = [
                'response_code' => '00',
                'response_message' => 'OTP yang baru telah dikirim ke email anda!',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            dd($e);
            return response()->json([
                'message' => "Failed $e->errorInfo"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verification(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required'
            ]);

            // get user id 
            $otp_code = OtpCode::firstWhere('otp', $request->otp);

            // check OTP
            if ($otp_code === null) {
                return [
                    'response_code' => '01',
                    'response_message' => 'OTP yang anda masukan salah',
                ];
            }

            // check expired time OTP
            if (now() > $otp_code->valid_until) {
                return [
                    'response_code' => '01',
                    'response_message' => "OTP yang anda masukan expired",
                ];
            }

            // make user account verified
            $user = User::where('id', '=', $otp_code->user_id)->update([
                'email_verified_at' => now()
            ]);

            $user = User::find($otp_code->user_id);

            // make response
            $response = [
                'response_code' => '00',
                'response_message' => 'Berhasil verifikasi',
                'data' => [
                    'user' => $user
                ]
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            dd($e);
            return response()->json([
                'message' => "Failed $e->errorInfo"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
