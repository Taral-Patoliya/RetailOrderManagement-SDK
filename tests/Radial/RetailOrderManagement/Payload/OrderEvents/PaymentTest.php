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

use Radial\RetailOrderManagement\Payload\IPayloadMap;
use Radial\RetailOrderManagement\Payload\ISchemaValidator;
use Radial\RetailOrderManagement\Payload\IValidator;
use Radial\RetailOrderManagement\Payload\IValidatorIterator;
use Radial\RetailOrderManagement\Payload\PayloadMap;
use Radial\RetailOrderManagement\Payload\TPayloadTest;
use Radial\RetailOrderManagement\Payload\ValidatorIterator;
use Psr\Log\NullLogger;

class PaymentTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    const FULL_FIXTURE_FILE = 'Payment.xml';

    /** @var IPayloadMap */
    protected $payloadMap;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\Radial\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new ValidatorIterator([$this->stubValidator]);
        $this->stubSchemaValidator = $this->getMock('\Radial\RetailOrderManagement\Payload\ISchemaValidator');
        $this->payloadMap = new PayloadMap;

        $this->fullPayload = $this->buildPayload([
            'setDescription' => 'Visa',
            'setTenderType' => 'VC',
            'setMaskedAccount' => '************1111',
            'setAmount' => 5.55,
        ]);
    }

    protected function createNewPayload()
    {
        return new Payment($this->validatorIterator, $this->stubSchemaValidator, $this->payloadMap, new NullLogger());
    }

    protected function getCompleteFixtureFile()
    {
        return __DIR__ . '/Fixtures/' . static::FULL_FIXTURE_FILE;
    }

    public function testSerialize()
    {
        $this->assertSame(
            $this->loadXmlTestString($this->getCompleteFixtureFile(), true),
            $this->fullPayload->serialize()
        );
    }

    public function testDeserialize()
    {
        $payload = $this->buildPayload();
        $this->assertEquals(
            $this->fullPayload,
            $payload->deserialize($this->loadXmlTestString($this->getCompleteFixtureFile()))
        );
    }
}
