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

namespace Radial\RetailOrderManagement\Payload\TaxDutyFee;

trait TPriceGroup
{
    /** @var float */
    protected $amount;
    /** @var float */
    protected $unitPrice;
    /** @var string */
    protected $rootNodeName;

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $this->sanitizeAmount($amount);
        return $this;
    }

    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    public function setUnitPrice($unitPrice)
    {
        $cleanPrice = $this->sanitizeAmount($unitPrice);
        $this->unitPrice = $cleanPrice;
        return $this;
    }

    /**
     * Dynamically set the name of the root node the price group gets serialized
     * with. As this type can represent a variant of pricing information,
     * serializations will vary based upon context.
     *
     * @param string Must be a valid XML node name
     */
    public function setRootNodeName($nodeName)
    {
        $this->rootNodeName = $nodeName;
        return $this;
    }

    /**
     * Serialize the price group amount value, including a remainder attribute
     * if a remainder amount has been set.
     *
     * @return string
     */
    protected function serializePriceGroupAmount()
    {
        return "<Amount>{$this->formatAmount($this->getAmount())}</Amount>";
    }
}
