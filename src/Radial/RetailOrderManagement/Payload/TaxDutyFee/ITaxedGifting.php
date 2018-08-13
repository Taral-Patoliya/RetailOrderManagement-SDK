<?php
/**
 * Copyright (c) 2013-2014 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2013-2014 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Radial\RetailOrderManagement\Payload\TaxDutyFee;

interface ITaxedGifting
{
    const GIFTING_PRICE_GROUP_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedMerchandisePriceGroup';

    /**
     * Get a new, empty price group for gifting pricing.
     *
     * @return ITaxedMerchandisePriceGroup
     */
    public function getEmptyGiftPriceGroup();

    /**
     * Unique identifier for the gift packaging
     *
     * restrictions: length <= 12
     * @return string
     */
    public function getGiftId();

    /**
     * @param string
     * @return self
     */
    public function setGiftId($id);

    /**
     * Identifier for the item being included as a gift. A SKU.
     *
     * restrictions: string with length >= 1 and <= 20
     * @return string
     */
    public function getGiftItemId();

    /**
     * @param string
     * @return self
     */
    public function setGiftItemId($giftItemId);

    /**
     * Gift packaging description
     *
     * restrictions: optional
     * @return string
     */
    public function getGiftDescription();

    /**
     * @param string
     * @return self
     */
    public function setGiftDescription($giftItemId);

    /**
     * Pricing data for the gift.
     *
     * @return ITaxedMerchandisePriceGroup
     */
    public function getGiftPricing();

    /**
     * @param ITaxedMerchandisePriceGroup
     * @return self
     */
    public function setGiftPricing(ITaxedMerchandisePriceGroup $giftPricing);
}
