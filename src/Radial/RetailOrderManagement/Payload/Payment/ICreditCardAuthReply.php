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
 * Interface ICreditCardAuthReply The Reply Message for the Credit Card Authorization Operation
 * @package Radial\RetailOrderManagement\Payload\Payment
 */
interface ICreditCardAuthReply extends ICreditCardAuth
{
    const ROOT_NODE = 'CreditCardAuthReply';

    /**
     * Response code of the credit card authorization. This includes approval, timeout, and several decline codes.
     * Please see supporting documentation for a full list of these codes.
     *
     * @return string
     */
    public function getAuthorizationResponseCode();

    /**
     * Authorization Code returned by the payment processor upon a successful credit card auth.
     * Any order taken by the Order Service that is paid for by Credit Card MUST have this authorization code.
     *
     * @return string
     */
    public function getBankAuthorizationCode();

    /**
     * Payment Processor Response for CVV2 (Card Verification Value) check.
     * For most credit cards, you will get an Approval on the AuthorizationResponseCode,
     * even though CVV2ResponseCode returns a CVV2 failure.
     * You CANNOT accept an order where CVV2ResponseCode returns a CVV2 failure code.
     * Please see supporting documentation for a full list of these codes.
     *
     * @return string
     */
    public function getCVV2ResponseCode();

    /**
     * Payment Processor Response for the Address Verification System check.
     * For most credit cards, you will get an Approval on the AuthorizationResponseCode, even
     * though AVSResponseCode returns an AVS failure code.  That said, it is typically considered a significant fraud
     * risk to accept an order where AVSResponseCode returns an AVS failure code.
     * Please see supporting documentation for a full list of these codes.
     *
     * @return string
     */
    public function getAVSResponseCode();

    /**
     * Response code for customer phone number verification (only applies to Amex auths).  This data should be
     * included in the OrderCreateRequest for Orders paid for with Amex to support downstream fraud processing.
     * @return string
     */
    public function getPhoneResponseCode();

    /**
     * Response code for customer name verification (only applies to Amex auths). This data should be
     * included in the OrderCreateRequest for Orders paid for with Amex to support downstream fraud processing.
     *
     * @return string
     */
    public function getNameResponseCode();

    /**
     * Response code for customer email verification (only applies to Amex auths). This data should be
     * included in the OrderCreateRequest for Orders paid for with Amex to support downstream fraud processing.
     *
     * @return string
     */
    public function getEmailResponseCode();

    /**
     * The amount authorized by the credit card processor.
     * Includes a required attribute for a three character ISO currency code.
     *
     * @return float
     */
    public function getAmountAuthorized();

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getCurrencyCode();

    /**
     * Address verification was successful, no resend required
     *
     * @return bool
     */
    public function getIsAVSSuccessful();

    /**
     * If the auth was approved but AVS failed, the address needs to be corrected.
     * @return bool
     */
    public function getIsAVSCorrectionRequired();

    /**
     * CSC verification was successful, no resend required
     * @return bool
     */
    public function getIsCVV2Successful();

    /**
     * If the auth was approved but CVV failed, the CVV needs to be corrected.
     * @return bool
     */
    public function getIsCVV2CorrectionRequired();

    /**
     * Was the authorization was approved.
     * @return bool
     */
    public function getIsAuthApproved();

    /**
     * Did the authorization resulted in a timeout response.
     * @return bool
     */
    public function getIsAuthTimeout();

    /**
     * Was the credit card auth an unqualified success - no errors or failed response codes.
     * @return bool
     */
    public function getIsAuthSuccessful();

    /**
     * Can the credit card auth reply be accepted.
     * True if the reply was successful or the request reported a timeout.
     * @return bool
     */
    public function getIsAuthAcceptable();

    /**
     * Authorization response code acceptable to send to the OMS.
     * Only valid values for the OMS are "APPROVED" or "TIMEOUT".
     * @return string
     */
    public function getResponseCode();
   
    /**
     * Authorization response code acceptable to send to the OMS.
     * Only valid values for the OMS are "APPROVED" or "TIMEOUT".
     * Note this may duplicate the above.
     * @return string
     */
    public function getRiskResponseCode();
}
