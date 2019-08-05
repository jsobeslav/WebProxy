<?php

namespace WebProxy;

use WebProxy\Clients\ClientFactory;
use WebProxy\Endpoints\HttpEndpoint;
use WebProxy\Endpoints\SoapEndpoint;
use WebProxy\Support\Enumerations\Method;
use WebProxy\Support\Exceptions\UnsupportedMethodException;
use WebProxy\Support\Wrappers\Request;

/**
 * Class WebProxy
 *
 * Performs all requests on endpoints and returns the modified instances.
 */
class WebProxy
{

	/** @var array $defaultRequestOptions Default options appended to all requests. */
	private $defaultRequestOptions = [];

	/** @var array $defaultRequestHeaders Default headers appended to all requests. */
	private $defaultRequestHeaders = [];

	/**
	 * Shorthand for GET httpRequest.
	 *
	 * @param HttpEndpoint $endpoint Website or RestResource.
	 * @param array        $headers  HTTP headers submitted with the request.
	 *
	 * <pre>
	 * [
	 *    'Header-Name' => 'Header-Value'
	 * ]
	 * </pre>.
	 *
	 * @return HttpEndpoint The same object that has been passed trough parameter $endpoint, but with response.
	 *
	 * @throws UnsupportedMethodException From self::httpRequest().
	 * @throws Support\Exceptions\UnknownClient From self::httpRequest().
	 * @throws Support\Exceptions\UnknownService From self::httpRequest().
	 */
	public function get(HttpEndpoint $endpoint, array $headers = []): HttpEndpoint
	{
		$request = Request::create(Method::GET)
						  ->withHeaders($headers);

		return $this->httpRequest($endpoint, $request);
	}

	/**
	 * Shorthand for POST httpRequest
	 *
	 * @param HttpEndpoint $endpoint Website or RestResource.
	 * @param array        $postBody Text fields.
	 *
	 * <pre>
	 * [
	 *    'foo' => 'bar',
	 *    'baz' => ['hi', 'there!']
	 * ]
	 * </pre>.
	 *
	 * @param array        $files    Files supplied with the request.
	 *
	 * <pre>
	 * [
	 *    'filename' => '/fullPath/to/file'
	 * ]
	 * </pre>
	 *
	 * OR
	 *
	 * <pre>
	 * [
	 *    [
	 *        'name'     => 'filename',
	 *        'contents' => fopen('/path/to/file', 'r'),
	 *        'filename' => 'custom_filename.txt'
	 *    ],
	 * ]
	 * </pre>.
	 *
	 * @param array        $headers  HTTP headers submitted with the request.
	 *
	 * <pre>
	 * [
	 *    'Header-Name' => 'Header-Value'
	 * ]
	 * </pre>.
	 *
	 * @return HttpEndpoint The same object that has been passed trough parameter $endpoint, but with response.
	 *
	 * @throws UnsupportedMethodException From self::httpRequest().
	 * @throws Support\Exceptions\UnknownClient From self::httpRequest().
	 * @throws Support\Exceptions\UnknownService From self::httpRequest().
	 * @throws Support\Exceptions\RequestBodyTypeMismatchException From self::httpRequest().
	 */
	public function post(HttpEndpoint $endpoint, array $postBody = [], array $files = [], array $headers = [])
	{
		$request = Request::create(Method::POST)
						  ->withFiles($files)
						  ->withBody($postBody)
						  ->withHeaders($headers);

		return $this->httpRequest($endpoint, $request);
	}

	/**
	 * Shorthand for PUT httpRequest
	 *
	 * @param HttpEndpoint $endpoint Website or RestResource.
	 * @param array        $postBody Text fields.
	 *
	 * <pre>
	 * [
	 *    'foo' => 'bar',
	 *    'baz' => ['hi', 'there!']
	 * ]
	 * </pre>.
	 *
	 * @param array        $files    Files supplied with the request.
	 *
	 * <pre>
	 * [
	 *    'filename' => '/fullPath/to/file'
	 * ]
	 * </pre>
	 *
	 * OR
	 *
	 * <pre>
	 * [
	 *    [
	 *        'name'     => 'filename',
	 *        'contents' => fopen('/path/to/file', 'r'),
	 *        'filename' => 'custom_filename.txt'
	 *    ],
	 * ]
	 * </pre>.
	 *
	 * @param array        $headers  HTTP headers submitted with the request.
	 *
	 * <pre>
	 * [
	 *    'Header-Name' => 'Header-Value'
	 * ]
	 * </pre>.
	 *
	 * @return HttpEndpoint The same object that has been passed trough parameter $endpoint, but with response.
	 *
	 * @throws UnsupportedMethodException From self::httpRequest().
	 * @throws Support\Exceptions\UnknownClient From self::httpRequest().
	 * @throws Support\Exceptions\UnknownService From self::httpRequest().
	 * @throws Support\Exceptions\RequestBodyTypeMismatchException From self::httpRequest().
	 */
	public function put(HttpEndpoint $endpoint, array $postBody = [], array $files = [], array $headers = [])
	{
		$request = Request::create(Method::PUT)
						  ->withFiles($files)
						  ->withBody($postBody)
						  ->withHeaders($headers);

		return $this->httpRequest($endpoint, $request);
	}

