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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Radial\RetailOrderManagement\Payload\Address;

use Radial\RetailOrderManagement\Payload\Checkout\IPhysicalAddress;

interface IResultAddress extends IPhysicalAddress, IErrorLocationContainer
{
    /**
     * Get the complete address, formatted according to the address format
     * for the target country.
     *
     * @return string
     */
    public function getFormattedAddress();

    /**
     * @param string
     * @return self
     */
    public function setFormattedAddress($formattedAddress);
}
