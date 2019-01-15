<?php declare(strict_types = 1);

namespace WebProxy\Clients;

use GuzzleHttp\Client as GuzzleClient;
use WebProxy\Endpoints\EndpointInterface;
use WebProxy\Services\ServiceFactory;
use WebProxy\Support\Wrappers\Request;
use WebProxy\Support\Wrappers\Response;

class HttpClient extends Client
{

	/** @var GuzzleClient */
	private $guzzle;

	/**
	 * HttpClient constructor.
	 */
	public function __construct()
	{
		$this->guzzle = new GuzzleClient();
	}

	/**
	 * @param EndpointInterface $endpoint
	 * @param Request           $request
	 *
	 * @return Response
	 */
	public function request(EndpointInterface $endpoint, Request $request): Response
	{
		$fullUri = append_slash($endpoint->getServiceUri())
				   . trim_slashes($endpoint->getRequestName())
				   . prepend_slash($endpoint->getQueryString());

		$responseObject = $this->guzzle->request(
			$request->getMethod(),
			$fullUri,
			$request->getGuzzleOptions()
		);

		$responseBody = $responseObject->getBody()->getContents();

		return new Response($responseObject, $responseBody);
	}
}
