<?php

namespace App\Models\DB;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * User roles
     */
    const USER_ROLE_ADMIN = 0;
    const USER_ROLE_MANAGER = 1;
    const USER_ROLE_USER = 2;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'role'
    ];

    /**
     * Return role name
     *
     * @param int $role
     *
     * @return string
     */
    public static function getRole($role) {
        switch ($role) {
            case self::USER_ROLE_ADMIN:
                return 'Admin';

            case self::USER_ROLE_MANAGER:
                return 'Manager';

            case self::USER_ROLE_USER:
                return 'User';

            default:
                return '';
        }
    }

    /**
     * Get list of available user roles
     * @return array
     */
    public static function getRolesList() {
        return [
            [
                'id' => self::USER_ROLE_ADMIN,
                'name' => self::getRole(self::USER_ROLE_ADMIN)
            ],
            [
                'id' => self::USER_ROLE_MANAGER,
                'name' => self::getRole(self::USER_ROLE_MANAGER)
            ],
            [
                'id' => self::USER_ROLE_USER,
                'name' => self::getRole(self::USER_ROLE_USER)
            ]
        ];
    }

    /**
     * Get user's Profile
     */
    public function profile() {
        return $this->hasOne('App\Models\DB\Profile', 'user_id', 'id');
    }

    /**
     * Generate password hash
     *
     * @param string $password
     *
     * @return string
     */
    public static function hashPassword($password) {
        return bcrypt($password);
    }

    /**
     * Check password with existing hash
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public static function checkPassword($password, $hash) {
        return password_verify($password, $hash);
    }

}
