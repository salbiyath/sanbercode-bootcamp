<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['updatePassword']]);
    }

    // /**
    //  * Handle the incoming request.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function __invoke(Request $request)
    // {
    //     return $request->user()->name;
    // }

    public function getUser(Request $request)
    {
        // make response
        $response = [
            'response_code' => '00',
            'response_message' => 'Profile berhasil ditampilkan',
            'data' => [
                'user' => $request->user()
            ]
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'picture' => ['required']
        ]);

        try {
            $extension = $request->file('picture')->extension();
            $filename = rand(0, 100000) . '.' . $extension;
            $path = Storage::putFileAs(
                'avatars',
                $request->file('picture'),
                $filename
            );

            $user = User::where('id', '=', $request->user()->id)->update([
                'name' => $request->name,
                'picture' => $filename
            ]);

            $user = User::find($request->user()->id);

            // make response
            $response = [
                'response_code' => '00',
                'response_message' => 'Berhasil update profile',
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
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email:rfc,dns'],
                'password' => ['required', 'confirmed', 'min:6'],
            ]);

            $user = User::where('email', '=', $request->email)->update([
                'password' => bcrypt($request->password)
            ]);

            if ($user === 0) {
                return [
                    'response_code' => '01',
                    'response_message' => 'User not found',
                ];
            }

            $user = User::where('email', '=', $request->email)->first();

            // make response
            $response = [
                'response_code' => '00',
                'response_message' => 'Berhasil merubah password',
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
}
