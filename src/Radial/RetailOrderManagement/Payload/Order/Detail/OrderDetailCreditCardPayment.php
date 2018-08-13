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

namespace Radial\RetailOrderManagement\Payload\Order\Detail;

use DateTime;
use Radial\RetailOrderManagement\Payload\IPayload;
use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Radial\RetailOrderManagement\Payload\PayloadFactory;
use Radial\RetailOrderManagement\Payload\Payment\TAmount;
use Radial\RetailOrderManagement\Payload\TPayload;
use Radial\RetailOrderManagement\Payload\Order\CreditCardPayment;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderDetailCreditCardPayment extends CreditCardPayment implements IOrderDetailCreditCardPayment
{
    protected function serializeContents()
    {
        // Making sure an empty request id node don't serialized when it's empty.
        if (trim($this->getPaymentRequestId()) === '') {
            $this->setPaymentRequestId(null);
        }
        return $this->serializePaymentContext()
            . $this->serializePaymentRequestId()
            . $this->serializeOptionalAmount('Amount', $this->getAmount())
            . $this->serializeAuthorizations()
            . $this->serializeOptionalDateValue('ExpirationDate', 'Y-m', $this->getExpirationDate())
            . $this->serializeOptionalDateValue('StartDate', 'Y-m', $this->getStartDate())
            . $this->serializeOptionalXmlEncodedValue('IssueNumber', $this->getIssueNumber())
            . $this->serializeSecureVerificationData()
            . $this->serializeOptionalXmlEncodedValue('PurchasePlanCode', $this->getPurchasePlanCode())
            . $this->serializeOptionalXmlEncodedValue('PurchasePlanDescription', $this->getPurchasePlanDescription())
            . $this->getCustomAttributes()->serialize();
    }
}
