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
use Radial\RetailOrderManagement\Util\TTestReflection;
use Psr\Log\NullLogger;

class CreditCardAuthReplyTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest, TTestReflection;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory();
    }

    /**
     * Data provider for valid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideValidPayload()
    {
        // move to JSON
        $properties = [
            'orderId' => 'ORDER_ID',
            'cardNumber' => '4111ABC123ZYX987',
            'panIsToken' => true,
            'authorizationResponseCode' => 'AP01',
            'bankAuthorizationCode' => 'OK',
            'cvv2ResponseCode' => 'M',
            'avsResponseCode' => 'M',
            'amountAuthorized' => 55.99,
            'currencyCode' => 'USD',
            'phoneResponseCode' => 'PHONE_OK',
            'nameResponseCode' => 'NAME_OK',
        ];

        $encryptionProperties = [
            'isEncrypted' => true,
            // pan will be encrypted, so not a token
            'panIsToken' => false
        ];
        return [
            [$properties, 'UnencryptedCardData'],
            [array_merge($properties, $encryptionProperties), 'EncryptedCardData']
        ];
    }

    /**
     * Provide payloads of data that should result in an unsuccessful payload
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideUnsuccessfulPayload()
    {
        return [
            // any authorization response code other than AP01, TO01 or NR01 is
            // unsuccessful, regardless of any other responses
            [
                [
                    'authorizationResponseCode' => 'ND01',
                ]
            ],
            // when auth response code is successful, AVS and CVV response
            // codes must be successful as well, when they are not, reply is unsuccessful
            [
                [
                    'authorizationResponseCode' => 'AP01',
                    'cvv2ResponseCode' => 'M',
                    'avsResponseCode' => 'N',
                ]
            ],
            [
                [
                    'authorizationResponseCode' => 'AP01',
                    'cvv2ResponseCode' => 'M',
                    'avsResponseCode' => 'AW',
                ]
            ],
            [
                [
                    'authorizationResponseCode' => 'AP01',
                    'cvv2ResponseCode' => 'N',
                    'avsResponseCode' => 'P',
                ]
            ],
        ];
    }

    /**
     * Provide payloads of data that should result in a successful payload
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideSuccessfulPayload()
    {
        return [
            [
                [
                    'authorizationResponseCode' => 'AP01',
                    'cvv2ResponseCode' => 'M',
                    'avsResponseCode' => 'P'
                ]
            ],
        ];
    }

    /**
     * Provide payloads of data that should result in an unacceptable payload
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideUnacceptableAuthPayload()
    {
        return [
            [['authorizationResponseCode' => 'ND01']],
        ];
    }

    /**
     * Provide payloads of data that should result in an acceptable payload
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideAcceptableAuthPayload()
    {
        return [
            [['authorizationResponseCode' => 'AP01']],
            [['authorizationResponseCode' => 'NR01']],
            [['authorizationResponseCode' => 'TO01']],
        ];
    }

    /**
     * Provide payloads of data that should produce the provided response code.
     * @return array[] Array of arg arrays, each containing a set of payload data suitable
     *                 for self::buildPayload and the expected result of getResponseCode
     */
    public function provideResponseCodePayloadAndCode()
    {
        return [
            [
                ['authorizationResponseCode' => 'AP01'],
                'APPROVED'
            ],
            [
                ['authorizationResponseCode' => 'NR01'],
                'TIMEOUT'
            ],
            [
                ['authorizationResponseCode' => 'TO01'],
                'TIMEOUT'
            ],
            // basically anything else should result in a `null` response code
            [
                ['authorizationResponseCode' => 'NC03'],
                null
            ],
        ];
    }

    /**
     * Provide payload data that will require AVS correction
     * @return array
     */
    public function provideAVSCorrectionRequiredPayload()
    {
        return [
            [['authorizationResponseCode' => 'AP01', 'avsResponseCode' => 'N']],
        ];
    }

    /**
     * Provide payload data that will not require AVS correction
     * @return array
     */
    public function provideAVSCorrectionNotRequiredPayload()
    {
        return [
            [['authorizationResponseCode' => 'ND01', 'avsResponseCode' => 'N']],
            [['authorizationResponseCode' => 'AP01', 'avsResponseCode' => 'M']],
        ];
    }

    /**
     * Provide payload data that will require CVV2 correction
     * @return array
     */
    public function provideCVVCorrectionRequiredPayload()
    {
        return [
            [['authorizationResponseCode' => 'AP01', 'cvv2ResponseCode' => 'N']],
        ];
    }

    /**
     * Provide payload data that will not require CVV2 correction
     * @return array
     */
    public function provideCVVCorrectionNotRequiredPayload()
    {
        return [
            [['authorizationResponseCode' => 'AP01', 'cvv2ResponseCode' => 'M']],
            [['authorizationResponseCode' => 'ND01', 'cvv2ResponseCode' => 'N']],
        ];
    }

    public function provideAuthTimeoutPayload()
    {
        return [
            [['authorizationResponseCode' => 'TO01']],
            [['authorizationResponseCode' => 'NR01']]
        ];
    }

    /**
     * @return array
     */
    public function provideBooleanFromStringTests()
    {
        return [
            ["true", true],
            ["false", false],
            ["1", true],
            ["0", false],
            ["True", true],
            [null, null],
            [1, null],
            ["test", null]
        ];
    }

    /**
     * @dataProvider provideBooleanFromStringTests
     */
    public function testBooleanFromString($value, $expected)
    {
        $payload = $this->createNewPayload();
        $actual = $this->invokeRestrictedMethod($payload, 'convertStringToBoolean', [$value]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Create a payload with the provided data injected.
     * @param  mixed[] $properties key/value pairs of property => value
     * @return CreditCardAuthReply
     */
    protected function buildPayloadWithReflection($properties)
    {
        $payload = $this->createNewPayload();
        $this->setRestrictedPropertyValues($payload, $properties);
        return $payload;
    }

    /**
     * Check for the authorization success to be false when errors were
     * returned in the reply.
     * @param  array $payloadData
     * @dataProvider provideUnsuccessfulPayload
     */
    public function testGetIsAuthSuccessfulPayloadWithErrors(array $payloadData)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertFalse($payload->getIsAuthSuccessful());
    }

    /**
     * Check for the authorization successful to be true when there were no
     * errors returned in the reply.
     * @param  array $payloadData
     * @dataProvider provideSuccessfulPayload
     */
    public function testGetIsAuthSuccessfulPayloadNoErrors(array $payloadData)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertTrue($payload->getIsAuthSuccessful());
    }

    /**
     * Check for the authorization to be unacceptable if the reply contains
     * any error.
     * @param  array $payloadData
     * @dataProvider provideUnacceptableAuthPayload
     */
    public function testGetIsAuthAcceptableUnacceptablePayload(array $payloadData)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertFalse($payload->getIsAuthAcceptable());
    }

    /**
     * Check for the authorization to be acceptable if the reply is successful
     * or reports a timeout.
     * @param  array $payloadData
     * @dataProvider provideAcceptableAuthPayload
     */
    public function testGetIsAuthAcceptableAcceptablePayload(array $payloadData)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertTrue($payload->getIsAuthAcceptable());
    }

    /**
     * Check for the payload response code to match the expected response
     * code. Response code should be 'APPROVED' for acceptable authorizations,
     * 'TIMEOUT' for requests that indicate a timeout or null when the authorization
     * reply should not be accepted.
     * @param  array $payloadData
     * @param  string|null $responseCode
     * @dataProvider provideResponseCodePayloadAndCode
     */
    public function testGetReplyResponseCode(array $payloadData, $responseCode)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertSame($responseCode, $payload->getResponseCode());
    }

    /**
     * Test checking for if AVS corrections are needed.
     * @param array $payloadData
     * @dataProvider provideAVSCorrectionRequiredPayload
     */
    public function testAvsCorrectionRequired(array $payloadData)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertTrue($payload->getIsAVSCorrectionRequired());
    }

    /**
     * Test checking for if AVS corrections are needed.
     * @param array $payloadData
     * @dataProvider provideAVSCorrectionNotRequiredPayload
     */
    public function testAvsCorrectionNotRequired(array $payloadData)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertFalse($payload->getIsAVSCorrectionRequired());
    }

    /**
     * Test checking for if AVS corrections are needed.
     * @param array $payloadData
     * @dataProvider provideCVVCorrectionRequiredPayload
     */
    public function testIsCVV2CorrectionRequired(array $payloadData)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertTrue($payload->getIsCVV2CorrectionRequired());
    }

    /**
     * Test checking for if AVS corrections are needed.
     * @param array $payloadData
     * @dataProvider provideCVVCorrectionNotRequiredPayload
     */
    public function testIsCVV2CorrectionNotRequired(array $payloadData)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertFalse($payload->getIsCVV2CorrectionRequired());
    }

    /**
     * Test checking for auth timeout responses.
     * @param  array $payloadData
     * @dataProvider provideAuthTimeoutPayload
     */
    public function testIsAuthTimeout(array $payloadData)
    {
        $payload = $this->buildPayloadWithReflection($payloadData);
        $this->assertTrue($payload->getIsAuthTimeout());
    }

    /**
     * Get a new CreditCardAuthReply payload.
     * @return CreditCardAuthReply
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\Radial\RetailOrderManagement\Payload\Payment\CreditCardAuthReply', null, null, new NullLogger());
    }

    /**
     * Provide paths to fixture files containing valid serialized data.
     *
     * @return array
     */
    public function provideSerializedDataFile()
    {
        return [
            [
                __DIR__ . '/Fixtures/UnencryptedCardData/CreditCardAuthReply.xml'
            ],
            [
                __DIR__ . '/Fixtures/EncryptedCardData/CreditCardAuthReply.xml'
            ],
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
}
