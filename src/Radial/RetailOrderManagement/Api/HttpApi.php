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

namespace eBayEnterprise\RetailOrderManagement\Api;

use eBayEnterprise\RetailOrderManagement\Api\Exception\UnsupportedOperation;
use eBayEnterprise\RetailOrderManagement\Api\TLogger;
use eBayEnterprise\RetailOrderManagement\Payload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class HttpApi implements IBidirectionalApi
{
    use TLogger;

    /** @var IConfig */
    protected $config;
    /** @var Payload\IPayload */
    protected $requestPayload;
    /** @var  Payload\IPayload */
    protected $replyPayload;
    /** @var  Payload\IBidirectionalMessageFactory */
    protected $messageFactory;
    /** @var  \Requests_Response Response object from the last call to Requests */
    protected $lastRequestsResponse;
    /** @var LoggerInterface */
    protected $logger;

    /**
     * Configure the api by supplying an object that informs
     * what payload object to use, what URI to send to, etc.
     *
     * @param IHttpConfig $config
     * @param LoggerInterface $logger
     */
    public function __construct(IHttpConfig $config, LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
        $this->config = $config;

        \Requests::register_autoloader();

        $this->messageFactory = new Payload\BidirectionalMessageFactory($this->config);

        $this->logRequestUrl();
    }

    public function setRequestBody(Payload\IPayload $payload)
    {
        $this->requestPayload = $payload;
        return $this;
    }

    public function send()
    {
        $requestData = $this->getRequestBody()->serialize();
        $this->logPayloadMessage($requestData, 'Payload request body', 'rom_request_body');

        // actually do the request
        try {
            if ($this->sendRequest() === false) {
                $message = sprintf(
                    "HTTP result %s for %s to %s.\n%s",
                    $this->lastRequestsResponse->status_code,
                    $this->config->getHttpMethod(),
                    $this->lastRequestsResponse->url,
                    $this->lastRequestsResponse->body
                );
                throw new Exception\NetworkError($message);
            }
        } catch (\Requests_Exception $e) {
            // simply pass through the message but with an expected exception type - don't
            // have any request/response to include as this exception only occurs
            // when the request cannot even be attempted.
            throw new Exception\NetworkError($e->getMessage());
        }

        $responseData = $this->lastRequestsResponse->body;
        $this->logPayloadMessage($responseData, 'Payload response body', 'rom_response_body');
        $this->getResponseBody()->deserialize($responseData);

        return $this;
    }

    /**
     * @return boolean
     * @throws Exception\UnsupportedHttpAction
     */
    protected function sendRequest()
    {
        // clear the old response
        $this->lastRequestsResponse = null;
        $httpMethod = strtolower($this->config->getHttpMethod());
        if (!method_exists($this, $httpMethod)) {
            throw new Exception\UnsupportedHttpAction(
                sprintf(
                    'HTTP action %s not supported.',
                    strtoupper($httpMethod)
                )
            );
        }

        return $this->$httpMethod();
    }

    public function getResponseBody()
    {
        if ($this->replyPayload !== null) {
            return $this->replyPayload;
        }

        // If a payload doesn't exist for the response, the operation cannot
        // be supported.
        try {
            $this->replyPayload = $this->messageFactory->replyPayload();
        } catch (Payload\Exception\UnsupportedPayload $e) {
            throw new UnsupportedOperation();
        }
        return $this->replyPayload;
    }

    /**
     * @return \Requests_Response
     * @throws \Requests_Exception
     */
    protected function post()
    {
	$options = array();
	
	if( $this->config->getResponseTimeout() )
	{
		$timeoutSeconds = (float)$this->config->getResponseTimeout() / 1000.0;
		$options = array( 'timeout' => $timeoutSeconds );
	}

        $this->lastRequestsResponse = \Requests::post(
            $this->config->getEndpoint(),
            $this->buildHeader(),
            $this->getRequestBody()->serialize(),
	    $options
        );
        return $this->lastRequestsResponse->success;
    }

    protected function buildHeader()
    {
        return [
            'apikey' => $this->config->getApiKey(),
            'Content-type' => $this->config->getContentType()
        ];
    }

    public function getRequestBody()
    {
        if ($this->requestPayload !== null) {
            return $this->requestPayload;
        }
        // If a payload doesn't exist for the request, the operation cannot
        // be supported.
        try {
            $this->requestPayload = $this->messageFactory->requestPayload();
        } catch (Payload\Exception\UnsupportedPayload $e) {
            throw new UnsupportedOperation();
        }
        return $this->requestPayload;
    }

    /**
     * @return \Requests_Response
     * @throws \Requests_Exception
     */
    protected function get()
    {
        $this->lastRequestsResponse = \Requests::post(
            $this->config->getEndpoint(),
            $this->buildHeader()
        );
        return $this->lastRequestsResponse->success;
    }

    protected function getRequestUrlLogData()
    {
        $context = $this->getContext();
        $logData = ['rom_request_url' => $this->config->getEndpoint()];
        return $context ? $context->getMetaData(__CLASS__, $logData) : [];
    }

    protected function logRequestUrl()
    {
        $logMessage = 'SDK API endpoint: {rom_request_url}';
        $this->logger->debug($logMessage, $this->getRequestUrlLogData());
        return $this;
    }

    /**
     * Log the request payload.
     *
     * @param  string
     * @param  string
     * @param  string
     * @return self
     */
    protected function logPayloadMessage($xmlPayload, $logMessage, $key)
    {
        $this->logger->debug($logMessage, $this->addLogContext($key, $xmlPayload));
        return $this;
    }
}
