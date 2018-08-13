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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Radial\RetailOrderManagement\Payload\TaxDutyFee;

use Radial\RetailOrderManagement\Payload\IPayload;
use Radial\RetailOrderManagement\Payload\Checkout\IPhysicalAddress as ICheckoutPhysicalAddress;

interface IOriginPhysicalAddress extends IPayload, IPhysicalAddress
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    /**
     * The origin address root node name.
     *
     * @return string
     */
    public function getOriginAddressNodeName();

    /**
     * @param string
     * @return self
     */
    public function setOriginAddressNodeName($name);
}