	/**
	 * Shorthand for DELETE httpRequest
	 *
	 * @param HttpEndpoint $endpoint Website or RestResource.
	 * @param array        $headers  HTTP headers submitted with the request.
	 *
	 * <pre>
	 * [
	 *    'Header-Name' => 'Header-Value'
	 * ]
	 * </pre>.
	 *
	 * @return HttpEndpoint The same object that has been passed trough parameter $endpoint, but with response.
	 *
	 * @throws UnsupportedMethodException From self::httpRequest().
	 * @throws Support\Exceptions\UnknownClient From self::httpRequest().
	 * @throws Support\Exceptions\UnknownService From self::httpRequest().
	 */
	public function delete(HttpEndpoint $endpoint, array $headers = []): HttpEndpoint
	{
		$request = Request::create(Method::DELETE)
						  ->withHeaders($headers);

		return $this->httpRequest($endpoint, $request);
	}

	/**
	 * @param HttpEndpoint $endpoint Website or RestResource.
	 * @param Request      $request  Request options object.
	 *
	 * @return HttpEndpoint The same object that has been passed trough parameter $endpoint, but with response.
	 *
	 * @throws UnsupportedMethodException When request method is blocked by Endpoint's method whitelist.
	 * @throws Support\Exceptions\UnknownClient When there is no client suitable for Endpoint's service type.
	 * @throws Support\Exceptions\UnknownService When is Endpoint's service is misconfigured.
	 */
	public function httpRequest(HttpEndpoint $endpoint, Request $request): HttpEndpoint
	{
		if (! $endpoint->supportsMethod($request->getMethod())) {
			throw new UnsupportedMethodException();
		}

		// Append default options and headers.
		$request->appendOptions($this->getDefaultRequestOptions());
		$request->appendHeaders($this->getDefaultRequestHeaders());

		// Perform request.
		$client   = ClientFactory::getClientForService($endpoint->getService());
		$response = $client->request($endpoint, $request);
		$endpoint->setResponse($response);

		return $endpoint;
	}

	/**
	 * Shorthand for soapCall
	 *
	 * @param SoapEndpoint $endpoint   SoapEndpoint.
	 * @param array        $parameters Parameters supplied to remote method.
	 *
	 * <pre>
	 * [
	 *    'foo' => 'bar',
	 *    'baz' => ['hi', 'there!']
	 * ]
	 * </pre>.
	 *
	 * @param array        $headers    Headers supplied with the request.
	 *
	 * <pre>
	 * [
	 *        [
	 *                     'name' => 'Header-Name',
	 *                     'value' => 'Header-Value'
	 *                     'namespace' => 'optional-namespace',
	 *        ]
	 * ]
	 * </pre>.
	 *
	 * @return SoapEndpoint  The same object that has been passed trough parameter $endpoint, but with response.
	 *
	 * @throws Support\Exceptions\UnknownClient
	 * @throws Support\Exceptions\UnknownService
	 * @throws UnsupportedMethodException
	 */
	public function call(SoapEndpoint $endpoint, array $parameters = [], array $headers = []): SoapEndpoint
	{
		$request = Request::create(Method::RPC)
						  ->withBody($parameters)
						  ->withHeaders($headers);

		return $this->soapRequest($endpoint, $request);
	}

	/**
	 * @param SoapEndpoint $endpoint Soap Endpoint.
	 * @param Request      $request  Request options object.
	 *
	 * @return SoapEndpoint  The same object that has been passed trough parameter $endpoint, but with response.
	 *
	 * @throws UnsupportedMethodException When the method is not set to RPC.
	 * @throws Support\Exceptions\UnknownClient When there is no client suitable for Endpoint's service type.
	 * @throws Support\Exceptions\UnknownService When is Endpoint's service is misconfigured.
	 */
	public function soapRequest(SoapEndpoint $endpoint, Request $request)
	{
		if ($request->getMethod() !== Method::RPC) {
			throw new UnsupportedMethodException();
		}

		// Append default options and headers.
		$request->appendOptions($this->getDefaultRequestOptions());
		$request->appendHeaders($this->getDefaultRequestHeaders());

		// Perform request.
		$client   = ClientFactory::getClientForService($endpoint->getService());
		$response = $client->request($endpoint, $request);
		$endpoint->setResponse($response);

		return $endpoint;
	}

	/**
	 * @return array
	 */
	public function getDefaultRequestOptions(): array
	{
		return $this->defaultRequestOptions;
	}

	/**
	 * @param array $options
	 */
	public function setDefaultRequestOptions(array $options): void
	{
		$this->defaultRequestOptions = $options;
	}

	/**
	 * @return array
	 */
	public function getDefaultRequestHeaders(): array
	{
		return $this->defaultRequestHeaders;
	}

	/**
	 * @param array $headers
	 */
	public function setDefaultRequestHeaders(array $headers): void
	{
		$this->defaultRequestHeaders = $headers;
	}

}
