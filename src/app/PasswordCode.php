<?php

namespace Squashjedi\Basecamp\App;

use Illuminate\Database\Eloquent\Model;

class PasswordCode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_codes';

    public function setUpdatedAt($value) {
        // Do Nothing
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'code'
    ];
}
