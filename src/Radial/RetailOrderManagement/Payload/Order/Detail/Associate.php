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

use Radial\RetailOrderManagement\Payload\IPayload;
use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Radial\RetailOrderManagement\Payload\TPayload;
use Radial\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Associate implements IAssociate
{
    use TPayload;

    /** @var string */
    protected $name;
    /** @var string */
    protected $number;
    /** @var string */
    protected $store;

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
        $this->payloadFactory = $this->getNewPayloadFactory();
        // don't include root node if all the sub-node are empty.
        $this->isSerializeEmptyNode = false;
        $this->initExtractPaths();
    }

    /**
     * Initialize the protected class property array self::extractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initExtractPaths()
    {
        $this->extractionPaths = [
            'name' => 'string(x:Name)',
            'number' => 'string(x:Number)',
            'store' => 'string(x:Store)',
        ];
        return $this;
    }

    /**
     * @see IAssociate::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @see IAssociate::setName()
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *  @see IAssociate::getNumber()
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @see IAssociate::setNumber()
     * @codeCoverageIgnore
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @see IAssociate::getStore()
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @see IAssociate::setStore()
     * @codeCoverageIgnore
     */
    public function setStore($store)
    {
        $this->store = $store;
        return $this;
    }

    /**
     * Check if the associate node is serializable.
     *
     * @return bool
     */
    protected function isSerializable()
    {
        return $this->getName() && $this->getNumber() && $this->getStore();
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->isSerializable()
            ? $this->serializeRequiredValue('Name', $this->xmlEncode($this->getName()))
            . $this->serializeRequiredValue('Number', $this->xmlEncode($this->getNumber()))
            . $this->serializeRequiredValue('Store', $this->xmlEncode($this->getStore()))
            : null;
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
