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

use Radial\RetailOrderManagement\Payload\IPayload;

interface ITaxedMerchandisePriceGroup extends ITaxedPriceGroup
{
    /**
     * Individual unit price for the group of pricing.
     *
     * restrictions: optional
     * @return float
     */
    public function getUnitPrice();

    /**
     * @param float
     * @return self
     */
    public function setUnitPrice($unitPrice);
}
