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

namespace Radial\RetailOrderManagement\Payload\Order;

use Radial\RetailOrderManagement\Payload\Checkout\IDestinationIterable;
use Radial\RetailOrderManagement\Payload\Checkout\IEmailAddressDestination;
use Radial\RetailOrderManagement\Payload\Checkout\IMailingAddress;

interface IOrderDestinationIterable extends IDestinationIterable
{
    const MAILING_ADDRESS_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\Order\IMailingAddress';
    const STORE_LOCATION_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\Order\IStoreLocation';
    const EMAIL_ADDRESS_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\Order\IEmailAddressDestination';

    /**
     * Get a new, empty mailing address destination object.
     *
     * @return IMailingAddress
     */
    public function getEmptyMailingAddress();

    /**
     * Get a new, empty store location destination object.
     *
     * @return IStoreLocation
     */
    public function getEmptyStoreLocation();

    /**
     * Get a new, empty email address destination object.
     *
     * @return IEmailAddressDestination
     */
    public function getEmptyEmailAddress();
}
