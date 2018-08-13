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
 * Interface IProtectPanReply The Reply Message for the Pan Tokenization Operation
 * @package Radial\RetailOrderManagement\Payload\Payment
 */
interface IProtectPanReply extends IPayload
{
    const ROOT_NODE = 'ProtectPanReply';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = '/checkout/1.0/Payment-Service-ProtectPan-1.0.xsd';

    /**
     * Token representing actual Payment Account Number (PAN). Payment card numbers are found on payment cards,
     * such as credit cards and debit cards, as well as stored-value cards, gift cards and other similar cards.
     * Some card issuers refer to the card number as the primary account number or PAN.
     *
     * @return string
     */
    public function getToken();
}
