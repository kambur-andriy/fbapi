<?php

namespace App\Models\Facebook;

use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Values\CampaignObjectiveValues;

class AdvertisingApi
{

    /**
     * @var Ad Account ID
     */
    protected $accountID;

    /**
     * FB constructor.
     *
     * Init FB Adv API
     */
    public function __construct($accountID, $accessToken)
    {
        $appID = env('FB_CLIENT_ID', '');
        $appSecret = env('FB_CLIENT_SECRET', '');
//        $accessToken = 'EAAHw6rFh6WIBALd70GfXwz6ZBQc8srnWFR79rlZCl2YYHeEemyEsXJ2nYPlrQNyeT8ZBOg0aGgigLsRFWqwKhKOrakei1LCtBGK7xlFc0hz7TYihhgaprchziuuT8k8ioJC5oICqZAsWBJ7L9wvsAZBjvMD3jPASEYdY4Ij6ad7YdY43w1cvRZCcQTcDhGzeF7YFvP4y2pu6UP3SKNGCCoZAERQvXZCz3e0ZD';

        Api::init($appID, $appSecret, $accessToken);

        $this->accountID = 'act_' . $accountID;
    }

    /**
     * Get Ad Companies for Account
     *
     * @param string $accountID
     *
     * return array
     */
    public function getCompanies()
    {
        $account = new AdAccount($this->accountID);

        $campaigns = $account->getCampaigns(array(
            CampaignFields::NAME,
            CampaignFields::OBJECTIVE,
        ), array(
            CampaignFields::EFFECTIVE_STATUS => array(
                ArchivableCrudObjectEffectiveStatuses::ACTIVE,
                ArchivableCrudObjectEffectiveStatuses::PAUSED,
            ),
        ));

        return $campaigns;
    }

    /**
     * Add new Ad Company
     *
     * @param array $company
     *
     * @throws \Exception
     */
    public function addCompany($company)
    {
        $campaign = new Campaign(null, $this->accountID);

        $campaign->setData(
            [
                CampaignFields::NAME => $company['name'],
                CampaignFields::OBJECTIVE => $company['objective'],
            ]
        );

        $campaign->create(
            [
                Campaign::STATUS_PARAM_NAME => Campaign::STATUS_PAUSED,
            ]
        );
    }

    /**
     * Get available company objecives
     *
     * @return array
     */
    public static function getCompanyObjectives()
    {
        return [
            CampaignObjectiveValues::APP_INSTALLS => 'APP INSTALLS',
            CampaignObjectiveValues::BRAND_AWARENESS => 'BRAND AWARENESS',
            CampaignObjectiveValues::CONVERSIONS => 'CONVERSIONS',
            CampaignObjectiveValues::EVENT_RESPONSES => 'EVENT_RESPONSES',
            CampaignObjectiveValues::LEAD_GENERATION => 'LEAD GENERATION',
            CampaignObjectiveValues::LINK_CLICKS => 'LINK CLICKS',
            CampaignObjectiveValues::LOCAL_AWARENESS => 'LOCAL AWARENESS',
            CampaignObjectiveValues::OFFER_CLAIMS => 'OFFER CLAIMS',
            CampaignObjectiveValues::PAGE_LIKES => 'PAGE LIKES',
            CampaignObjectiveValues::POST_ENGAGEMENT => 'POST ENGAGEMENT',
            CampaignObjectiveValues::PRODUCT_CATALOG_SALES => 'PRODUCT CATALOG_SALES',
            CampaignObjectiveValues::REACH => 'REACH',
            CampaignObjectiveValues::VIDEO_VIEWS => 'VIDEO_VIEWS',

        ];
    }

}
