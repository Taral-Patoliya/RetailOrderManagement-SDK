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

interface IItemPriceAdjustment extends IPayload
{
    const ROOT_NODE = 'LineItemAdjustment';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = '/events/1.0/events/Order-PriceAdjustment-Event-1.0.xsd';

    /**
     * @return string $modificationType
     */
    public function getModificationType();

    /**
     * @param string $modificationType
     */
    public function setModificationType($modificationType);

    /**
     * @return string $adjustmentCategory
     */
    public function getAdjustmentCategory();

    /**
     * @param string $adjustmentCategory
     */
    public function setAdjustmentCategory($adjustmentCategory);

    /**
     * @return bool $isCredit
     */
    public function getIsCredit();

    /**
     * @param bool $isCredit
     */
    public function setIsCredit($isCredit);

    /**
     * @return float $amount
     */
    public function getAmount();

    /**
     * @param float $amount
     */
    public function setAmount($amount);
}
