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

use DOMDocument;
use Radial\RetailOrderManagement\Payload;
use Psr\Log\NullLogger;

/**
 * Class StoredValueRedeemReplyTest
 * @package Radial\RetailOrderManagement\Payload\Payment
 *
 * Tests that the payload object for StoredValueRedeemReply contains
 * the expected fields, serializes and validates according to its interface.
 */
class StoredValueRedeemReplyTest extends \PHPUnit_Framework_TestCase
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /** @var Payload\IValidator */
    protected $validatorStub;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var  Payload\ISchemaValidator */
    protected $schemaValidatorStub;
    /** @var Payload\IPayloadMap */
    protected $payloadMap;
    /** @var string */
    protected $testXML = <<<'XML'
<Root xmlns="http://api.gsicommerce.com/schema/checkout/1.0">
<Node1 attrib="true">0</Node1><Node2 attrib="false">1</Node2>
</Root>
XML;

    /**
     * data provider to provide an empty array of properties
     * Empty properties will generate and invalid IPayload object
     *
     * @return array $payloadData
     */
    public function provideInvalidPayload()
    {
        $payloadData = [];
        return [
            [$payloadData]
        ];
    }

    /**
     * Data provider to provide an array of valid property values that will generate an valid IPayload object
     *
     * @return array $payloadData
     */
    public function provideValidPayload()
    {
        // move to JSON
        $properties = [
            'setOrderId' => 'o3trodZDaS2zhZHirJnA',
            'setPanIsToken' => false,
            'setCardNumber' => 'hmrROxcsoE8BDmbZFUME0+',
            'setAmountRedeemed' => 0.00,
            'setBalanceAmount' => 15.55,
            'setAmountRedeemedCurrencyCode' => 'GBP',
            'setBalanceAmountCurrencyCode' => 'GBP',
            'setResponseCode' => 'Fail',
        ];

        return [
            [$properties]
        ];
    }

    /**
     * Data provider for was redeemed tests
     * @return array[]
     */
    public function provideResponseCodeConditions()
    {
        return [
            [
                [
                    'setOrderId' => 'o3trodZDaS2zhZHirJnA',
                    'setPanIsToken' => false,
                    'setCardNumber' => 'hmrROxcsoE8BDmbZFUME0+',
                    'setAmountRedeemed' => 0.00,
                    'setBalanceAmount' => 15.55,
                    'setAmountRedeemedCurrencyCode' => 'GBP',
                    'setBalanceAmountCurrencyCode' => 'GBP',
                    'setResponseCode' => 'Success',
                ]
            ],
            [
                [
                    'setOrderId' => 'o3trodZDaS2zhZHirJnA',
                    'setPanIsToken' => false,
                    'setCardNumber' => 'hmrROxcsoE8BDmbZFUME0+',
                    'setAmountRedeemed' => 0.00,
                    'setBalanceAmount' => 15.55,
                    'setAmountRedeemedCurrencyCode' => 'GBP',
                    'setBalanceAmountCurrencyCode' => 'GBP',
                    'setResponseCode' => 'Fail',
                ]
            ],
            [
                [
                    'setOrderId' => 'o3trodZDaS2zhZHirJnA',
                    'setPanIsToken' => false,
                    'setCardNumber' => 'hmrROxcsoE8BDmbZFUME0+',
                    'setAmountRedeemed' => 0.00,
                    'setBalanceAmount' => 15.55,
                    'setAmountRedeemedCurrencyCode' => 'GBP',
                    'setBalanceAmountCurrencyCode' => 'GBP',
                    'setResponseCode' => 'Timeout',
                ]
            ]
        ];
    }

    /**
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \Radial\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testValidateWillFail(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->validatorStub->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $payload->validate();
    }

    /**
     * Take an array of property values with property names as keys and return an IPayload object
     *
     * @param array $properties
     * @return StoredValueRedeemReply
     */
    protected function buildPayload(array $properties)
    {
        $payload = new StoredValueRedeemReply($this->validatorIterator, $this->schemaValidatorStub, $this->payloadMap, new NullLogger());

        foreach ($properties as $property => $value) {
            $payload->$property($value);
        }

        return $payload;
    }

    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testValidateWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->validatorStub->expects($this->any())
            ->method('validate')
            ->will($this->returnSelf());
        $this->assertSame($payload, $payload->validate());
    }

    /**
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \Radial\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFailPayloadValidation(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->validatorStub->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload()));
        $payload->serialize();
    }

    /**
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \Radial\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFailXsdValidation(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->schemaValidatorStub->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload()));
        $payload->serialize();
    }

    /**
     * Read an XML file with invalid payload data and return a canonicalized string
     *
     * @return string
     */
    protected function xmlInvalidTestString()
    {
        $dom = new DOMDocument();
        $dom->load(__DIR__ . '/Fixtures/InvalidStoredValueRedeemReply.xml');
        $string = $dom->C14N();

        return $string;
    }

    /**
     * @expectedException \Radial\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFailPayloadValidation()
    {
        $this->validatorStub->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $xml = $this->xmlInvalidTestString();

        $newPayload = new StoredValueRedeemReply($this->validatorIterator, $this->schemaValidatorStub, $this->payloadMap, new NullLogger());
        $newPayload->deserialize($xml);
    }

    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $xml = $this->xmlTestString();

        $newPayload = new StoredValueRedeemReply($this->validatorIterator, $this->schemaValidatorStub, $this->payloadMap, new NullLogger());
        $newPayload->deserialize($xml);

        $this->assertEquals($payload, $newPayload);
    }

    /**
     * Read an XML file with valid payload data and return a canonicalized string
     *
     * @return string
     */
    protected function xmlTestString()
    {
        $dom = new DOMDocument();
        $dom->load(__DIR__ . '/Fixtures/StoredValueRedeemReply.xml');
        $string = $dom->C14N();

        return $string;
    }

    /**
     * Test that a response code of "Success" is considered a successful redeem request/reply.
     * @dataProvider provideResponseCodeConditions
     */
    public function testWasRedeemed(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $wasRedeemed = $payload->wasRedeemed();
        if ($payload->getResponseCode() === 'Success') {
            $this->assertTrue($wasRedeemed);
        } else {
            $this->assertFalse($wasRedeemed);
        }
    }

    protected function setUp()
    {
        $this->validatorStub = $this->getMock('\Radial\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->validatorStub]);
        $this->schemaValidatorStub = $this->getMock('\Radial\RetailOrderManagement\Payload\ISchemaValidator');
        $this->payloadMap = new Payload\PayloadMap;
    }
}
