<?php

namespace App\Models\Facebook;

use FacebookAds\Api;

class AdvertisingApi
{
    /**
     * FB Account ID
     */
    protected $accountID;

    /**
     * FB Access Token
     */
    protected $accountAccessToken;

    /**
     * FB constructor.
     *
     * @param string $accountID
     * @param string $accountAccessToken
     *
     * Init FB Adv API
     */
    public function __construct($accountID, $accountAccessToken)
    {
        $this->accountID = 'act_' . $accountID;

//        $this->accountAccessToken = $accountAccessToken;
        $this->accountAccessToken = 'EAAHw6rFh6WIBALd70GfXwz6ZBQc8srnWFR79rlZCl2YYHeEemyEsXJ2nYPlrQNyeT8ZBOg0aGgigLsRFWqwKhKOrakei1LCtBGK7xlFc0hz7TYihhgaprchziuuT8k8ioJC5oICqZAsWBJ7L9wvsAZBjvMD3jPASEYdY4Ij6ad7YdY43w1cvRZCcQTcDhGzeF7YFvP4y2pu6UP3SKNGCCoZAERQvXZCz3e0ZD';

        $this->initAPI();
    }

    /**
     * Init FB Ad API
     */
    protected function initAPI() {
        $appID = env('FB_CLIENT_ID', '');
        $appSecret = env('FB_CLIENT_SECRET', '');

        Api::init($appID, $appSecret, $this->accountAccessToken);
    }

}
