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
use Radial\RetailOrderManagement\Payload\Checkout\MailingAddress as CheckoutMailingAddress;

class MailingAddress extends CheckoutMailingAddress implements IMailingAddress
{
    protected function getRootNodeName()
    {
        return self::ROOT_NODE;
    }

    protected function getPersonNameRootNodeName()
    {
        return self::PERSON_NAME_ROOT_NODE;
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return self::PHYSICAL_ADDRESS_ROOT_NODE;
    }
}
