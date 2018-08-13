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

namespace Radial\RetailOrderManagement\Payload\TaxDutyFee;

use Radial\RetailOrderManagement\Payload\IPayload;
use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TaxedMerchandisePriceGroup extends TaxedPriceGroup implements ITaxedMerchandisePriceGroup
{
    const ROOT_NODE = 'Pricing';

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        parent::__construct($validators, $schemaValidator, $payloadMap, $logger, $parentPayload);

        $this->optionalExtractionPaths = array_merge(
            $this->optionalExtractionPaths,
            ['unitPrice' => 'x:UnitPrice']
        );
    }

    /**
     * Dynamically set the name of the root node the price group gets serialized
     * with. As this type can represent a variant of pricing information,
     * serializations will vary based upon context.
     *
     * @param string Must be a valid XML node name
     */
    public function setRootNodeName($nodeName)
    {
        $this->rootNodeName = $nodeName;
        return $this;
    }

    protected function serializeContents()
    {
        $unitPrice = $this->getUnitPrice();
        return $this->serializePriceGroupAmount()
            . $this->serializeTaxData()
            . $this->getDiscounts()->serialize()
            . $this->serializeAmount('UnitPrice', $this->getUnitPrice());
    }

    /**
     * perform additional sanitization
     * @return self
     */
    protected function deserializeExtra()
    {
        $this->amount = $this->sanitizeAmount($this->amount);
        $this->unitPrice = $this->sanitizeAmount($this->unitPrice);
        return $this;
    }
}
