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

namespace Radial\RetailOrderManagement\Payload\Payment;

use Radial\RetailOrderManagement\Payload\IPayload;
/**
 * Interface IConfirmFundsReply
 * @package Radial\RetailOrderManagement\Payload\Payment
 */
interface IConfirmFundsReply extends IPayload, IPaymentContext
{
    // XML related values - document root node, XMLNS and name of the xsd schema file
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'ConfirmFundsReply';
    const XSD = '/checkout/1.0/Payment-Service-ConfirmFunds-1.0.xsd';

    /**
     * 	Are the funds still available for the payment?
     * @return string Success/Fail/Timeout
     */
    public function getFundsAvailable();

    /**
     * Should downstream systems consider this reply a success?
     *
     * @return bool
     */
    public function isSuccess();

    /**
     * Did upstream systems report a timeout?
     *
     * @return bool
     */
    public function isTimeout();
}
