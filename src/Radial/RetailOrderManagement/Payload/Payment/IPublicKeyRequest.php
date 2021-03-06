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

namespace Radial\RetailOrderManagement\Payload\Payment;

use Radial\RetailOrderManagement\Payload\IPayload;
/**
 * Interface IPublicKeyRequest
 * @package Radial\RetailOrderManagement\Payload\Payment
 */
interface IPublicKeyRequest extends IPayload
{
    // XML related values - document root node, XMLNS and name of the xsd schema file
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'PublicKeyRequest';
    const XSD = '/checkout/1.0/Payment-Service-PublicKey-1.0.xsd';

    /**
     *
     * xsd restrictions: 3-8 characters
     * @return string
     */
    public function getAlgorithmVersion();

    /**
     * @param string
     * @return self
     */
    public function setAlgorithmVersion($algorithmVersion);
}
