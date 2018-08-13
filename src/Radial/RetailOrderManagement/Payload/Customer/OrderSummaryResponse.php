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
use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Radial\RetailOrderManagement\Payload\TTopLevelPayload;
use Radial\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderSummaryResponse implements IOrderSummaryResponse
{
    use TTopLevelPayload;

    /** @var IOrderSummaryIterable */
    protected $orderSummaries;

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
        $this->logger = $logger;
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->parentPayload = $parentPayload;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory =  new PayloadFactory();
        $this->setOrderSummaries($this->buildPayloadForInterface(
            static::ORDER_SUMMARY_ITERABLE_INTERFACE
        ));
    }

    /**
     * @see IOrderSummaryResponse::getOrderSummaries()
     */
    public function getOrderSummaries()
    {
        return $this->orderSummaries;
    }

    /**
     * @see IOrderSummaryResponse::setOrderSummaries()
     */
    public function setOrderSummaries(IOrderSummaryIterable $orderSummaries)
    {
        $this->orderSummaries = $orderSummaries;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->getOrderSummaries()->serialize();
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see TPayload::getXmlNamespace()
     */
    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    /**
     * Validate the serialized data via the schema validator.
     * @param  string
     * @return self
     */
    protected function schemaValidate($serializedData)
    {
        $this->schemaValidator->validate($serializedData, $this->getSchemaFile());
        return $this;
    }

    /**
     * @see TPayload::getRootAttributes()
     */
    protected function getRootAttributes()
    {
        return ['xmlns' => $this->getXmlNamespace()];
    }

    /**
     * @see TPayload::deserializeExtra()
     */
    protected function deserializeExtra($serializedPayload)
    {
        $this->getOrderSummaries()->deserialize($serializedPayload);
        return $this;
    }
}
