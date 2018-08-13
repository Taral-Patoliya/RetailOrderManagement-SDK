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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Radial\RetailOrderManagement\Payload\Order\Detail;

use Radial\RetailOrderManagement\Payload\IPayload;

interface IShippedItem extends IOrderDetailTrackingNumberContainer, IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'ShippedItem';

    /**
     * @return string
     */
    public function getItem();

    /**
     * @param  string
     * @return self
     */
    public function setItem($item);

    /**
     * Contains the OrderID to which the item belongs.
     *
     * @return string
     */
    public function getRef();

    /**
     * @param  string
     * @return self
     */
    public function setRef($ref);

    /**
     * The number of items included in the shipment.
     * Allowable Values: Integer
     * Required: Yes
     * Length: TBD
     * Default Value: Blank
     *
     * @return float
     */
    public function getQuantity();

    /**
     * @param  float
     * @return self
     */
    public function setQuantity($quantity);

    /**
     * @return string
     */
    public function getInvoiceNo();

    /**
     * @param  string
     * @return self
     */
    public function setInvoiceNo($invoiceNo);
}
