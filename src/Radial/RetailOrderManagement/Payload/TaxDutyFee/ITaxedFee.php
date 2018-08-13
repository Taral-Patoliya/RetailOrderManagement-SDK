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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface ITaxedFee extends IPayload, IFeeBase
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const PRICEGROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedPriceGroup';

    /**
     * get a new, empty ITaxedPriceGroup object
     * @return ITaxedPriceGroup
     */
    public function getEmptyFeePriceGroup();

    /**
     * Item id associated with the fee.
     *
     * @return ITaxedPriceGroup
     */
    public function getCharge();

    /**
     * @param ITaxedPriceGroup
     * @return self
     */
    public function setCharge(ITaxedPriceGroup $priceGroup);
}
