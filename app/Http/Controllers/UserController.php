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
use App\Models\Facebook\AdsAPI;
use App\Models\Facebook\AdvertisingApi;
use App\Models\Facebook\CampaignsAPI;
use App\Models\Facebook\CreativesAPI;
use App\Models\Facebook\SetsAPI;
use App\Models\Validation\ValidationMessages;
use FacebookAds\Http\Exception\AuthorizationException;
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
     * Ad Account ID
     */
    protected $adAccountID;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(
            function ($request, $next) {
                $this->user = Auth::user();

                $this->adAccountID = $this->user->profile->ad_account_id;

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

        return view(
            'user.profile',
            [
                'profile' => $profile
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
                'ad_account_id' => 'required|string',
                'first_name' => 'alpha|max:100|nullable',
                'last_name' => 'alpha|max:100|nullable',
            ],
            ValidationMessages::getList(
                [
                    'ad_account_id' => 'Ad Account ID',
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

            $profile->ad_account_id = $request->ad_account_id;
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
        $fbAccount = $this->user->socialNetworkAccount;

        if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
            return view(
                'user.no-account'
            );
        }

        $adApi = new CampaignsAPI($this->adAccountID, $fbAccount->account_token);
        $adCampaigns = $adApi->getCampaigns();

        return view(
            'user.campaigns',
            [
                'adCampaigns' => $adCampaigns,
                'objectives' => CampaignsAPI::getCampaignObjectives(),
                'statuses' => CampaignsAPI::getCampaignStatuses(),
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
                'status' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'name' => 'Campaign Name',
                    'objective' => 'Campaign Objective',
                    'status' => 'Campaign Status',
                ]
            )
        );

        try {

            $fbAccount = $this->user->socialNetworkAccount;

            if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
                throw new \Exception('Error saving campaign');
            }

            $campaignInfo = [
                'name' => $request->input('name'),
                'objective' => $request->input('objective'),
                'status' => $request->input('status')
            ];

            $adApi = new CampaignsAPI($this->adAccountID, $fbAccount->account_token);
            $adApi->addCampaign($campaignInfo);

        } catch (\Exception $e) {

            $message = ($e instanceof AuthorizationException) ? $e->getErrorUserMessage() : 'Error creating Ad Campaign';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => [
                        'name' => $message,
                    ]
                ],
                422
            );
        }

        return response()->json(
            []
        );

    }

    /**
     * Delete Ad Campaign
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function deleteCampaign(Request $request)
    {
        $this->validate(
            $request,
            [
                'campaign' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'campaign' => 'Campaign',
                ]
            )
        );

        DB::beginTransaction();

        try {

            $fbAccount = $this->user->socialNetworkAccount;

            if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
                throw new \Exception('Error deleting campaign');
            }

            $adCampaign = $request->input('campaign');

            $adApi = new CampaignsAPI($fbAccount->account_id, $fbAccount->account_token);
            $adApi->deleteCampaign($adCampaign);

        } catch (\Exception $e) {

            DB::rollback();

            $message = ($e instanceof AuthorizationException) ? $e->getErrorUserMessage() : 'Error deleting Ad Campaign';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => []
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

        $adApi = new CampaignsAPI($this->adAccountID, $fbAccount->account_token);
        $adCampaigns = $adApi->getCampaigns();

        $adApi = new SetsAPI($this->adAccountID, $fbAccount->account_token);
        $adSets = $adApi->getSets();

        return view(
            'user.sets',
            [
                'optimizationGoals' => SetsAPI::getSetOptimizationGoals(),
                'billingEvents' => SetsAPI::getSetBillingEvents(),
                'statuses' => SetsAPI::getSetStatuses(),
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
                'campaign' => 'required|string',
                'name' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'bid_amount' => 'required|integer',
                'daily_budget' => 'required|integer',
                'optimization_goal' => 'required|string',
                'billing_event' => 'required|string',
                'status' => 'required|string',

                'age_min' => 'required|integer',
                'age_max' => 'required|integer',
                'gender' => 'required|integer',
                'interest' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'campaign' => 'Campaign',
                    'name' => 'Set Name',
                    'start_date' => 'Start Date',
                    'end_date' => 'End Date',
                    'bid_amount' => 'BID Amount',
                    'daily_budget' => 'Daily Budget',
                    'optimization_goal' => 'Optimization Goal',
                    'billing_event' => 'Billing Event',
                    'status' => 'Set Status',

                    'age_min' => 'Age Min',
                    'age_max' => 'Age Max',
                    'gender' => 'Gender',
                    'interest' => 'Interest',
                ]
            )
        );

        try {
            $fbAccount = $this->user->socialNetworkAccount;

            if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
                throw new \Exception('Error creating Ad Set');
            }

            $setInfo = [
                'campaign' => $request->input('campaign'),
                'name' => $request->input('name'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'bid_amount' => $request->input('bid_amount'),
                'daily_budget' => $request->input('daily_budget'),
                'optimization_goal' => $request->input('optimization_goal'),
                'billing_event' => $request->input('billing_event'),
                'status' => $request->input('status'),
                'targeting' => [
                    'age_min' => $request->input('age_min'),
                    'age_max' => $request->input('age_max'),
                    'gender' => $request->input('gender'),
                    'interest' => $request->input('interest'),
                ]
            ];

            $adApi = new SetsAPI($this->adAccountID, $fbAccount->account_token);
            $adApi->addSet($setInfo);

        } catch (\Exception $e) {

            $message = ($e instanceof AuthorizationException) ? $e->getErrorUserMessage() : 'Error creating Ad Set';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => [
                        'name' => $message,
                    ]
                ],
                422
            );
        }

        return response()->json(
            []
        );

    }

    /**
     * Delete Ad Set
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function deleteSet(Request $request)
    {
        $this->validate(
            $request,
            [
                'set' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'set' => 'Set',
                ]
            )
        );

        try {

            $fbAccount = $this->user->socialNetworkAccount;

            if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
                throw new \Exception('Error deleting set');
            }

            $adSet = $request->input('set');

            $adApi = new SetsAPI($this->adAccountID, $fbAccount->account_token);;
            $adApi->deleteSet($adSet);

        } catch (\Exception $e) {

            $message = ($e instanceof AuthorizationException) ? $e->getErrorUserMessage() : 'Error deleting Ad Set';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => []
                ],
                422
            );
        }

        return response()->json(
            []
        );
    }

    /**
     * User Ad Creatives
     *
     * @return View
     */
    public function creatives()
    {
        // Ad Account
        $fbAccount = $this->user->socialNetworkAccount;

        if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
            return view(
                'user.no-account'
            );
        }

        $adApi = new CreativesAPI($this->adAccountID, $fbAccount->account_token);
        $adCreatives = $adApi->getCreatives();

        return view(
            'user.creatives',
            [
                'creatives' => $adCreatives
            ]
        );
    }

    /**
     * Create Ad Creative
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function createCreative(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string',
                'page' => 'string|required_without_all:page,link,message',
                'link' => 'required|url',
                'message' => 'required|string',
                'image_file' => 'file|max:2048|mimetypes:image/jpeg,image/png',
            ],
            ValidationMessages::getList(
                [
                    'name' => 'Name',
                    'page' => 'Page ID',
                    'link' => 'Link',
                    'message' => 'Message',
                    'image_file' => 'Image File',
                ]
            )
        );

        try {
            $fbAccount = $this->user->socialNetworkAccount;

            if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
                throw new \Exception('Error creating Ad Set');
            }

            $file = $request->file('image_file');

            $newFileName = uniqid() . '.' . $file->getClientOriginalExtension();

            $filePath = $file->storeAs('creatives', $newFileName);

            $creativeInfo = [
                'name' => $request->input('name'),
                'page' => $request->input('page'),
                'link' => $request->input('link'),
                'message' => $request->input('message'),
                'image_path' => storage_path('app/creatives/') . $newFileName,
            ];

            $adApi = new CreativesAPI($this->adAccountID, $fbAccount->account_token);
            $adApi->addCreative($creativeInfo);

            Storage::delete($filePath);

        } catch (\Exception $e) {

            $message = ($e instanceof AuthorizationException) ? $e->getErrorUserMessage() : 'Error creating Ad Creative';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => [
                        'name' => $message,
                    ]
                ],
                422
            );
        }

        return response()->json(
            []
        );

    }

    /**
     * Delete Ad Creative
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function deleteCreative(Request $request)
    {
        $this->validate(
            $request,
            [
                'creative' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'creative' => 'Creative',
                ]
            )
        );

        try {

            $fbAccount = $this->user->socialNetworkAccount;

            if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
                throw new \Exception('Error deleting creative');
            }

            $adCreative = $request->input('creative');

            $adApi = new CreativesAPI($this->adAccountID, $fbAccount->account_token);;
            $adApi->deleteCreative($adCreative);

        } catch (\Exception $e) {

            $message = ($e instanceof AuthorizationException) ? $e->getErrorUserMessage() : 'Error deleting Ad Creative';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => []
                ],
                422
            );
        }

        return response()->json(
            []
        );
    }

    /**
     * Check if FB Account exist and Ad Account ID is not empty
     *
     * @param string $adAccountID
     * @param SocialNetworkAccount $fbAccount
     *
     * @return bool
     */
    protected function canUseAPI($adAccountID, $fbAccount)
    {
        if (is_null($fbAccount) || is_null($this->adAccountID)) {
            return false;
        }

        return true;
    }

    /**
     * User Ads
     *
     * @return View
     */
    public function ads()
    {
        // Ad Account
        $fbAccount = $this->user->socialNetworkAccount;

        if (is_null($fbAccount)) {
            return view(
                'user.no-account'
            );
        }

        $adApi = new SetsAPI($this->adAccountID, $fbAccount->account_token);
        $adSets = $adApi->getSets();

        $adApi = new CreativesAPI($this->adAccountID, $fbAccount->account_token);
        $adCreatives = $adApi->getCreatives();

        $adApi = new AdsAPI($this->adAccountID, $fbAccount->account_token);
        $ads = $adApi->getAds();

        return view(
            'user.ads',
            [
                'statuses' => AdsAPI::getAdStatuses(),
                'adSets' => $adSets,
                'adCreatives' => $adCreatives,
                'ads' => $ads
            ]
        );
    }

    /**
     * Create Ad
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function createAd(Request $request)
    {
        $this->validate(
            $request,
            [
                'set' => 'required|string',
                'creative' => 'required|string',
                'name' => 'required|string',
                'status' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'set' => 'Set',
                    'creative' => 'Creative',
                    'name' => 'Ad Name',
                    'status' => 'Set Status',
                ]
            )
        );

        try {
            $fbAccount = $this->user->socialNetworkAccount;

            if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
                throw new \Exception('Error creating Ad Set');
            }

            $adInfo = [
                'set' => $request->input('set'),
                'creative' => $request->input('creative'),
                'name' => $request->input('name'),
                'status' => $request->input('status'),
            ];

            $adApi = new AdsAPI($this->adAccountID, $fbAccount->account_token);
            $adApi->addAd($adInfo);

        } catch (\Exception $e) {

            $message = ($e instanceof AuthorizationException) ? $e->getErrorUserMessage() : 'Error creating Ad';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => [
                        'name' => $message,
                    ]
                ],
                422
            );
        }

        return response()->json(
            []
        );

    }

    /**
     * Delete Ad
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function deleteAd(Request $request)
    {
        $this->validate(
            $request,
            [
                'ad' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'ad' => 'Ad',
                ]
            )
        );

        try {

            $fbAccount = $this->user->socialNetworkAccount;

            if (!$this->canUseAPI($this->adAccountID, $fbAccount)) {
                throw new \Exception('Error deleting set');
            }

            $adAd = $request->input('ad');

            $adApi = new AdsAPI($this->adAccountID, $fbAccount->account_token);;
            $adApi->deleteAd($adAd);

        } catch (\Exception $e) {

            $message = ($e instanceof AuthorizationException) ? $e->getErrorUserMessage() : 'Error deleting Ad';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => []
                ],
                422
            );
        }

        return response()->json(
            []
        );
    }

}
