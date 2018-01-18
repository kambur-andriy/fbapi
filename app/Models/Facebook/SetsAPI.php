<?php

namespace App\Models\Facebook;

use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Values\AdSetBillingEventValues;
use FacebookAds\Object\Values\AdSetOptimizationGoalValues;
use FacebookAds\Object\TargetingSearch;
use FacebookAds\Object\Search\TargetingSearchTypes;
use FacebookAds\Object\Targeting;
use FacebookAds\Object\Fields\TargetingFields;
use FacebookAds\Object\AdAccount;
use DateTime;


class SetsAPI extends AdvertisingApi
{
    /**
     * Get Ad Sets for Account
     *
     * return FacebookAds\Cursor
     */
    public function getSets()
    {
        $account = new AdAccount($this->accountID);

        $adSets = $account->getAdSets(
            [
                AdSetFields::ID,
                AdSetFields::NAME,
                AdSetFields::DAILY_BUDGET,
                AdSetFields::CAMPAIGN,
                AdSetFields::STATUS
            ]
        );

        return $adSets;
    }

    /**
     * Add new Ad Set
     *
     * @param array $set
     *
     * @throws \Exception
     */
    public function addSet($set)
    {
        $targetingInterest = $this->defineInterest($set['interest']);
        $targeting = $this->defineTargeting($targetingInterest);

        $adSet = new AdSet(null, $this->accountID);

        $adSet->setData(
            [
                AdSetFields::NAME => $set['name'],
                AdSetFields::OPTIMIZATION_GOAL => $set['optimization_goal'],
                AdSetFields::BILLING_EVENT => $set['billing_event'],
                AdSetFields::BID_AMOUNT => $set['bid_amount'],
                AdSetFields::DAILY_BUDGET => $set['daily_budget'],
                AdSetFields::CAMPAIGN_ID => $set['campaign'],
                AdSetFields::START_TIME => (new \DateTime($set['start_date']))->format(DateTime::ISO8601),
                AdSetFields::END_TIME => (new \DateTime($set['end_date']))->format(DateTime::ISO8601),
                AdSetFields::TARGETING => $targeting,
            ]
        );

        $adSet->create(
            [
                AdSet::STATUS_PARAM_NAME => AdSet::STATUS_PAUSED,
            ]
        );
    }


    /**
     * Define target interest
     *
     * @param $interest
     * @return \FacebookAds\Cursor
     */
    protected function defineInterest($interest)
    {
        return TargetingSearch::search(
            TargetingSearchTypes::INTEREST,
            null,
            $interest
        );
    }

    /**
     * Define Targeting
     *
     * @param \FacebookAds\Cursor $targetInterest
     *
     * @return Targeting
     */
    protected function defineTargeting($targetInterest)
    {
        $targeting = new Targeting();

        $targeting->{TargetingFields::INTERESTS} = $targetInterest;

        $targeting->{TargetingFields::GEO_LOCATIONS} =
            [
                'countries' => ['US']
            ];

        return $targeting;
    }

    /**
     * Get available set optimization goals
     *
     * @return array
     */
    public static function getSetOptimizationGoals()
    {
        return [
            AdSetOptimizationGoalValues::NONE => 'NONE',
            AdSetOptimizationGoalValues::APP_INSTALLS => 'APP INSTALLS',
            AdSetOptimizationGoalValues::BRAND_AWARENESS => 'BRAND_AWARENESS',
            AdSetOptimizationGoalValues::AD_RECALL_LIFT => 'AD RECALL LIFT',
            AdSetOptimizationGoalValues::CLICKS => 'CLICKS',
            AdSetOptimizationGoalValues::ENGAGED_USERS => 'ENGAGED USERS',
            AdSetOptimizationGoalValues::EVENT_RESPONSES => 'EVENT RESPONSES',
            AdSetOptimizationGoalValues::IMPRESSIONS => 'IMPRESSIONS',
            AdSetOptimizationGoalValues::LEAD_GENERATION => 'LEAD GENERATION',
            AdSetOptimizationGoalValues::LINK_CLICKS => 'LINK CLICKS',
            AdSetOptimizationGoalValues::OFFER_CLAIMS => 'OFFER CLAIMS',
            AdSetOptimizationGoalValues::OFFSITE_CONVERSIONS => 'OFFSITE CONVERSIONS',
            AdSetOptimizationGoalValues::PAGE_ENGAGEMENT => 'PAGE ENGAGEMENT',
            AdSetOptimizationGoalValues::PAGE_LIKES => 'PAGE LIKES',
            AdSetOptimizationGoalValues::POST_ENGAGEMENT => 'POST ENGAGEMENT',
            AdSetOptimizationGoalValues::REACH => 'REACH',
            AdSetOptimizationGoalValues::SOCIAL_IMPRESSIONS => 'SOCIAL IMPRESSIONS',
            AdSetOptimizationGoalValues::VIDEO_VIEWS => 'VIDEO_VIEWS',
            AdSetOptimizationGoalValues::APP_DOWNLOADS => 'APP_DOWNLOADS',
            AdSetOptimizationGoalValues::LANDING_PAGE_VIEWS => 'LANDING_PAGE_VIEWS',
        ];
    }

    /**
     * Get available set billing events
     *
     * @return array
     */
    public static function getSetBillingEvents()
    {
        return [
            AdSetBillingEventValues::APP_INSTALLS => 'APP INSTALLS',
            AdSetBillingEventValues::CLICKS => 'CLICKS',
            AdSetBillingEventValues::IMPRESSIONS => 'IMPRESSIONS',
            AdSetBillingEventValues::LINK_CLICKS => 'LINK CLICKS',
            AdSetBillingEventValues::OFFER_CLAIMS => 'OFFER CLAIMS',
            AdSetBillingEventValues::PAGE_LIKES => 'PAGE LIKES',
            AdSetBillingEventValues::POST_ENGAGEMENT => 'POST ENGAGEMENT',
            AdSetBillingEventValues::VIDEO_VIEWS => 'VIDEO VIEWS',
            AdSetBillingEventValues::MRC_VIDEO_VIEWS => 'MRC VIDEO VIEWS',
            AdSetBillingEventValues::COMPLETED_VIDEO_VIEWS => 'COMPLETED VIDEO VIEWS',
            AdSetBillingEventValues::VIDEO_VIEWS_15S => 'VIDEO VIEWS 15S',
        ];
    }

}
