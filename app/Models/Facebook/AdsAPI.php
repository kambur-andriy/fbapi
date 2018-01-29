<?php

namespace App\Models\Facebook;

use FacebookAds\Object\Ad;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\AdAccount;


class AdsAPI extends AdvertisingApi
{
    /**
     * Get Ad Sets for Account
     *
     * return FacebookAds\Cursor
     */
    public function getAds()
    {
        $account = new AdAccount($this->accountID);

        $ads = $account->getAds(
            [
                AdFields::ID,
                AdFields::NAME,
                AdFields::ADSET_ID,
                AdFields::STATUS
            ]
        );

        return $ads;
    }

    /**
     * Add new Ad
     *
     * @param $ad
     *
     * @return Ad
     *
     * @throws \Exception
     */
    public function addAd($ad)
    {

        $adAd = new Ad(null, $this->accountID);

        $adAd->setData(
            [
                AdFields::NAME => $ad['name'],
                AdFields::ADSET_ID => $ad['set'],
                AdFields::CREATIVE =>
                    [
                        'creative_id' => $ad['creative'],
                    ],
            ]
        );

        $adAd->create(
            [
                Ad::STATUS_PARAM_NAME => $ad['status'],
            ]
        );

        return $adAd;
    }

    /**
     * Delete Ad
     *
     * @param string $ad
     *
     * @throws \Exception
     */
    public function deleteAd($ad)
    {
        $adAd = new Ad($ad);
        $adAd->deleteSelf();
    }


    /**
     * Get available ad statuses
     *
     * @return array
     */
    public static function getAdStatuses()
    {
        return [
            Ad::STATUS_ACTIVE => 'ACTIVE',
            Ad::STATUS_PAUSED => 'PAUSED',
            Ad::STATUS_DELETED => 'DELETED',
            Ad::STATUS_ARCHIVED => 'ARCHIVED',
        ];
    }

}
