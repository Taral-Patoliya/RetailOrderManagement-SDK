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

namespace Radial\RetailOrderManagement\Payload\Address;

use Radial\RetailOrderManagement\Payload\Checkout\TPhysicalAddress;
use Radial\RetailOrderManagement\Payload\IPayload;
use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Radial\RetailOrderManagement\Payload\PayloadFactory;
use Radial\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;

class ValidationRequest implements IValidationRequest
{
    use TTopLevelPayload, TPhysicalAddress, TValidationHeader;

    const ADDRESS_ROOT_NODE = 'Address';

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory;

        $this->extractionPaths = [
            'maxSuggestions' => 'number(x:Header/x:MaxAddressSuggestions)',
            'city' => 'string(x:Address/x:City)',
            'countryCode' => 'string(x:Address/x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'mainDivision' => 'x:Address/x:MainDivision',
            'postalCode' => 'x:Address/x:PostalCode',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'x:Address/*[starts-with(name(), "Line")]'
            ],
        ];
    }

    protected function serializeContents()
    {
        return $this->serializeValidationHeader() . $this->serializePhysicalAddress();
    }

    protected function getRootAttributes()
    {
        return array_filter([
            'xmlns' => $this->getXmlNamespace(),
        ]);
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return static::ADDRESS_ROOT_NODE;
    }

    protected function getRootNodeName()
    {
        return self::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }
}
