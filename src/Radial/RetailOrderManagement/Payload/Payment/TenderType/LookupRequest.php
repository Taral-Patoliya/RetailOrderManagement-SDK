<?php

namespace Radial\RetailOrderManagement\Payload\Payment\TenderType;

use Radial\RetailOrderManagement\Payload\IPayload;
use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Radial\RetailOrderManagement\Payload\TTopLevelPayload;
use Radial\RetailOrderManagement\Payload\Payment\TCurrencyCode;
use Radial\RetailOrderManagement\Payload\Payment\TPaymentAccountUniqueId;
use Psr\Log\LoggerInterface;

class LookupRequest implements ILookupRequest
{
    use TTopLevelPayload, TPaymentAccountUniqueId, TCurrencyCode;

    const ROOT_NODE = 'TenderTypeLookupRequest';

    protected $tenderClass;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        list(
            $this->validators,
            $this->schemaValidator,
            $this->payloadMap,
            $this->logger,
            $this->parentPayload
        ) = func_get_args();

        $this->extractionPaths = [
            'tenderClass' => 'string(x:TenderClass)',
            'currencyCode' => 'string(x:CurrencyCode)',
        ];
        $this->optionalExtractionPaths = [
            'panIsToken' => 'x:PaymentAccountUniqueId/@isToken',
        ];
    }

    /**
     * @return string
     */
    public function getTenderClass()
    {
        return $this->tenderClass;
    }

    /**
     * @param string
     */
    public function setTenderClass($tenderClass)
    {
        $this->tenderClass = $tenderClass;
        return $this;
    }

    /**
     * deserialize the card number and mark whether the card number
     * is incrypted or not.
     *
     * @param string
     * @return self
     */
    protected function deserializeExtra($serializedPayload)
    {
        $xPath = $this->getPayloadAsXPath($serializedPayload);
        $cardNumber = $this->cleanString(
            $xPath->evaluate('string(x:EncryptedPaymentAccountUniqueId)'),
            1000
        );
        // need to set the isEncrypted flag before setting the card number or else it'll be
        // treated as an unencrypted pan and get truncated.
        $this->setIsEncrypted((bool) $cardNumber);
        if (!$cardNumber) {
            $cardNumber = $this->cleanString(
                $xPath->evaluate('string(x:PaymentAccountUniqueId)'),
                22
            )
                ?: null;
        }
        $this->setCardNumber($cardNumber);
    }

    /**
     * generate an xml string representation of the payload
     *
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializePaymentAccountUniqueId()
            . $this->serializeRequiredValue('TenderClass', $this->xmlEncode($this->getTenderClass()))
            . $this->serializeCurrencyCode();
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . static::XSD;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }
}
