<?php

namespace Squashjedi\Basecamp\App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Squashjedi\Basecamp\App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'verified', 'name', 'email', 'password', 'verify_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the social networks for the user.
     */
    public function social()
    {
        return $this->hasMany(Social::class);
    }


    /**
     * Get the password code for the user.
     */
    public function passwordCode()
    {
        return $this->hasOne(PasswordCode::class, 'email', 'email');
    }
}