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

namespace eBayEnterprise\RetailOrderManagement\Api\Amqp;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Abstract Class AbstractConnectionConfig
 * @package eBayEnterprise\RetailOrderManagement\Api\Amqp
 */
abstract class AbstractConnectionConfig implements IConnectionConfig
{
    /** @var string */
    protected $connectionHostname;
    /** @var string */
    protected $connectionPort;
    /** @var string */
    protected $connectionUsername;
    /** @var string */
    protected $connectionPassword;
    /** @var string */
    protected $connectionVhost;
    /** @var array */
    protected $connectionContext;
    /** @var bool */
    protected $connectionInsist;
    /** @var string */
    protected $connectionLoginMethod;
    /** @var string */
    protected $connectionLocale;
    /** @var string */
    protected $connectionTimeout;
    /** @var string */
    protected $connectionReadWriteTimeout;
    /** @var int */
    protected $connectionHeartbeat;
    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param string $connectionHostname
     * @param string $connectionPort
     * @param string $connectionUsername
     * @param string $connectionPassword
     * @param string $connectionVhost
     * @param array $connectionContext
     * @param bool|null $connectionInsist
     * @param string|null $connectionLoginMethod
     * @param string|null $connectionLocale
     * @param int|null $connectionTimeout
     * @param int|null $connectionReadWriteTimeout
     * @param LoggerInterface $logger
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $connectionHostname,
        $connectionPort,
        $connectionUsername,
        $connectionPassword,
        $connectionVhost,
        array $connectionContext = [],
        $connectionInsist = null,
        $connectionLoginMethod = null,
        $connectionLocale = null,
        $connectionTimeout = null,
        $connectionReadWriteTimeout = null,
	$connectionHeartbeat = null,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?: new NullLogger();
        $this->connectionHostname = $connectionHostname;
        $this->connectionPort = $connectionPort;
        $this->connectionUsername = $connectionUsername;
        $this->connectionPassword = $connectionPassword;
        $this->connectionVhost = $connectionVhost;
        $this->connectionContext = $connectionContext;
        $this->connectionInsist = $connectionInsist;
        $this->connectionLoginMethod = $connectionLoginMethod;
        $this->connectionLocale = $connectionLocale;
        $this->connectionTimeout = $connectionTimeout;
        $this->connectionReadWriteTimeout = $connectionReadWriteTimeout;
	$this->connectionHeartbeat = $connectionHeartbeat;
    }

    abstract public function getConnectionParams();
}
