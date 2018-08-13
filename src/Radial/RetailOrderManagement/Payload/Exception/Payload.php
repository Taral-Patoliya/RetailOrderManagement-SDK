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

namespace Radial\RetailOrderManagement\Payload\Exception;

/**
 * Class Payload
 * @package Radial\RetailOrderManagement\Payload\Exception
 *
 * Provides a grouping of Payload-related exceptions.
 * (PHP doesn't allow you to catch more than one type of exception in a single try/catch statement,
 * so a superclass provides for the possibility that you might want to catch all Payload-related
 * exceptions at once. This could be YAGNI, but it's cheap.)
 */
class Payload extends \Exception
{
}
