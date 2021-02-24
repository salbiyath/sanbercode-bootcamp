<?php

namespace App\Http\Controllers\Auth;

use App\Events\Illuminate\Mail\Events\UserRegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use App\Mail\UserRegisteredMail;
use App\Notifications\WelcomeEmailNotification;
use App\Providers\UserRegisteredEvent as ProvidersUserRegisteredEvent;
use Illuminate\Support\Facades\Mail;


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

            // event(new ProvidersUserRegisteredEvent($user));

            ProvidersUserRegisteredEvent::dispatch($user);

            // Mail::to($user)->send(new UserRegisteredMail($user));

            // $user->notify(new WelcomeEmailNotification());


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
