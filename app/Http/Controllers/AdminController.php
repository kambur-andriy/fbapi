<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\DB\Country;
use App\Models\DB\DebitCard;
use App\Models\DB\Document;
use App\Models\DB\Invite;
use App\Models\DB\Profile;
use App\Models\DB\State;
use App\Models\DB\Transaction;
use App\Models\DB\User;
use App\Models\DB\Verification;
use App\Models\Validation\ValidationMessages;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Users list
     *
     * @return View
     */
    public function users()
    {
        $usersList = [];

        foreach (User::all() as $user) {

            $usersList[] = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'role' => User::getRole($user->role),
            ];
        }

        return view(
            'admin.users',
            [
                'users' => $usersList,
            ]
        );
    }

}
