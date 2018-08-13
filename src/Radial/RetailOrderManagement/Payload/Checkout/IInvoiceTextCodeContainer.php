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

namespace Radial\RetailOrderManagement\Payload\Checkout;

interface IInvoiceTextCodeContainer
{
    const INVOICE_TEXT_CODE_ITERABLE_INTERFACE =
        '\Radial\RetailOrderManagement\Payload\Checkout\IInvoiceTextCodeIterable';

    /**
     * Get all taxes in the container.
     *
     * @return IInvoiceTextCodeIterable
     */
    public function getInvoiceTextCodes();

    /**
     * @param IInvoiceTextCodeIterable
     * @return self
     */
    public function setInvoiceTextCodes(IInvoiceTextCodeIterable $taxes);
}
