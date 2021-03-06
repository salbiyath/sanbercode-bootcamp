<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
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
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        try {

            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['Error' => 'Unauthorized']);
            }

            $user = User::where('email', $request->email)->first();

            // make response
            $response = [
                'response_code' => '00',
                'response_message' => 'Berhasil Login',
                'data' => [
                    'token' => $token,
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
}
