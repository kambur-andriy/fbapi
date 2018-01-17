<?php

namespace App\Models\Processors;


use App\Models\DB\Profile;
use App\Models\DB\SocialNetworkAccount;
use App\Models\DB\User;

class Account
{

    /**
     * Create user acconut
     *
     * @return array
     */
    public static function addUser($userInfo)
    {
        $user = User::create($userInfo);

        Profile::create(
            [
                'user_id' => $user->id
            ]
        );

        return $user;
    }


    /**
     * Process FB user
     *
     * @param array $fbUserInfo
     *
     * @return int
     */
    public static function processFBUser($fbUserInfo)
    {
        $fbAccount = self::getFBUser($fbUserInfo);


        if (is_null($fbAccount)) {

            $user = self::addFBUser($fbUserInfo);

            $userID = $user->id;

        } else {

            $fbAccount->account_token = $fbUserInfo->token;
            $fbAccount->save();

            $userID = $fbAccount->user()->id;
        }

        return $userID;
    }

    /**
     * Get FB user account info
     *
     * @param array $fbUserInfo
     *
     * @return SocialNetworkAccount | null
     */
    public static function getFBUser($fbUserInfo)
    {
        return SocialNetworkAccount::where('account_id', $fbUserInfo->getId())
            ->where('network', SocialNetworkAccount::SOCIAL_NETWORK_FACEBOOK)
            ->first();
    }

    /**
     * Create FB user acconut
     *
     * @param array $userInfo
     * @param array $fbUserInfo
     *
     * @return array
     */
    public static function addFBUser($fbUserInfo)
    {
        $user = self::addUser(
            [
                'email' => $fbUserInfo->email,
                'password' => User::hashPassword(uniqid()),
            ]
        );

        SocialNetworkAccount::create(
            [
                'network_id' => SocialNetworkAccount::SOCIAL_NETWORK_FACEBOOK,
                'account_id' => $fbUserInfo->getId(),
                'account_token' => $fbUserInfo->token,
                'user_id' => $user->id
            ]
        );

        return $user;
    }

    /**
     * Return user home page
     *
     * @param int $userRole
     *
     * @return string
     */
    public static function getUserPage($userRole)
    {
        switch ($userRole) {
            case User::USER_ROLE_ADMIN:
                return '/admin/users';

            case User::USER_ROLE_MANAGER:
                return '/admin/users';

            case User::USER_ROLE_USER:
                return '/user/profile';

            default:
                return '/';
        }
    }


}
