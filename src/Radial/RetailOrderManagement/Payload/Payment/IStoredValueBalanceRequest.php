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

/**
 * Interface IStoredValueBalanceRequest
 * @package Radial\RetailOrderManagement\Payload\Payment
 */
interface IStoredValueBalanceRequest extends IStoredValueBalance
{
    const ROOT_NODE = 'StoredValueBalanceRequest';

    /**
     * RequestId is used to globally identify a request message and is used
     * for duplicate request protection.
     *
     * xsd restrictions: 1-40 characters
     * @return string
     */
    public function getRequestId();

    /**
     * @param string $requestId
     * @return self
     */
    public function setRequestId($requestId);

    /**
     * The personal identification number or code associated with a gift card or gift certificate.
     *
     * xsd restrictions: 1-8 characters.
     * @return string
     */
    public function getPin();

    /**
     * @param string
     * @return self
     */
    public function setPin($pin);

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getCurrencyCode();

    /**
     * @param string $code
     * @return self
     */
    public function setCurrencyCode($code);
}
