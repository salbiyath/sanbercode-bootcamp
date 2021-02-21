<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\otpCode;
use App\Models\User;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        try {
            // Store new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Set expired time for OTP
            $valid_until = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime(now())));

            // Store OTP
            $otp_code = otpCode::create([
                'otp' => rand(0, 100000),
                'user_id' => $user->id,
                'valid_until' => $valid_until
            ]);

            // make response
            $response = [
                'response_code' => '00',
                'response_message' => 'Register success',
                'data' => ['user' => $user]
            ];

            // send response
            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            dd($e);
            return response()->json([
                'message' => "Failed $e->errorInfo"
            ]);
        }
    }
}
