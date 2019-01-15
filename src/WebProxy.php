<?php

namespace WebProxy;

use WebProxy\Clients\ClientFactory;
use WebProxy\Endpoints\EndpointInterface;
use WebProxy\Endpoints\HttpEndpoint;
use WebProxy\Endpoints\SoapEndpoint;
use WebProxy\Support\Enumerations\Method;
use WebProxy\Support\Exceptions\UnsupportedMethodException;
use WebProxy\Support\Wrappers\Request;

/**
 * Class Proxy
 *
 * Performs all HTTP requests and passes the response to appropriate
 *
 */
class WebProxy
{

	/**
	 * Shorthand for GET httpRequest.
	 *
	 * @param HttpEndpoint $endpoint Website or RestResource.
	 * @param array        $headers  HTTP headers submitted with the request.
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
	 * @param array        $postBody
	 * @param array        $files
	 * @param array        $headers  HTTP headers submitted with the request.
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
	 * Shorthand for DELETE httpRequest
	 *
	 * @param HttpEndpoint $endpoint Website or RestResource.
	 * @param array        $headers  HTTP headers submitted with the request.
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
	 * @param Request      $request  Request options.
	 *
	 * @return HttpEndpoint The same object that has been passed trough parameter $endpoint, but with response.
	 *
	 * @throws UnsupportedMethodException
	 * @throws Support\Exceptions\UnknownClient
	 * @throws Support\Exceptions\UnknownService
	 */
	public function httpRequest(HttpEndpoint $endpoint, Request $request): HttpEndpoint
	{
		if (! $endpoint->supportsMethod($request->getMethod())) {
			throw new UnsupportedMethodException();
		}

		$client   = ClientFactory::getClientForService($endpoint->getService());
		$response = $client->request($endpoint, $request);
		$endpoint->setResponse($response);

		return $endpoint;
	}

	/**
	 * Shorthand for soapCall
	 *
	 * @param SoapEndpoint $endpoint
	 * @param array        $parameters
	 *
	 * @return SoapEndpoint
	 * @throws Support\Exceptions\UnknownClient
	 * @throws Support\Exceptions\UnknownService
	 */
	public function call(SoapEndpoint $endpoint, array $parameters = []): SoapEndpoint
	{
		$request = Request::create(Method::RPC)
						  ->withBody($parameters);

		return $this->soapRequest($endpoint, $request);
	}

	/**
	 * @param SoapEndpoint $endpoint
	 * @param Request      $request
	 *
	 * @return SoapEndpoint
	 * @throws Support\Exceptions\UnknownClient
	 * @throws Support\Exceptions\UnknownService
	 */
	public function soapRequest(SoapEndpoint $endpoint, Request $request)
	{
		if ($request->getMethod() !== Method::RPC) {
			throw new UnsupportedMethodException();
		}

		$client   = ClientFactory::getClientForService($endpoint->getService());
		$response = $client->request($endpoint, $request);
		$endpoint->setResponse($response);

		return $endpoint;
	}
}
