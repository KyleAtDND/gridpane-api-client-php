<?php

namespace Gridpane\Api;

/*
 * Dead simple autoloader:
 * spl_autoload_register(function($c){@include 'src/'.preg_replace('#\\\|_(?!.+\\\)#','/',$c).'.php';});
 */

use Gridpane\Api\Exceptions\AuthException;
use Gridpane\Api\Middleware\RetryHandler;
use Gridpane\Api\Resources\Core\Server;
use Gridpane\Api\Traits\Utility\InstantiatorTrait;
use Gridpane\Api\Utilities\Auth;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;

/**
 * Client class, base level access
 *
 * @method Server server($id = null)
 */
class HttpClient
{
    const VERSION = '1.0.0';

    use InstantiatorTrait;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string This is appended between the full base domain and the resource endpoint
     */
    protected $apiBasePath;

    /**
     * @var Debug
     */
    protected $debug;

    /**
     * @var \GuzzleHttp\Client
     */
    public $guzzle;

    /**
     * @param  string  $scheme
     * @param  string  $hostname
     * @param  int  $port
     * @param  \GuzzleHttp\Client  $guzzle
     */
    public function __construct(
        $scheme = 'https',
        $hostname = 'zendesk.com',
        $port = 443,
        $guzzle = null
    ) {
        if (is_null($guzzle)) {
            $handler = HandlerStack::create();
            $handler->push(new RetryHandler(['retry_if' => function ($retries, $request, $response, $e) {
                return $e instanceof RequestException && strpos($e->getMessage(), 'ssl') !== false;
            }]), 'retry_handler');
            $this->guzzle = new \GuzzleHttp\Client(compact('handler'));
        } else {
            $this->guzzle = $guzzle;
        }

        $this->hostname = $hostname;
        $this->scheme = $scheme;
        $this->port = $port;
        $this->apiUrl = "$scheme://$hostname:$port/";
        $this->debug = new Debug();
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getValidSubResources()
    {
        return [
            'Server' => Server::class,
        ];
    }

    /**
     * @return Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Configure the authorization method
     *
     * @param    $strategy
     * @param  array  $options
     *
     * @throws AuthException
     */
    public function setAuth($strategy, array $options)
    {
        $this->auth = new Auth($strategy, $options);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param  string  $key The name of the header to set
     * @param  string  $value The value to set in the header
     * @return HttpClient
     *
     * @internal param array $headers
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Return the user agent string
     *
     * @return string
     */
    public function getUserAgent()
    {
        return 'GridpaneAPI PHP '.self::VERSION;
    }

    /**
     * Returns the generated api URL
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Sets the api base path
     *
     * @param  string  $apiBasePath
     */
    public function setApiBasePath($apiBasePath)
    {
        $this->apiBasePath = $apiBasePath;
    }

    /**
     * Returns the api base path
     *
     * @return string
     */
    public function getApiBasePath()
    {
        return $this->apiBasePath;
    }

    /**
     * Set debug information as an object
     *
     * @param  mixed  $lastRequestHeaders
     * @param  mixed  $lastRequestBody
     * @param  mixed  $lastResponseCode
     * @param  string  $lastResponseHeaders
     * @param  mixed  $lastResponseError
     */
    public function setDebug(
        $lastRequestHeaders,
        $lastRequestBody,
        $lastResponseCode,
        $lastResponseHeaders,
        $lastResponseError
    ) {
        $this->debug->lastRequestHeaders = $lastRequestHeaders;
        $this->debug->lastRequestBody = $lastRequestBody;
        $this->debug->lastResponseCode = $lastResponseCode;
        $this->debug->lastResponseHeaders = $lastResponseHeaders;
        $this->debug->lastResponseError = $lastResponseError;
    }

    /**
     * Returns debug information in an object
     *
     * @return Debug
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * This is a helper method to do a get request.
     *
     * @param    $endpoint
     * @param  array  $queryParams
     * @return \stdClass | null
     *
     * @throws \Gridpane\Api\Exceptions\AuthException
     * @throws \Gridpane\Api\Exceptions\ApiResponseException
     */
    public function get($endpoint, $queryParams = [])
    {
        $response = Http::send(
            $this,
            $endpoint,
            ['queryParams' => $queryParams]
        );

        return $response;
    }

    /**
     * This is a helper method to do a post request.
     *
     * @param    $endpoint
     * @param  array  $postData
     * @param  array  $options
     * @return null|\stdClass
     *
     * @throws \Gridpane\Api\Exceptions\AuthException
     * @throws \Gridpane\Api\Exceptions\ApiResponseException
     */
    public function post($endpoint, $postData = [], $options = [])
    {
        $extraOptions = array_merge($options, [
            'postFields' => $postData,
            'method' => 'POST',
        ]);

        $response = Http::send(
            $this,
            $endpoint,
            $extraOptions
        );

        return $response;
    }

    /**
     * This is a helper method to do a put request.
     *
     * @param    $endpoint
     * @param  array  $putData
     * @return \stdClass | null
     *
     * @throws \Gridpane\Api\Exceptions\AuthException
     * @throws \Gridpane\Api\Exceptions\ApiResponseException
     */
    public function put($endpoint, $putData = [])
    {
        $response = Http::send(
            $this,
            $endpoint,
            ['postFields' => $putData, 'method' => 'PUT']
        );

        return $response;
    }

    /**
     * This is a helper method to do a delete request.
     *
     * @param $endpoint
     * @return null
     *
     * @throws \Gridpane\Api\Exceptions\AuthException
     * @throws \Gridpane\Api\Exceptions\ApiResponseException
     */
    public function delete($endpoint)
    {
        $response = Http::send(
            $this,
            $endpoint,
            ['method' => 'DELETE']
        );

        return $response;
    }
}
