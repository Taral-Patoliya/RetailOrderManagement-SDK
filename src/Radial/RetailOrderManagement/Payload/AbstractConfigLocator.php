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

namespace Radial\RetailOrderManagement\Payload;

use Radial\RetailOrderManagement\Payload\Exception\UnsupportedPayload;

/**
 * Abstract implementation of a payload locator using an array of config data
 * to providing details on how to construct various types of payloads.
 */
abstract class AbstractConfigLocator implements ILocator
{
    /**
     * Indicates if the locator knows about a specific type of payload.
     *
     * @param string
     * @return bool
     */
    public function hasPayloadConfig($type)
    {
        return isset($this->getConfig()[$type]);
    }

    /**
     * Get the payload configuration for a specific type of payload.
     *
     * @param string
     * @return array
     * @throws UnsupportedPayload
     */
    public function getPayloadConfig($type)
    {
        if ($this->hasPayloadConfig($type)) {
            return $this->getConfig()[$type];
        }
        throw new UnsupportedPayload("No configuration found for $type.");
    }

    /**
     * Template method for getting the blob of config data.
     *
     * @return array
     */
    abstract protected function getConfig();
}
