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

namespace Radial\RetailOrderManagement\Payload\OrderEvents;

use Radial\RetailOrderManagement\Payload\IPayload;

interface ITaxDescriptionIterable extends \Countable, \Iterator, \ArrayAccess, IPayload
{
    const TAX_DESCRIPTION_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\OrderEvents\ITaxDescription';
    const ROOT_NODE = 'OrderTaxesDutiesFeesInformations';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const SUBPAYLOAD_XPATH = 'x:TaxesDutiesFeesInformation';
    /**
     * Get a new, empty tax description object.
     * @return ITaxDescription
     */
    public function getEmptyTaxDescription();
}
