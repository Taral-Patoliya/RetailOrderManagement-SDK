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

interface IGiftCardActivationContainer
{
    const GIFT_CARD_ACTIVATION_ITERABLE_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\OrderEvents\IGiftCardActivationIterable';

    /**
     * Get all gift card activations for the order.
     *
     * @return IGiftCardActivationIterable
     */
    public function getGiftCardActivations();
    /**
     * @param IGiftCardActivationIterable
     * @return self
     */
    public function setGiftCardActivations(IGiftCardActivationIterable $giftCardActivations);
}
