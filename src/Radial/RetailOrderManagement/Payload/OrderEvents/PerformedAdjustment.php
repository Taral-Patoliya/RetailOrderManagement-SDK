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
use Radial\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PerformedAdjustment implements IPerformedAdjustment
{
    use TPayload;

    /** @var string */
    protected $display;

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

        $this->extractionPaths = [
            'type' => 'string(./@type)',
            'display' => 'string(./@display)',
        ];
    }

    /**
     * String to display.
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }
    /**
     * @param string
     * @return self
     */
    public function setDisplay($display)
    {
        $this->display = $display;
        return $this;
    }
    /**
     * Type of adjustment.
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param string
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * nothing special to serialize
     * @return string
     */
    protected function serializeContents()
    {
        return '';
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getRootAttributes()
    {
        return ['type' => $this->getType(), 'display' => $this->getDisplay()];
    }
}
