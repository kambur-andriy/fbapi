<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class SocialNetworkAccount extends Model
{
    /**
     * Social networks
     */
    const SOCIAL_NETWORK_FACEBOOK = 0;
    const SOCIAL_NETWORK_GOOGLE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_network_id', 'user_token', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get user
     */
    public function user() {
        return $this->belongsTo('App\Models\DB\User', 'id', 'user_id');
    }

}
