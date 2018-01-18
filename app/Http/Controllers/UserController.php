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
use App\Models\Facebook\CampaignsAPI;
use App\Models\Facebook\SetsAPI;
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
     * Authorized user
     */
    protected $user;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(
            function ($request, $next) {
                $this->user = Auth::user();

                return $next($request);
            }
        );
    }

    /**
     * User profile
     *
     * @return View
     */
    public function profile()
    {
        // Profile
        $profile = $this->user->profile;

        // FB Account
        $fbAccount = $this->user->socialNetworkAccount;

        return view(
            'user.profile',
            [
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
            $profile = $this->user->profile;

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
     * User Ad Campaigns
     *
     * @return View
     */
    public function campaigns()
    {
        // Ad Account
        $fbAccount = $this->user->socialNetworkAccount;

        if (is_null($fbAccount)) {
            return view(
                'user.no-account'
            );
        }

        $adApi = new CampaignsAPI($fbAccount->account_id, $fbAccount->account_token);
        $adCampaigns = $adApi->getCampaigns();

        return view(
            'user.campaigns',
            [
                'objectives' => CampaignsAPI::getCampaignObjectives(),
                'adCampaigns' => $adCampaigns,
            ]
        );
    }

    /**
     * Create Ad Campaign
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function createCampaign(Request $request)
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

            $fbAccount = $this->user->socialNetworkAccount;

            if (!$fbAccount) {
                throw new \Exception('Error saving company');
            }

            $campaign = [
                'name' => $request->input('name'),
                'objective' => $request->input('objective')
            ];

            $adApi = new CampaignsAPI($fbAccount->account_id, $fbAccount->account_token);
            $adApi->addCampaign($campaign);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error creating Ad Campaign',
                    'errors' => [
                        'name' => 'Error creating Ad Campaign',
                    ]
                ],
                422
            );
        }

        DB::commit();

        return response()->json(
            [
                $campaign
            ]
        );

    }

    /**
     * User Ad Sets
     *
     * @return View
     */
    public function sets()
    {
        // Ad Account
        $fbAccount = $this->user->socialNetworkAccount;

        if (is_null($fbAccount)) {
            return view(
                'user.no-account'
            );
        }

        $adApi = new CampaignsAPI($fbAccount->account_id, $fbAccount->account_token);
        $adCampaigns = [];

        foreach ($adApi->getCampaigns() as $adCampaign) {
            $adCampaigns[$adCampaign->id] = $adCampaign->name;
        }

        $adApi = new SetsAPI($fbAccount->account_id, $fbAccount->account_token);
        $adSets = $adApi->getSets();

        return view(
            'user.sets',
            [
                'optimizationGoals' => SetsAPI::getSetOptimizationGoals(),
                'billingEvents' => SetsAPI::getSetBillingEvents(),
                'adCampaigns' => $adCampaigns,
                'adSets' => $adSets,
            ]
        );
    }

    /**
     * Create Ad Set
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function createSet(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'bid_amount' => 'required|integer',
                'daily_budget' => 'required|integer',
                'optimization_goal' => 'required|string',
                'billing_event' => 'required|string',
                'interest' => 'required|string',
                'campaign' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'name' => 'Set Name',
                    'start_date' => 'Start Date',
                    'end_date' => 'End Date',
                    'bid_amount' => 'BID Amount',
                    'daily_budget' => 'Daily Budget',
                    'optimization_goal' => 'Optimization Goal',
                    'billing_event' => 'Billing Event',
                    'interest' => 'Interest',
                    'campaign' => 'Campaign',
                ]
            )
        );

        DB::beginTransaction();

        try {
            $fbAccount = $this->user->socialNetworkAccount;

            if (!$fbAccount) {
                throw new \Exception('Error creating Ad Set');
            }

            $set = [
                'name' => $request->input('name'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'bid_amount' => $request->input('bid_amount'),
                'daily_budget' => $request->input('daily_budget'),
                'optimization_goal' => $request->input('optimization_goal'),
                'billing_event' => $request->input('billing_event'),
                'interest' => $request->input('interest'),
                'campaign' => $request->input('campaign'),
            ];

            $adApi = new SetsAPI($fbAccount->account_id, $fbAccount->account_token);
            $adApi->addSet($set);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => $e->getErrorUserMessage(),//'Error creating Ad Set',
                    'errors' => [
                        'account_name' => 'Error creating Ad Set',
                    ]
                ],
                422
            );
        }

        DB::commit();

        return response()->json(
            [
                $set
            ]
        );

    }

}
