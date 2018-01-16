<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class AdvertisingAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'account_id'
    ];

}
