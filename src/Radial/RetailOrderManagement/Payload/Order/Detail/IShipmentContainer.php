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

namespace Radial\RetailOrderManagement\Payload\Order\Detail;

interface IShipmentContainer
{
    const SHIPMENT_ITERABLE_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\Order\Detail\IShipmentIterable';

    /**
     * @return IShipmentIterable
     */
    public function getShipments();

    /**
     * @param  IShipmentIterable
     * @return self
     */
    public function setShipments(IShipmentIterable $shipments);
}
