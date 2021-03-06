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

namespace Radial\RetailOrderManagement\Payload\Payment;

use Radial\RetailOrderManagement\Payload\IPayload;
use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Radial\RetailOrderManagement\Payload\PayloadFactory;
use Radial\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PayPalSetExpressCheckoutRequest implements IPayPalSetExpressCheckoutRequest
{
    use TTopLevelPayload, TAmount, TOrderId, TCurrencyCode, TShippingAddress, TLineItemContainer;

    /** @var string * */
    protected $returnUrl;
    /** @var string * */
    protected $cancelUrl;
    /** @var string * */
    protected $localeCode;
    /** @var float * */
    protected $amount;
    /** @var boolean * */
    protected $addressOverride;
    /** @var string * */
    protected $shipToName;

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
        $this->payloadFactory = new PayloadFactory();

        $this->extractionPaths = [
            'orderId' => 'string(x:OrderId)',
            'amount' => 'number(x:Amount)',
            'returnUrl' => 'string(x:ReturnUrl)',
            'cancelUrl' => 'string(x:CancelUrl)',
            'localeCode' => 'string(x:LocaleCode)',
            'currencyCode' => 'string(x:Amount/@currencyCode)',
	    'shipToName' => 'string(x:ShipToName)',
            // see addressLinesFromXPath - Address lines Line1 through Line4 are specially handled with that function
            'shipToCity' => 'string(x:ShippingAddress/x:City)',
            'shipToMainDivision' => 'string(x:ShippingAddress/x:MainDivision)',
            'shipToCountryCode' => 'string(x:ShippingAddress/x:CountryCode)',
            'shipToPostalCode' => 'string(x:ShippingAddress/x:PostalCode)',
            'shippingTotal' => 'number(x:LineItems/x:ShippingTotal)',
            'taxTotal' => 'number(x:LineItems/x:TaxTotal)',
            'lineItemsTotal' => 'number(x:LineItems/x:LineItemsTotal)',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'shipToLines',
                'xPath' => "x:ShippingAddress/*[starts-with(name(), 'Line')]",
            ]
        ];
        $this->booleanExtractionPaths = [
            'addressOverride' => 'string(x:AddressOverride)',
        ];
        $this->subpayloadExtractionPaths = [
            'lineItems' => "x:LineItems",
        ];
        $this->lineItems = $this->buildPayloadForInterface(static::ITERABLE_INTERFACE);
    }

    /**
     * Serialize the various parts of the payload into XML strings and
     * concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializeOrderId()
        . $this->serializeUrls()
        . $this->serializeLocaleCode()
        . $this->serializeCurrencyAmount('Amount', $this->getAmount(), $this->xmlEncode($this->getCurrencyCode()))
        . $this->serializeAddressOverride()
	. $this->serializeShipToName()
        . $this->serializeShippingAddress()
        . $this->serializeLineItemsContainer();
    }
   
    /**
     * Serialize the Ship To Name
     * @return string
     */
    protected function serializeShipToName()
    {
        return $this->serializeOptionalXmlEncodedValue("ShipToName", $this->getShipToName());
    }

    /**
     * The name of the person shipped to like "FirsName LastName".
     *
     * @return string
     */
    public function getShipToName()
    {
        return $this->shipToName;
    }

    /**
     * @param string
     * @return self
     */
    public function setShipToName($name)
    {
        $this->shipToName = $name;
        return $this;
    }

    /**
     * Serialize the URLs to which PayPal should redirect upon return and cancel, respectively
     * @return string
     */
    protected function serializeUrls()
    {
        return "<ReturnUrl>{$this->xmlEncode($this->getReturnUrl())}</ReturnUrl>"
        . "<CancelUrl>{$this->xmlEncode($this->getCancelUrl())}</CancelUrl>";
    }

    /**
     * URL to which the customer's browser is returned after choosing to pay with PayPal.
     * PayPal recommends that the value be the final review page on which the customer confirms the order and payment.
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param string
     * @return self
     */
    public function setReturnUrl($url)
    {
        $this->returnUrl = $url;
        return $this;
    }

    /**
     * URL to which the customer is returned if the customer does not approve the use of PayPal.
     * PayPal recommends that the value be the original page on which the customer chose to pay with PayPal.
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    /**
     * @param string
     * @return self
     */
    public function setCancelUrl($url)
    {
        $this->cancelUrl = $url;
        return $this;
    }

    /**
     * Serialize the Local Code
     * @return string
     */
    protected function serializeLocaleCode()
    {
        return "<LocaleCode>{$this->xmlEncode($this->getLocaleCode())}</LocaleCode>";
    }

    /**
     * Locale of pages displayed by PayPal during Express Checkout.
     *
     * @link https://developer.paypal.com/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->localeCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setLocaleCode($localeCode)
    {
        $this->localeCode = $localeCode;
        return $this;
    }

    /**
     * The amount to authorize
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $this->sanitizeAmount($amount);
        return $this;
    }

    protected function deserializeExtra()
    {
        if (count($this->getLineItems()) && $this->getLineItemsTotal() === null) {
            $this->calculateLineItemsTotal();
        }
    }

    /**
     * Serialize the AddressOverride indicator, which is a boolean
     * @return string
     */
    protected function serializeAddressOverride()
    {
        return '<AddressOverride>' . ($this->getAddressOverride() ? '1' : '0') . '</AddressOverride>';
    }

    /**
     * If true, PayPal will display the shipping address provided in the payload.
     * Otherwise PayPal will display whatever shipping address it has for the customer
     * and won't let the customer edit it.
     * Consider setting this flag implicitly based on whether or not an address is provided.
     * And simply implement the getter/setter to allow overriding as an edge case.
     *
     * @return bool
     */
    public function getAddressOverride()
    {
        return $this->addressOverride;
    }

    /**
     * @param bool
     * @return self
     */
    public function setAddressOverride($override)
    {
        $this->addressOverride = $override;
        return $this;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    /**
     * The XML namespace for the payload.
     *
     * @return string
     */
    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    /**
     * Return the name of the xml root node.
     *
     * @return string
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }
}
