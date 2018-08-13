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

namespace Radial\RetailOrderManagement\Payload\Customer;

use Radial\RetailOrderManagement\Payload\IPayload;
use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Radial\RetailOrderManagement\Payload\PayloadFactory;
use Radial\RetailOrderManagement\Payload\TIterablePayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SPLObjectStorage;

class OrderSummaryIterable extends SPLObjectStorage implements IOrderSummaryIterable
{
    use TIterablePayload;

    const XML_NAMESPACE = 'x:';

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory();
        $this->includeIfEmpty = false;
        $this->buildRootNode = false;
    }

    /**
     * @see IOrderSummaryIterable::getEmptyOrderSummary()
     */
    public function getEmptyOrderSummary()
    {
        return $this->buildPayloadForInterface(static::ORDER_SUMMARY_INTERFACE);
    }

    /**
     * Get a new order summary instance.
     * @return IOrderSummary
     */
    protected function getNewSubpayload()
    {
        return $this->getEmptyOrderSummary();
    }

    /**
     * @see TIterablePayload::getSubpayloadXPath()
     */
    protected function getSubpayloadXPath()
    {
        return static::XML_NAMESPACE
            . static::SUBPAYLOAD_XPATH;
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
}
