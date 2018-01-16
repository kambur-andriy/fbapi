<?php

namespace App\Http\Controllers;

use App\Mail\InviteFriend;
use App\Models\Currency;
use App\Models\DB\AdvertisingAccount;
use App\Models\DB\Country;
use App\Models\DB\DebitCard;
use App\Models\DB\Document;
use App\Models\DB\Invite;
use App\Models\DB\Profile;
use App\Models\DB\SocialNetworkAccount;
use App\Models\DB\State;
use App\Models\DB\User;
use App\Models\DB\Verification;
use App\Models\Facebook\AdvertisingApi;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;


class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * User profile
     *
     * @return View
     */
    public function profile()
    {
        // User
        $user = Auth::user();

        // Profile
        $profile = $user->profile;

        // FB Account
        $fbAccount = SocialNetworkAccount::where('user_id', $user->id)
            ->where('network', SocialNetworkAccount::SOCIAL_NETWORK_FACEBOOK)
            ->first();;

        return view(
            'user.profile',
            [
                'user' => $user,
                'profile' => $profile,
                'adAccount' => $fbAccount
            ]
        );
    }

    /**
     * Save user profile
     *
     * @param Request $request
     *
     * @return View
     */
    public function saveProfile(Request $request)
    {
        $this->validate(
            $request,
            [
                'first_name' => 'alpha|max:100|nullable',
                'last_name' => 'alpha|max:100|nullable',
            ],
            ValidationMessages::getList(
                [
                    'first_name' => 'First Name',
                    'last_name' => 'Last Name',
                ]
            )
        );

        DB::beginTransaction();

        try {
            $user = Auth::user();

            $profile = $user->profile;

            if (!$profile) {
                throw new \Exception('Error updating profile');
            }

            $profile->first_name = $request->first_name;
            $profile->last_name = $request->last_name;
            $profile->save();

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error updating profile',
                    'errors' => [
                        'last_name' => 'Error updating profile',
                    ]
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            []
        );
    }


    /**
     * Save FB Adv account
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function saveAccount(Request $request)
    {
        $this->validate(
            $request,
            [
                'account_id' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'account_id' => 'Account Name',
                ]
            )
        );

        DB::beginTransaction();

        try {
            $user = Auth::user();

            $accountID = $request->input('account_id', '');

            $adAccount = $user->advertisingAccount;

            if (!$adAccount) {
                throw new \Exception('Error saving account');
            }

            $adAccount->account_id = $accountID;

            $adAccount->save();

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error saving FB advertising account',
                    'errors' => [
                        'account_name' => 'Error saving FB advertising account',
                    ]
                ],
                422
            );
        }

        DB::commit();

        return response()->json(
            []
        );

    }

    /**
     * User Ad Companies
     *
     * @return View
     */
    public function companies()
    {
        // User
        $user = Auth::user();

        // Ad Account
        $adAccount = $user->advertisingAccount;

        if (!$adAccount || empty($adAccount->account_id)) {
            return view(
                'user.no-account'
            );
        }

        $adApi = new AdvertisingApi($adAccount->account_id);
        $adCompanies = $adApi->getCompanies();

        return view(
            'user.companies',
            [
                'objectives' => AdvertisingApi::getCompanyObjectives(),
                'adCompanies' => $adCompanies,
            ]
        );
    }

    /**
     * Save Ad Company
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function saveCompany(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string',
                'objective' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'name' => 'Company Name',
                    'objective' => 'Company Objective',
                ]
            )
        );

        DB::beginTransaction();

        try {
            $user = Auth::user();

            $adAccount = $user->advertisingAccount;

            if (!$adAccount) {
                throw new \Exception('Error saving company');
            }

            $company = [
                'name' => $request->input('name'),
                'objective' => $request->input('objective')
            ];

            $adApi = new AdvertisingApi($adAccount->account_id);
            $adApi->addCompany($company);


        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error saving FB advertising account',
                    'errors' => [
                        'account_name' => 'Error saving FB advertising account',
                    ]
                ],
                422
            );
        }

        DB::commit();

        return response()->json(
            [
                $company
            ]
        );

    }

}
