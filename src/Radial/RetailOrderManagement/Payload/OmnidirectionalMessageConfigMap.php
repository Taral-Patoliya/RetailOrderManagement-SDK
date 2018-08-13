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

/**
 * Wrap include in a function to allow variables while protecting scope.
 * @return array mapping of config keys to message payload types for bidirectional api operations
 */
return call_user_func(function () {
    $orderEvents = '\Radial\RetailOrderManagement\Payload\OrderEvents';
    $riskEvents = '\Radial\RetailOrderManagement\Payload\Risk';
    $map = [];
    $map['OrderAccepted'] = "$orderEvents\OrderAccepted";
    $map['OrderBackorder'] = "$orderEvents\OrderBackorder";
    $map['OrderCancelled'] = "$orderEvents\OrderCancel";
    $map['OrderConfirmed'] = "$orderEvents\OrderConfirmed";
    $map['OrderCreditIssued'] = "$orderEvents\OrderCreditIssued";
    $map['OrderGiftCardActivation'] = "$orderEvents\OrderGiftCardActivation";
    $map['OrderPriceAdjustment'] = "$orderEvents\OrderPriceAdjustment";
    $map['OrderRejected'] = "$orderEvents\OrderRejected";
    $map['OrderReturnInTransit'] = "$orderEvents\OrderReturnInTransit";
    $map['OrderShipped'] = "$orderEvents\OrderShipped";
    $map['PaymentSettlementStatus'] = "$orderEvents\SettlementStatus";
    $map['PaymentAuthCancelReply'] = "$orderEvents\PaymentAuthCancel";
    $map['Test'] = "$orderEvents\TestMessage";
    $map['RiskAssessmentReply'] = "$riskEvents\RiskAssessmentReply";
    $map['Fault_DUPLICATE'] = "$riskEvents\FaultDuplicate";
    return $map;
});
