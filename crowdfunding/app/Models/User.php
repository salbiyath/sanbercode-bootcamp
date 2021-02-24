<?php

namespace App\Models;

use App\Models\Article\Article;
use App\Traits\UsesUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'picture'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function get_user_role_id()
    {
        $roleIdUser = DB::table('roles')
            ->where('name', '=', 'user')
            ->value('id');

        return $roleIdUser;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->role_id = $model->get_user_role_id();
        });

        static::created(function ($model) {
            $model->generate_otp_code();
        });
    }

    public function generate_otp_code()
    {
        do {

            $random = mt_rand(100000, 999999);
            $check = OtpCode::where('otp', $random)->first();
        } while ($check);

        $now = Carbon::now();

        // create code otp
        $otp_code = OtpCode::updateOrCreate(
            ['user_id' => $this->id],
            ['otp' => $random, 'valid_until' => $now->addMinutes(5)]
        );
    }

    public function isVerified()
    {
        if ($this->email_verified_at === null) {
            return false;
        }
        return true;
    }

    public function isAdmin()
    {
        $roleIdAdmin = DB::table('roles')
            ->where('name', '=', 'admin')
            ->value('id');

        if ($this->role_id === $roleIdAdmin) {
            return true;
        }
        return false;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
