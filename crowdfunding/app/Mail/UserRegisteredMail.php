<?php

namespace App\Mail;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $otp_code = OtpCode::where('user_id', $this->user->id)->first();

        return $this->from('example@example.com')
            ->view('send_email_registered')
            ->with([
                'name' => $this->user->name,
                'otp_code' => $otp_code->otp
            ]);
    }
}
