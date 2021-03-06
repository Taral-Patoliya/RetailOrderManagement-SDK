<?php
/**
 * Copyright (c) 2014-2015 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2014-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Radial\RetailOrderManagement\Payload\TaxDutyFee;

interface ICustomization extends ICustomizationBase
{
    const PRICE_GROUP_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * get a new, empty IMerchandisePriceGroup object
     * @return IMerchandisePriceGroup
     */
    public function getEmptyPriceGroup();

    /**
     * Additional charges associated with the customization.
     *
     * @return IMerchandisePriceGroup
     */
    public function getUpCharge();

    /**
     * @param IPriceGroup
     * @return self
     */
    public function setUpCharge(IMerchandisePriceGroup $priceGroup);
}
