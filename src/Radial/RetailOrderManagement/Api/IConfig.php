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

namespace Radial\RetailOrderManagement\Api;

/**
 * Interface IConfig
 * @package Radial\RetailOrderManagement\Api
 *
 * Provides an IApi object with the metadata it needs to send and/or receive
 * the right payload objects to the right places.
 */
interface IConfig
{
    /**
     * Provide a means to look up constant configuration information
     * from PayloadConfigMap
     *
     * @see Radial/RetailOrderManagement/Payload/PayloadConfigMap.php
     * @return string
     */
    public function getConfigKey();
}
