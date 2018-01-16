<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @var bool Disable timestamps
     */
    public $timestamps = false;
}
