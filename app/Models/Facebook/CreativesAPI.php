<?php

namespace App\Models\Facebook;

use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;
use FacebookAds\Object\AdCreative;
use FacebookAds\Object\AdCreativeLinkData;
use FacebookAds\Object\Fields\AdCreativeLinkDataFields;
use FacebookAds\Object\AdCreativeObjectStorySpec;
use FacebookAds\Object\Fields\AdCreativeObjectStorySpecFields;
use FacebookAds\Object\Fields\AdCreativeFields;

class CreativesAPI extends AdvertisingApi
{
    /**
     * Get Ad Companies for Account
     *
     * return FacebookAds\Cursor
     */
    public function getCreatives()
    {
        $account = new AdAccount($this->accountID);

        $adCreatives = $account->getAdCreatives(array(
            AdCreativeFields::ID,
            AdCreativeFields::NAME,
            AdCreativeFields::THUMBNAIL_URL
        ));

        return $adCreatives;
    }

    /**
     * Add new Ad Campaign
     *
     * @param array $creative
     *
     * @throws \Exception
     */
    public function addCreative($creative)
    {
        $adLink = new AdCreativeLinkData();

        $adLink->setData(array(
            AdCreativeLinkDataFields::MESSAGE => $creative['message'],
            AdCreativeLinkDataFields::LINK => $creative['link'],
            AdCreativeLinkDataFields::IMAGE_HASH => $this->addImage($creative['image_path']),
        ));

        $objectStorySpec = new AdCreativeObjectStorySpec();

        $objectStorySpec->setData(
            [
                AdCreativeObjectStorySpecFields::PAGE_ID => $creative['page'],
                AdCreativeObjectStorySpecFields::LINK_DATA => $adLink,
            ]
        );

        $adCreative = new AdCreative(null, $this->accountID);

        $adCreative->setData(
            [
                AdCreativeFields::NAME => $creative['name'],
                AdCreativeFields::OBJECT_STORY_SPEC => $objectStorySpec,
            ]
        );

        $adCreative->create();
    }

    /**
     * Create Image
     *
     * @param string $imagePath
     *
     * @return mixed
     * @throws \Exception
     */
    protected function addImage($imagePath)
    {
        $image = new AdImage(null, $this->accountID);

        $image->{AdImageFields::FILENAME} = $imagePath;

        $image->create();

        return $image->{AdImageFields::HASH};
    }
}
