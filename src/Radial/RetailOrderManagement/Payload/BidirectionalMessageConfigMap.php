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
    $map = [];
    $map['address/validate'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Address\ValidationRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Address\ValidationReply',
    ];
    $map['orders/create'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Order\OrderCreateRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Order\OrderCreateReply',
    ];
    $map['payments/creditcard/auth'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\CreditCardAuthReply',
    ];
    $map['payments/storedvalue/balance'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\StoredValueBalanceRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\StoredValueBalanceReply',
    ];
    $map['payments/storedvalue/redeem'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\StoredValueRedeemRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\StoredValueRedeemReply',
    ];
    $map['payments/storedvalue/redeemvoid'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidReply',
    ];
    $map['payments/paypal/setExpress'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutReply',
    ];
    $map['payments/paypal/getExpress'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalGetExpressCheckoutRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalGetExpressCheckoutReply',
    ];
    $map['payments/paypal/doExpress'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalDoExpressCheckoutRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalDoExpressCheckoutReply',
    ];
    $map['payments/paypal/doAuth'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalDoAuthorizationRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalDoAuthorizationReply',
    ];
    $map['payments/paypal/void'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalDoVoidRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\PayPalDoVoidReply',
    ];
    $map['taxes/quote'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteReply',
    ];
    $map['taxes/invoice'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeInvoiceRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeInvoiceReply',
    ];
    $map['orders/cancel'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Order\OrderCancelRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Order\OrderCancelResponse',
    ];
    $map['customers/orders/get'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Customer\OrderSummaryRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Customer\OrderSummaryResponse',
    ];
    $map['inventory/quantity/get'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Inventory\QuantityRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Inventory\QuantityReply',
    ];
    $map['inventory/details/get'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Inventory\InventoryDetailsRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Inventory\InventoryDetailsReply',
    ];
    $map['orders/get'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailResponse',
    ];
    $map['inventory/allocations/create'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Inventory\AllocationRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Inventory\AllocationReply',
    ];
    $map['inventory/allocations/delete'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Inventory\AllocationRollbackRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Inventory\AllocationRollbackReply',
    ];
    $map['payments/tendertype/lookup'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\TenderType\LookupRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\TenderType\LookupReply',
    ];
    $map['payments/pan/protect'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\ProtectPanRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\ProtectPanReply',
    ];
    $map['payments/settlement/create'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\PaymentSettlementRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\PaymentSettlementReply',
    ];
    $map['payments/auth/cancel'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\PaymentAuthCancelRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\PaymentAuthCancelReply',
    ];
    $map['payments/funds/confirm'] = [
        'request' => '\Radial\RetailOrderManagement\Payload\Payment\ConfirmFundsRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\ConfirmFundsReply',
    ];
    $map['payments/publickey/lookup'] = [
	'request' => '\Radial\RetailOrderManagement\Payload\Payment\PublicKeyRequest',
        'reply' => '\Radial\RetailOrderManagement\Payload\Payment\PublicKeyReply',
    ];
    return $map;
});
