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

namespace Radial\RetailOrderManagement\Payload\OrderEvents;

use Radial\RetailOrderManagement\Payload\IPayload;
use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Radial\RetailOrderManagement\Payload\PayloadFactory;
use Radial\RetailOrderManagement\Payload\TIterablePayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SPLObjectStorage;

class ItemPriceAdjustmentIterable extends SPLObjectStorage implements IItemPriceAdjustmentIterable
{
    use TIterablePayload;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator unused, kept to allow IPayloadMap to be passed
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
        $this->payloadFactory = new PayloadFactory();
        $this->parentPayload = $parentPayload;

        $this->includeIfEmpty = true;
    }

    public function getEmptyAdjustment()
    {
        return $this->buildPayloadForInterface(static::ITEM_ADJUSTMENT_INTERFACE);
    }

    protected function getNewSubpayload()
    {
        return $this->getEmptyAdjustment();
    }

    protected function getSubpayloadXPath()
    {
        return 'x:' . static::SUBPAYLOAD_XPATH;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
