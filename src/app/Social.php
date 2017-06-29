<?php

namespace Squashjedi\Basecamp\App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'social';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'provider', 'provider_id'
    ];
}
