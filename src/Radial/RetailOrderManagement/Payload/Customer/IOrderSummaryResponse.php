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

namespace Radial\RetailOrderManagement\Payload\Customer;

use Radial\RetailOrderManagement\Payload\IPayload;

interface IOrderSummaryResponse extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'OrderSummaryResponse';
    const XSD = '/checkout/1.0/Order-Service-Search-1.0.xsd';
    const ORDER_SUMMARY_ITERABLE_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\Customer\IOrderSummaryIterable';

    /**
     * Get all order summary responses.
     * @return IOrderSummaryIterable
     */
    public function getOrderSummaries();

    /**
     * @param IOrderSummaryIterable
     * @return self
     */
    public function setOrderSummaries(IOrderSummaryIterable $orderSummaries);
}
