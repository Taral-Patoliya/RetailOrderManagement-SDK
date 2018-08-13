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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

trait TExchangeOrderContainer
{
    /** @var IExchangeOrderIterable */
    protected $exchangeOrders;

    /**
     * @see IExchangeOrderContainer::getExchangeOrders()
     */
    public function getExchangeOrders()
    {
        return $this->exchangeOrders;
    }

    /**
     * @see IExchangeOrderContainer::getExchangeOrders()
     */
    public function setExchangeOrders(IExchangeOrderIterable $exchangeOrders)
    {
        $this->exchangeOrders = $exchangeOrders;
        return $this;
    }
}
