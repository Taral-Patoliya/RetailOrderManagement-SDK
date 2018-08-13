<?php
/**
 * Copyright (c) 2014-2015 eBay Enterprise, Inc.
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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

interface IOrderItemRequestContainer
{
    const ORDER_ITEM_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IOrderItemRequestIterable';

    /**
     * Get all items within the container.
     *
     * @return IOrderItemIterable
     */
    public function getItems();

    /**
     * @param IOrderItemIterable
     * @return self
     */
    public function setItems(IOrderItemRequestIterable $iterable);
}
