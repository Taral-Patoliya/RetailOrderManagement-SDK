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

namespace Radial\RetailOrderManagement\Payload\Validator;

use Radial\RetailOrderManagement\Util\TTestReflection;
use libXMLError;

class XsdSchemaValidatorTest extends \PHPUnit_Framework_TestCase
{
    use TTestReflection;

    /**
     * XML not matching the schema should result in an exception.
     */
    public function testValidateInvalidXml()
    {
        $this->setExpectedException(
            'Radial\RetailOrderManagement\Payload\Exception\InvalidPayload',
            'XSD validation failed with following messages:'
        );
        $validator = new XsdSchemaValidator();
        $xmlString = file_get_contents(__DIR__ . '/Fixtures/InvalidXml.xml');
        $validator->validate($xmlString, __DIR__ . '/Fixtures/TestSchema.xsd');
    }

    /**
     * XML matching the schema should simply return self.
     */
    public function testValidateValidXml()
    {
        $validator = new XsdSchemaValidator();
        $xmlString = file_get_contents(__DIR__ . '/Fixtures/ValidXml.xml');
        $this->assertSame(
            $validator,
            $validator->validate($xmlString, __DIR__ . '/Fixtures/TestSchema.xsd')
        );
    }

    /**
     * Test building the error message from the libxml errors. Should return a
     * string with each libXMLError formatted.
     */
    public function testBuildErrorMessage()
    {
        $warnError = new libXMLError();
        $warnError->level = LIBXML_ERR_WARNING;
        $warnError->message = "Warning message\n";
        $warnError->file = 'some/file/path.xml';
        $warnError->line = 22;

        $errError = new libXMLError();
        $errError->level = LIBXML_ERR_ERROR;
        $errError->message = "Error message\n";
        $errError->file = 'some/file/path.xml';
        $errError->line = 23;

        $fatalError = new libXMLError();
        $fatalError->level = LIBXML_ERR_FATAL;
        $fatalError->message = "Fatal message\n";
        $fatalError->file = 'some/file/path.xml';
        $fatalError->line = 25;

        $errors = [$warnError, $errError, $fatalError];
        $validator = new XsdSchemaValidator();
        $this->assertSame(
            "XSD validation failed with following messages:\n"
            . "[some/file/path.xml:22] Warning message\n"
            . "[some/file/path.xml:23] Error message\n"
            . "[some/file/path.xml:25] Fatal message\n",
            $this->invokeRestrictedMethod($validator, 'formatErrors', [$errors])
        );
    }
}
