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
 * @return array mapping of config keys to payload configurations.
 */
return call_user_func(function () {
    $map = []; // This is what we eventually return

    // Common types injected into payloads, referenced in payload mappings.

    // validator types
    $iterableValidator = '\Radial\RetailOrderManagement\Payload\Validator\IterablePayload';
    $optionalGroupValidator = '\Radial\RetailOrderManagement\Payload\Validator\OptionalGroup';
    $optionalSubpayloadValidator = '\Radial\RetailOrderManagement\Payload\Validator\OptionalSubpayloads';
    $requiredFieldsValidator = '\Radial\RetailOrderManagement\Payload\Validator\RequiredFields';
    $subpayloadValidator = '\Radial\RetailOrderManagement\Payload\Validator\Subpayloads';
    // payload validator iterables - contain validator types
    $validatorIterator = '\Radial\RetailOrderManagement\Payload\ValidatorIterator';
    // xsd, xml or other schema validator types
    $xmlValidator = '\Radial\RetailOrderManagement\Payload\Validator\XmlValidator';
    $xsdSchemaValidator = '\Radial\RetailOrderManagement\Payload\Validator\XsdSchemaValidator';
    // payload map types
    $payloadMap = '\Radial\RetailOrderManagement\Payload\PayloadMap';

    // Common sets of required fields, typically consisting of required fields
    // implemented by a common trait and reused by multiple payloads.
    $paymentAccountUniqueIdParams = ['getCardNumber'];
    $paymentContextParams = array_merge($paymentAccountUniqueIdParams, ['getOrderId']);
    $shippingAddressParams = ['getShipToLines', 'getShipToCity', 'getShipToCountryCode'];
    $physicalAddressParams = ['getLines', 'getCity', 'getCountryCode'];
    $personNameParams = ['getLastName', 'getFirstName'];
    $orderEventItemParams = ['getLineNumber', 'getItemId', 'getQuantity', 'getTitle'];
    $orderPaymentContextParams = ['getOrderId', 'getTenderType', 'getPanIsToken', 'getAccountUniqueId', 'getPaymentRequestId'];
    $customAttributeParams = ['getKey', 'getValue'];
    $iLineItemContainerParams = ['getLineItemsTotal', 'getShippingTotal', 'getTaxTotal',];
    $taxDataParams = ['getType', 'getTaxability', 'getSitus', 'getEffectiveRate', 'getCalculatedTax'];
    $addressValidationHeaderParams = ['getMaxSuggestions'];
    $inventoryAddressParams = ['getAddressLines', 'getAddressCity', 'getAddressCountryCode'];

    // Payload validators shared by multiple payloads.
    $prepaidPaymentValidators = [
        ['validator' => $requiredFieldsValidator, 'params' => ['getAmount']],
        ['validator' => $optionalSubpayloadValidator, 'params' => ['getCustomAttributes']],
    ];

    // Child payload configuration for payment payloads with payment line items.
    $iLineItemIterableChildPayloads = [
        'payloadMap' => $payloadMap,
        'types' => [
            '\Radial\RetailOrderManagement\Payload\Payment\ILineItemIterable' =>
                '\Radial\RetailOrderManagement\Payload\Payment\LineItemIterable'
        ]
    ];
    // Common configuration for a payload that does not include child payloads.
    $noChildPayloads = [
        'payloadMap' => $payloadMap,
        'types' => [],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PublicKeyRequest'] = [
        'validators' => [
	    [
		'validator' => $requiredFieldsValidator,
                'params' => [
                    'getAlgorithmVersion',
                ]
	    ]
        ],
	'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PublicKeyReply'] = [
        'validators' => [
	    [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getPublicKey',
                ]
	    ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\ConfirmFundsRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getAmount',
                    'getCurrencyCode',
                ]
            ],
	    [
		'validator' => $optionalGroupValidator,
		'params' => [
			'getPerformReauthorization',
		]
	    ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\ConfirmFundsReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getFundsAvailable',
                ]
            ],
	    [
		'validator' => $optionalGroupValidator,
		'params' => [
		    'getReauthorizationAttempted',
		]
	    ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PaymentSettlementRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getAmount',
                    'getCurrencyCode',
                    'getTaxAmount',
                    'getSettlementType',
                    'getClientContext',
                    'getFinalDebit',
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PaymentSettlementReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => []
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\SettlementStatus'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getAmount',
                    'getCurrencyCode',
                    'getSettlementType',
                    'getSettlementStatus',
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PaymentAuthCancelRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getAmount',
                    'getCurrencyCode',
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PaymentAuthCancelReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => []
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\PaymentAuthCancel'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getAmount',
                    'getCurrencyCode',
                    'getTenderType',
                    'getResponseCode',
                ]
	    ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    $shippingAddressParams,
                    [
                        'getRequestId',
                        'getExpirationDate',
                        'getAmount',
                        'getCurrencyCode',
                        'getBillingFirstName',
                        'getBillingLastName',
                        'getBillingPhone',
                        'getBillingLines',
                        'getBillingCity',
                        'getBillingCountryCode',
                        'getEmail',
                        'getIp',
                        'getShipToFirstName',
                        'getShipToLastName',
                        'getShipToPhone',
                        'getIsRequestToCorrectCVVOrAVSError',
                    ]
                ),
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => [
                    'getAuthenticationAvailable',
                    'getAuthenticationStatus',
                    'getCavvUcaf',
                    'getTransactionId',
                    'getPayerAuthenticationResponse',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\CreditCardAuthReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    [
                        'getAuthorizationResponseCode',
                        'getBankAuthorizationCode',
                        'getCvv2ResponseCode',
                        'getAvsResponseCode',
                        'getAmountAuthorized',
                        'getCurrencyCode',
                    ]
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\StoredValueBalanceRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentAccountUniqueIdParams,
                    ['getCurrencyCode']
                ),
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => [
                    'getPin',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\StoredValueBalanceReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentAccountUniqueIdParams,
                    [
                        'getResponseCode',
                        'getBalanceAmount',
                        'getCurrencyCode',
                    ]
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\StoredValueRedeemRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    [
                        'getRequestId',
                        'getAmount',
                        'getCurrencyCode',
                    ]
                ),
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => [
                    'getPin',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\StoredValueRedeemReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    [
                        'getResponseCode',
                        'getAmountRedeemed',
                        'getCurrencyCodeRedeemed',
                        'getBalanceAmount',
                        'getBalanceCurrencyCode',
                    ]
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidRequest'] = [
        'validators' =>
            $map['\Radial\RetailOrderManagement\Payload\Payment\StoredValueRedeemRequest']['validators'],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    [
                        'getResponseCode',
                    ]
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\TestMessage'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getTimestamp',],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderAccepted'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrencyCode',
                    'getCurrencySymbol',
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getOrderAcceptedSource',
                    'getTotalAmount',
                    'getTaxAmount',
                    'getVatTaxAmount',
                    'getSubtotalAmount',
                    'getDutyAmount',
                    'getFeesAmount',
                    'getDiscountAmount',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getOrderItems',
                    'getPayments',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\AcceptedOrderItemIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\AcceptedOrderItem',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IPaymentIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\OrderAcceptedPaymentIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderShipped'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrencyCode',
                    'getCurrencySymbol',
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getTotalAmount',
                    'getTaxAmount',
                    'getSubtotalAmount',
                    'getDutyAmount',
                    'getFeesAmount',
                    'getDiscountAmount',
                    'getShippedAmount',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getOrderItems',
                    'getShippingDestination',
                    'getPayments',
                    'getTaxDescriptions'
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IMailingAddress' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\MailingAddress',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails',
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\ShippedOrderItemIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\ShippedOrderItem',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IPaymentIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\PaymentIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\ITaxDescriptionIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\TaxDescriptionIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderReturnInTransit'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getCurrencyCode',
                    'getCurrencySymbol',
                ],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getLoyaltyPrograms'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderCancel'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getCancelReason',
                    'getCancelReasonCode',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getOrderItems',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\CancelledOrderItemIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\CancelledOrderItem',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgram' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgram',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgram'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getAccount',
                    'getProgram',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getCustomAttributes',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\CustomAttributeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttribute' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\CustomAttribute',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\CustomAttribute'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getKey',
                    'getValue',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\TrackingNumberIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\ITrackingNumber' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\TrackingNumber',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\TrackingNumber'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getTrackingNumber',
                    'getUrl',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\MailingAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($physicalAddressParams, $personNameParams),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\PaymentIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IPayment' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\Payment',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderAcceptedPaymentIterable'] =
        $map['\Radial\RetailOrderManagement\Payload\OrderEvents\PaymentIterable'];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\Payment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getDescription',
                    'getTenderType',
                    'getMaskedAccount',
                    'getAmount',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\TaxDescriptionIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\ITaxDescription' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\TaxDescription',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\TaxDescription'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getDescription',
                    'getAmount',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\OrderItem',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\ShippedOrderItemIterable'] =
        $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\CancelledOrderItemIterable'] =
        $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\AcceptedOrderItemIterable'] =
        $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderEventItemParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\AcceptedOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderEventItemParams,
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getShipmentMethod', 'getShipmentMethodDisplayText'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getDestination'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IMailingAddress' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\MailingAddress',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\ShippedOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($orderEventItemParams, ['getShippedQuantity']),
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getTrackingNumbers'
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\ITrackingNumberIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\TrackingNumberIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\CancelledOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderEventItemParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\LineItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getName',
                    'getQuantity',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\LineItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Payment\ILineItem' =>
                    '\Radial\RetailOrderManagement\Payload\Payment\LineItem'
            ]
        ]
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getResponseCode',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getReturnUrl',
                    'getCancelUrl',
                    'getLocaleCode',
                    'getAmount',
                    'getAddressOverride',
                    'getCurrencyCode',
                ]
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => $shippingAddressParams
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => $iLineItemContainerParams
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $iLineItemIterableChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalGetExpressCheckoutReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getResponseCode',
                ]
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => [
                    'getPayerFirstName',
                    'getPayerLastName',
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalGetExpressCheckoutRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getToken',
                    'getCurrencyCode',
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalDoExpressCheckoutRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $shippingAddressParams,
                    [
                        'getRequestId',
                        'getOrderId',
                        'getPayerId',
                        'getAmount',
                    ]
                )
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => $shippingAddressParams,
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => $iLineItemContainerParams
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $iLineItemIterableChildPayloads
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalDoExpressCheckoutReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getResponseCode',
                    'getTransactionId',
                    'getOrderId',
                    'getPaymentStatus',
                    'getPendingReason',
                    'getReasonCode',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalDoAuthorizationRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getRequestId',
                    'getOrderId',
                    'getCurrencyCode',
                    'getAmount'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalDoAuthorizationReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getResponseCode',
                    'getPaymentStatus',
                    'getPendingReason',
                    'getReasonCode'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalDoVoidRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getRequestId',
                    'getOrderId',
                    'getCurrencyCode'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\PayPalDoVoidReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getResponseCode'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderRejected'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCustomerOrderId', 'getStoreId', 'getOrderCreateTimestamp'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderBackorder'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getCurrencyCode',
                    'getCurrencySymbol',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getShipGroups',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IMailingAddress' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\MailingAddress',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails',
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\BackOrderItemIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\PricedOrderItem',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IShipGroupIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\ShipGroupIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IShipGroup' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\BackOrderShipGroup',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IEddMessage' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\EddMessage',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\BackOrderItemIterable'] =
        $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\PricedOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderEventItemParams,
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\ShipGroupIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IShipGroup' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\ShipGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\EddMessage'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderCreditIssued'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getReturnOrCredit',
                    'getReferenceNumber',
                    'getTotalCredit'
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getOrderItems'
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\CreditOrderItemIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\CreditOrderItem'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\CreditOrderItemIterable'] =
        $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\CreditOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($orderEventItemParams, ['getRemainingQuantity']),
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderConfirmed'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getCurrencyCode',
                    'getCurrencySymbol',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getShipGroups',
                    'getPayments',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IMailingAddress' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\MailingAddress',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails',
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedOrderItemIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\PricedOrderItem',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IShipGroupIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\ShipGroupIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IShipGroup' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedShipGroup',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IPaymentIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedPaymentIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getShipmentMethod',
                    'getShippingDestination',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getOrderItems',
                    'getShippingDestination',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\BackOrderShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getShipmentMethod',
                    'getShippingDestination',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getOrderItems',
                    'getShippingDestination',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IEddMessage' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\EddMessage',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedOrderItemIterable'] =
        $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedPaymentIterable'] =
        $map['\Radial\RetailOrderManagement\Payload\OrderEvents\PaymentIterable'];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderPriceAdjustment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrencyCode',
                    'getCurrencySymbol',
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getTotalAmount',
                    'getTaxAmount',
                    'getSubtotalAmount',
                    'getDutyAmount',
                    'getFeesAmount',
                    'getDiscountAmount',
                    'getShippedAmount',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getOrderItems',
                    'getPerformedAdjustments',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IPerformedAdjustmentIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\PerformedAdjustmentIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IAdjustedOrderItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\AdjustedOrderItemIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\PerformedAdjustmentIterable'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => []
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IPerformedAdjustment' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\PerformedAdjustment',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\PerformedAdjustment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getType',
                    'getDisplay'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\AdjustedOrderItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IAdjustedOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\AdjustedOrderItem',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\AdjustedOrderItem'] =[
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getLineNumber', 'getItemId', 'getTitle', 'getAdjustments'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IItemPriceAdjustmentIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\ItemPriceAdjustmentIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\ItemPriceAdjustmentIterable'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => []
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IItemPriceAdjustment' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\ItemPriceAdjustment',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\ItemPriceAdjustment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getModificationType',
                    'getAdjustmentCategory',
                    'getIsCredit',
                    'getAmount'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\OrderGiftCardActivation'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrencyCode',
                    'getCurrencySymbol',
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getGiftCardActivations'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getLoyaltyPrograms'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IGiftCardActivationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\GiftCardActivationIterable',
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IGiftCardActivation' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\GiftCardActivation',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\GiftCardActivationIterable'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\OrderEvents\IGiftCardActivation' =>
                    '\Radial\RetailOrderManagement\Payload\OrderEvents\GiftCardActivation',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\OrderEvents\GiftCardActivation'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderCreateReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getStatus',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderCreateRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getRequestId',
                    'getOrderId',
                    'getCreateTime',
                    'getBillingAddress',
                    'getCurrency',
                    'getLocale',
                    'getOrderTotal',
                    'getHostname',
                    'getIpAddress',
                    'getSessionId',
                    'getUserAgent',
                    'getJavascriptData',
                    'getReferrer',
                    'getContentTypes',
                    'getEncoding',
                    'getLanguage',
                ]
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getAssociateName', 'getAssociateNumber', 'getAssociateStore']
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getOrderSource', 'getOrderSourceType']
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getOrderItems',
                    'getShipGroups',
                    'getDestinations',
                ],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getPayments',
                    'getItemRelationships',
                    'getHolds',
                    'getCustomAttributes',
                    'getTemplates',
                    'getOrderContextCustomAttributes',
                ],
            ],
            [
                'validator' => '\Radial\RetailOrderManagement\Payload\Validator\Order\OrderCreateReferences',
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\LoyaltyProgramIterable',
                '\Radial\RetailOrderManagement\Payload\Order\IOrderItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\OrderItemIterable',
                '\Radial\RetailOrderManagement\Payload\Order\IShipGroupIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ShipGroupIterable',
                '\Radial\RetailOrderManagement\Payload\Checkout\IDestinationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\OrderDestinationIterable',
                '\Radial\RetailOrderManagement\Payload\Order\IPaymentIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PaymentIterable',
                '\Radial\RetailOrderManagement\Payload\Order\IItemRelationshipIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ItemRelationshipIterable',
                '\Radial\RetailOrderManagement\Payload\Order\IOrderHoldIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\OrderHoldIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ITemplateIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\TemplateIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Checkout\MailingAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Checkout\InvoiceTextCode'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCode'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Checkout\InvoiceTextCodeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Checkout\IInvoiceTextCode' =>
                    '\Radial\RetailOrderManagement\Payload\Checkout\InvoiceTextCode',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Checkout\Tax'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $taxDataParams,
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getInvoiceTextCodes'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Checkout\IInvoiceTextCodeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Checkout\InvoiceTextCodeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\CreditCardPayment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $orderPaymentContextParams,
                    [
                        'getResponseCode',
                        'getBankAuthorizationCode',
                        'getCvv2ResponseCode',
                        'getAvsResponseCode',
                    ]
                ),
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\CustomAttribute'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getKey', 'getValue'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttribute' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttribute',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Customization'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCustomizedItemId'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomizationInstructions', 'getExtendedPrice'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomizationInstructionIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomizationInstructionIterable',
                '\Radial\RetailOrderManagement\Payload\Order\IPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PriceGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\CustomizationInstruction'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $customAttributeParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\CustomizationInstructionIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomizationInstruction' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomizationInstruction',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\CustomizationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomization' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Customization',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Discount'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getAmount'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getTaxes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ITaxIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\TaxIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\DiscountIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IDiscount' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Discount'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\EmailAddressDestination'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getEmailAddress'],
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getFirstName', 'getLastName'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Fee'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getType', 'getAmount', 'getItemId'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getTaxes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ITaxIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\TaxIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ITax' =>
                    '\Radial\RetailOrderManagement\Payload\Checkout\Tax',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\FeeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IFee' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Fee',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\ItemRelationship'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getParentItemId', 'getType'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getItemReferences'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IOrderItemReferenceIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\OrderItemReferenceIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\ItemRelationshipIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IItemRelationship' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ItemRelationship',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\LoyaltyProgram'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getAccount', 'getProgram'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\LoyaltyProgramIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgram' =>
                    '\Radial\RetailOrderManagement\Payload\Order\LoyaltyProgram',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\MailingAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $physicalAddressParams,
                    ['getId']
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderDestinationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IMailingAddress' =>
                    '\Radial\RetailOrderManagement\Payload\Order\MailingAddress',
                '\Radial\RetailOrderManagement\Payload\Order\IStoreLocation' =>
                    '\Radial\RetailOrderManagement\Payload\Order\StoreLocation',
                '\Radial\RetailOrderManagement\Payload\Order\IEmailAddressDestination' =>
                    '\Radial\RetailOrderManagement\Payload\Order\EmailAddressDestination',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderHold'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getType', 'getHoldDate', 'getReason', 'getStatusDescription'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderHoldIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IOrderHold' =>
                    '\Radial\RetailOrderManagement\Payload\Order\OrderHold'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getId',
                    'getLineNumber',
                    'getItemId',
                    'getQuantity',
                ],
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getEstimatedDeliveryWindowFrom', 'getEstimatedDeliveryWindowTo']
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getEstimatedShippingWindowFrom', 'getEstimatedShippingWindowTo']
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getMerchandisePricing'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => [
                    'getShippingPricing',
                    'getDutyPricing',
                    'getFees',
                    'getStoreFrontDetails',
                    'getProxyPickupDetails',
                    'getGiftPricing',
                    'getCustomizationBasePrice',
                    'getCustomizations',
                    'getCustomAttributes',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PriceGroup',
                '\Radial\RetailOrderManagement\Payload\Order\IStoreFrontDetails' =>
                    '\Radial\RetailOrderManagement\Payload\Order\StoreFrontDetails',
                '\Radial\RetailOrderManagement\Payload\Order\IProxyPickupDetails' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ProxyPickupDetails',
                '\Radial\RetailOrderManagement\Payload\Order\IFeeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\FeeIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ICustomizationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomizationIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\Order\OrderItem'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderItemReference'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getReferencedItemId'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderItemReferenceIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IOrderItemReference' =>
                    '\Radial\RetailOrderManagement\Payload\Order\OrderItemReference'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\PaymentIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICreditCardPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CreditCardPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IPrepaidCreditCardPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PrepaidCreditCardPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IPointsPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PointsPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IStoredValueCardPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\StoredValueCardPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IPayPalPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PayPalPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IPrepaidCashOnDeliveryPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PrepaidCashOnDeliveryPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IReservationPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ReservationPayment',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\PayPalPayment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $orderPaymentContextParams,
                    [
                        'getAmount',
                        'getAmountAuthorized',
                        'getAuthorizationResponseCode',
                    ]
                ),
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\PointsPayment'] = [
        'validators' => $prepaidPaymentValidators,
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\PrepaidCashOnDeliveryPayment'] = [
        'validators' => $prepaidPaymentValidators,
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\PrepaidCreditCardPayment'] = [
        'validators' => $prepaidPaymentValidators,
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\PriceGroup'] = [
        'validators' => [
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getTaxes', 'getDiscounts'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IDiscountIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\DiscountIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ITaxIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\TaxIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\ProxyPickupDetails'] = [
        'validators' => [
            [
                'validator' => $optionalGroupValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\ReservationPayment'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\ShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getChargeType', 'getId'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getItemReferences'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getGiftPricing'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IOrderItemReferenceIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\OrderItemReferenceIterable',
                '\Radial\RetailOrderManagement\Payload\Order\IPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PriceGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\ShipGroupIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IShipGroup' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ShipGroup'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\StoredValueCardPayment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderPaymentContextParams,
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\StoreFrontDetails'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\StoreLocation'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $physicalAddressParams,
                    ['getId']
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Tax'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $taxDataParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\TaxIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ITax' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Tax'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Template'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($customAttributeParams, ['getId']),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\TemplateIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ITemplate' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Template'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Address\ValidationReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($physicalAddressParams, $addressValidationHeaderParams),
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getSuggestedAddresses', 'getErrorLocations'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Address\ISuggestedAddressIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Address\SuggestedAddressIterable',
                '\Radial\RetailOrderManagement\Payload\Address\IErrorLocationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Address\ErrorLocationIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Address\ValidationRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($physicalAddressParams, $addressValidationHeaderParams),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Address\SuggestedAddressIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Address\ISuggestedAddress' =>
                    '\Radial\RetailOrderManagement\Payload\Address\SuggestedAddress',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Address\SuggestedAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getErrorLocations'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Address\IErrorLocationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Address\ErrorLocationIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Address\ErrorLocationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Address\IErrorLocation' =>
                    '\Radial\RetailOrderManagement\Payload\Address\ErrorLocation',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Address\ErrorLocation'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getFieldName'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrency',
                    'getBillingInformation',
                ]
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getShipGroups',
                    'getDestinations',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IShipGroupIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ShipGroupIterable',
                '\Radial\RetailOrderManagement\Payload\Checkout\IDestinationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\DestinationIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeInvoiceRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
		    'getOrderId',
		    'getInvoiceNumber',
		    'getOrderDateTime',
		    'getShipDateTime',
                    'getCurrency',
		    'getVatInclusivePricingFlag',
                    'getBillingInformation',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getShipGroups',
                    'getDestinations',
		    'getFees',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedShipGroupIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedShipGroupIterable',
                '\Radial\RetailOrderManagement\Payload\Checkout\IDestinationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\DestinationIterable',
		'\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedFeeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedFeeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeInvoiceReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => []
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\ShipGroupIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IShipGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ShipGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\ShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getChargeType'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getItems'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getGiftPricing'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IOrderItemRequestIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\OrderItemRequestIterable',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\MerchandisePriceGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\OrderItemRequestIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\OrderItemRequest',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\DestinationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IMailingAddress' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\MailingAddress',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IEmailAddressDestination' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\EmailAddressDestination',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\MailingAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($physicalAddressParams, $personNameParams),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\EmailAddressDestination'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getEmailAddress'],
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getFirstName', 'getLastName'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\OriginPhysicalAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\OrderItemRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getLineNumber',
                    'getItemId',
                    'getQuantity',
                    'getAdminOrigin',
                    'getShippingOrigin',
                ],
            ],
	    [
                'validator' => $subpayloadValidator,
                'params' => ['getMerchandisePricing'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => [
                    'getShippingPricing',
                    'getDutyPricing',
                    'getFees',
                    'getGiftPricing',
                    'getCustomizationBasePricing',
                    'getCustomizations',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IOriginPhysicalAddress' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\OriginPhysicalAddress',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\MerchandisePriceGroup',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IDutyPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\DutyPriceGroup',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IFeeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\FeeIterable',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ICustomizationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\CustomizationIterable',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\FeeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IFee' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\Fee',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\Fee'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getType'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getCharge'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\CustomizationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ICustomization' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\Customization',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\Discount'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getAmount'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\DiscountIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IDiscount' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\Discount'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\EmailAddressDestination'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getEmailAddress'],
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getFirstName', 'getLastName'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup'] = [
        'validators' => [
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getDiscounts'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IDiscountIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\DiscountIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\MerchandisePriceGroup'] =
        $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup'];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\DutyPriceGroup'] =
        $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup'];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteReply'] = [
        'validators' => [
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getShipGroups',
                    'getDestinations',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedShipGroupIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedShipGroupIterable',
                '\Radial\RetailOrderManagement\Payload\Checkout\IDestinationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\DestinationIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedShipGroupIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedShipGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedShipGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getChargeType'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getItems'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getGiftPricing'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedOrderItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedOrderItemIterable',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedMerchandisePriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedMerchandisePriceGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getLineNumber',
                    'getItemId',
                    'getQuantity',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getMerchandisePricing'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => [
		    'getAdminOrigin',
                    'getShippingOrigin',
                    'getShippingPricing',
                    'getDutyPricing',
                    'getFees',
                    'getGiftPricing',
                    'getCustomizationBasePricing',
                    'getCustomizations',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
		'\Radial\RetailOrderManagement\Payload\TaxDutyFee\IOriginPhysicalAddress' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\OriginPhysicalAddress',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedMerchandisePriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedMerchandisePriceGroup',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedDutyPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedDutyPriceGroup',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedFeeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedFeeIterable',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedCustomizationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomizationIterable',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedCustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup'] = [
        'validators' => [
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getDiscounts', 'getTaxes'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedDiscountIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedDiscountIterable',
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedMerchandisePriceGroup'] =
        $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup'];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedDutyPriceGroup'] =
        $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup'];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedOrderItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedOrderItem' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedOrderItem',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedFeeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedFee' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedFee',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomizationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedCustomization' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomization',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Checkout\ITax' =>
                    '\Radial\RetailOrderManagement\Payload\Checkout\Tax',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedDiscountIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedDiscount' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedDiscount'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedDiscount'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getAmount'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getTaxes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxIterable' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxIterable'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedFee'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getType'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getCharge'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\Customization'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getItemId'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getUpCharge'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\MerchandisePriceGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomization'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getItemId'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getUpCharge'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\TaxDutyFee\ITaxedMerchandisePriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\TaxDutyFee\TaxedMerchandisePriceGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderCancelRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCustomerOrderId', 'getReasonCode'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\OrderCancelResponse'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getResponseStatus'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Customer\OrderSummaryRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Customer\IOrderSearch' =>
                    '\Radial\RetailOrderManagement\Payload\Customer\OrderSearch',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Customer\OrderSearch'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Customer\OrderSummaryResponse'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' =>  [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Customer\IOrderSummaryIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Customer\OrderSummaryIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Customer\OrderSummaryIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Customer\IOrderSummary' =>
                    '\Radial\RetailOrderManagement\Payload\Customer\OrderSummary',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Customer\OrderSummary'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getModifiedTime', 'getOrderDate', 'getOrderTotal'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\QuantityRequest'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Inventory\IQuantityItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\QuantityItemIterable',
                '\Radial\RetailOrderManagement\Payload\Inventory\IQuantityItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\RequestQuantityItem',
            ]
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\QuantityReply'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Inventory\IQuantityItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\QuantityItemIterable',
                '\Radial\RetailOrderManagement\Payload\Inventory\IQuantityItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\ReplyQuantityItem',
            ]
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\QuantityItemIterable'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Inventory\IQuantityItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\QuantityItem',
            ]
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\QuantityItem'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\RequestQuantityItem'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\ReplyQuantityItem'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\InventoryDetailsRequest'] = [
        'validators' => [
            [
                'validator' => $subpayloadValidator,
                'params' => ['getItems'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Inventory\IItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\ItemIterable',
                '\Radial\RetailOrderManagement\Payload\Inventory\IShippingItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\CompliantShippingItem',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\ItemIterable'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                // NOTE: the ShippingItem implementation is being overridden in the
                // InventoryDetailsRequest payload configuration
                '\Radial\RetailOrderManagement\Payload\Inventory\IShippingItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\ShippingItem',
                '\Radial\RetailOrderManagement\Payload\Inventory\IInStorePickUpItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\InStorePickUpItem',
                '\Radial\RetailOrderManagement\Payload\Inventory\IDetailItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\DetailItem',
                '\Radial\RetailOrderManagement\Payload\Inventory\IUnavailableItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\UnavailableItem',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\ShippingItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $inventoryAddressParams,
                    ['getItemId', 'getLineId', 'getQuantity', 'getShippingMethod']
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\CompliantShippingItem'] =
        $map['\Radial\RetailOrderManagement\Payload\Inventory\ShippingItem'];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\InStorePickUpItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $inventoryAddressParams,
                    ['getItemId', 'getLineId', 'getQuantity', 'getStoreFrontId', 'getStoreFrontName']
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\InventoryDetailsReply'] = [
        'validators' => [
            [
                'validator' => $subpayloadValidator,
                'params' => ['getDetailItems', 'getUnavailableItems'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Inventory\IDetailItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\ItemIterable',
                '\Radial\RetailOrderManagement\Payload\Inventory\IUnavailableItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\ItemIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\DetailItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $inventoryAddressParams,
                    [
                        'getItemId',
                        'getLineId',
                        'getDeliveryWindowFromDate',
                        'getDeliveryWindowToDate',
                        'getShippingWindowFromDate',
                        'getShippingWindowToDate',
                        'getDeliveryEstimateCreationTime',
                        'getDeliveryEstimateDisplayFlag',
                    ]
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\UnavailableItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getItemId', 'getLineId'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCustomerOrderId'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailResponse'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderResponse' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderResponse',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderResponse'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderDetailCustomer' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailCustomer',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderDetailItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailItemIterable',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderDetailShipping' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailShipping',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderDetailPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IFeeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\FeeIterable',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IAssociate' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\Associate',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\ITaxHeader' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\TaxHeader',
                '\Radial\RetailOrderManagement\Payload\Order\IItemRelationshipIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ItemRelationshipIterable',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IShipmentIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ShipmentIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ITemplateIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\TemplateIterable',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IExchangeOrderIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ExchangeOrderIterable',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IChargeGroupIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ChargeGroupIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailCustomer'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\LoyaltyProgramIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderDetailItem' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailItem'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getEstimatedDeliveryWindowFrom', 'getEstimatedDeliveryWindowTo']
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getEstimatedShippingWindowFrom', 'getEstimatedShippingWindowTo']
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getMerchandisePricing'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => [
                    'getShippingPricing',
                    'getDutyPricing',
                    'getFees',
                    'getStoreFrontDetails',
                    'getProxyPickupDetails',
                    'getGiftPricing',
                    'getCustomizationBasePrice',
                    'getCustomizations',
                    'getCustomAttributes',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IPriceGroup' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PriceGroup',
                '\Radial\RetailOrderManagement\Payload\Order\IStoreFrontDetails' =>
                    '\Radial\RetailOrderManagement\Payload\Order\StoreFrontDetails',
                '\Radial\RetailOrderManagement\Payload\Order\IProxyPickupDetails' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ProxyPickupDetails',
                '\Radial\RetailOrderManagement\Payload\Order\IFeeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\FeeIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ICustomizationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomizationIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IStatusIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\StatusIterable',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\ICustomerCareOrderItemTotals' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\CustomerCareOrderItemTotals',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IChargeGroupIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ChargeGroupIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\ChargeGroupIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IChargeGroup' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ChargeGroup',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\ChargeGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IReferencedCharge' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ReferencedCharge',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IAdjustmentCharge' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\AdjustmentCharge',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\ReferencedCharge'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\ITaxChargeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\TaxChargeIterable',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IChargeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ChargeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\AdjustmentCharge'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\TaxChargeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\ITaxCharge' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\TaxCharge',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\TaxCharge'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ITax' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Tax',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\ChargeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\ICharge' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\Charge',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\Charge'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\StatusIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IStatus' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\Status'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\Status'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getQuantity'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\CustomerCareOrderItemTotals'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailShipping'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IShipGroupIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ShipGroupIterable',
                '\Radial\RetailOrderManagement\Payload\Checkout\IDestinationIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\OrderDestinationIterable',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IShipmentIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ShipmentIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\ShipmentIterable'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IShipment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\Shipment',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\Shipment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IShippedItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ShippedItemIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\ShippedItemIterable'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IShippedItem' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ShippedItem',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\ShippedItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderDetailTrackingNumberIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailTrackingNumberIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailTrackingNumberIterable'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderDetailTrackingNumber' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailTrackingNumber',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailTrackingNumber'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailPayment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\IPaymentIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailPaymentIterable',
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailPaymentIterable'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderDetailCreditCardPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailCreditCardPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IPrepaidCreditCardPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PrepaidCreditCardPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IPointsPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PointsPayment',
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IOrderDetailStoredValueCardPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailStoredValueCardPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IPayPalPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PayPalPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IPrepaidCashOnDeliveryPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\PrepaidCashOnDeliveryPayment',
                '\Radial\RetailOrderManagement\Payload\Order\IReservationPayment' =>
                    '\Radial\RetailOrderManagement\Payload\Order\ReservationPayment',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailCreditCardPayment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $orderPaymentContextParams,
                    [
                        'getResponseCode',
                        'getBankAuthorizationCode',
                        'getCvv2ResponseCode',
                        'getAvsResponseCode',
                    ]
                ),
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\OrderDetailStoredValueCardPayment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getOrderId', 'getTenderType', 'getAccountUniqueId', 'getPaymentRequestId'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\Associate'] = [
        'validators' => [
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getName', 'getNumber', 'getStore'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\TaxHeader'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\ExchangeOrderIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Order\Detail\IExchangeOrder' =>
                    '\Radial\RetailOrderManagement\Payload\Order\Detail\ExchangeOrder'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Order\Detail\ExchangeOrder'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\AllocatedItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Inventory\IAllocatedItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\AllocatedItem'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\AllocatedItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getItemId', 'getLineId'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\AllocationReply'] = [
        'validators' => [
            [
                'validator' => $subpayloadValidator,
                'params' => ['getAllocatedItems'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Inventory\IAllocatedItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\AllocatedItemIterable'
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\AllocationRequest'] = [
        'validators' => [
            [
                'validator' => $subpayloadValidator,
                'params' => ['getItems'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\Radial\RetailOrderManagement\Payload\Inventory\IShippingItem' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\CompliantShippingItem',
                '\Radial\RetailOrderManagement\Payload\Inventory\IItemIterable' =>
                    '\Radial\RetailOrderManagement\Payload\Inventory\ItemIterable',
            ],
        ],
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\AllocationRollbackRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getRequestId', 'getReservationId'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Inventory\AllocationRollbackReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getReservationId'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\TenderType\LookupRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCardNumber'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\TenderType\LookupReply'] = [
        'validators' => [
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\ProtectPanRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getPaymentAccountNumber', 'getTenderClass'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Payment\ProtectPanReply'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Risk\RiskAssessmentReply'] = [
        'validators' => [
        [
          'validator' => $requiredFieldsValidator,
                 'params' => [
                        'getResponseCode',
                 ]
             ]
         ],
         'validatorIterator' => $validatorIterator,
         'schemaValidator' => $xsdSchemaValidator,
         'childPayloads' => $noChildPayloads,
    ];
    $map['\Radial\RetailOrderManagement\Payload\Risk\FaultDuplicate'] = [
        'validators' => [
        [
          'validator' => $requiredFieldsValidator,
                 'params' => [
			'getCode',
			'getDescription',
                 ]
             ]
         ],
         'validatorIterator' => $validatorIterator,
         'schemaValidator' => $xsdSchemaValidator,
         'childPayloads' => $noChildPayloads,
    ];
    return $map;
});
