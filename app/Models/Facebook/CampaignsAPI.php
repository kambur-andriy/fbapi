<?php

namespace App\Models\Facebook;

use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Values\CampaignObjectiveValues;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;

class CampaignsAPI extends AdvertisingApi
{
    /**
     * Get Ad Companies for Account
     *
     * return FacebookAds\Cursor
     */
    public function getCampaigns()
    {
        $account = new AdAccount($this->accountID);

        $adCampaigns = $account->getCampaigns(
            [
                CampaignFields::ID,
                CampaignFields::NAME,
                CampaignFields::OBJECTIVE,
                CampaignFields::STATUS,
            ],
            [
                CampaignFields::EFFECTIVE_STATUS =>
                    [
                        ArchivableCrudObjectEffectiveStatuses::ACTIVE,
                        ArchivableCrudObjectEffectiveStatuses::PAUSED,
                    ]
            ]
        );

        return $adCampaigns;
    }

    /**
     * Add new Ad Campaign
     *
     * @param array $campaign
     *
     * @return Campaign
     * @throws \Exception
     */
    public function addCampaign($campaign)
    {
        $adCampaign = new Campaign(null, $this->accountID);

        $adCampaign->setData(
            [
                CampaignFields::NAME => $campaign['name'],
                CampaignFields::OBJECTIVE => $campaign['objective'],
            ]
        );

        $adCampaign->create(
            [
                Campaign::STATUS_PARAM_NAME => $campaign['status'],
            ]
        );

        return $adCampaign;
    }

    /**
     * Delete Ad Campaign
     *
     * @param string $campaign
     *
     * @throws \Exception
     */
    public function deleteCampaign($campaign)
    {
        $campaign = new Campaign($campaign);

        $campaign->deleteSelf();
    }

    /**
     * Get available campaign objecives
     *
     * @return array
     */
    public static function getCampaignObjectives()
    {
        return [
            CampaignObjectiveValues::APP_INSTALLS => 'APP INSTALLS',
            CampaignObjectiveValues::BRAND_AWARENESS => 'BRAND AWARENESS',
            CampaignObjectiveValues::CONVERSIONS => 'CONVERSIONS',
            CampaignObjectiveValues::EVENT_RESPONSES => 'EVENT RESPONSES',
            CampaignObjectiveValues::LEAD_GENERATION => 'LEAD GENERATION',
            CampaignObjectiveValues::LINK_CLICKS => 'LINK CLICKS',
            CampaignObjectiveValues::LOCAL_AWARENESS => 'LOCAL AWARENESS',
            CampaignObjectiveValues::OFFER_CLAIMS => 'OFFER CLAIMS',
            CampaignObjectiveValues::PAGE_LIKES => 'PAGE LIKES',
            CampaignObjectiveValues::POST_ENGAGEMENT => 'POST ENGAGEMENT',
            CampaignObjectiveValues::PRODUCT_CATALOG_SALES => 'PRODUCT CATALOG SALES',
            CampaignObjectiveValues::REACH => 'REACH',
            CampaignObjectiveValues::VIDEO_VIEWS => 'VIDEO VIEWS',

        ];
    }

    /**
     * Get available campaign statuses
     *
     * @return array
     */
    public static function getCampaignStatuses()
    {
        return [
            Campaign::STATUS_ACTIVE => 'ACTIVE',
            Campaign::STATUS_PAUSED => 'PAUSED',
            Campaign::STATUS_DELETED => 'DELETED',
            Campaign::STATUS_ARCHIVED => 'ARCHIVED'
        ];
    }

}
