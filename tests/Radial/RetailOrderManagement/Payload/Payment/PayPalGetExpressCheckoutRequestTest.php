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
use Radial\RetailOrderManagement\Payload\PayloadFactory;
use Radial\RetailOrderManagement\Payload\TPayloadTest;
use Psr\Log\NullLogger;

class PayPalGetExpressCheckoutRequestTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory();
    }

    public function provideSerializedDataFile()
    {
        return [
            [
                __DIR__ . '/Fixtures/PayPalGetExpressCheckoutRequestTest.xml'
            ]
        ];
    }

    /**
     * Test deserializing data into a payload and then deserializing back
     * to match the original data.
     *
     * @param string path to fixture file
     * @dataProvider provideSerializedDataFile
     */
    public function testDeserializeSerialize($serializedDataFile)
    {
        $payload = $this->buildPayload();
        $serializedData = $this->loadXmlTestString($serializedDataFile);
        $payload->deserialize($serializedData);
        $this->assertSame($serializedData, $payload->serialize());
    }

    /**
     * Get a new PayPalGetExpressCheckoutRequest payload.
     * @return PayPalGetExpressCheckoutRequest
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\Radial\RetailOrderManagement\Payload\Payment\PayPalGetExpressCheckoutRequest', null, null, new NullLogger());
    }
}
