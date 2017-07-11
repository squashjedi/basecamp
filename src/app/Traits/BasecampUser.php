<?php

namespace Squashjedi\Basecamp\App\Traits;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Squashjedi\Basecamp\App\Notifications\ResetPasswordNotification;
use Squashjedi\Basecamp\App\Social;
use Squashjedi\Basecamp\App\Role;
use Squashjedi\Basecamp\App\PasswordReset;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
        'verified', 'name', 'email', 'password', 'verify_token', 'deleted_at'
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
     * Get the roles for the user.
     */
    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    /**
     * Get the password code for the user.
     */
    public function passwordReset()
    {
        return $this->hasOne(PasswordReset::class, 'email', 'email');
    }
}