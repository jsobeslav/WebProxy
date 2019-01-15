<?php declare(strict_types = 1);

namespace WebProxy\Clients;

use WebProxy\Endpoints\EndpointInterface;
use WebProxy\Services\SoapService;
use WebProxy\Support\Wrappers\Request;
use WebProxy\Support\Wrappers\Response;

class SoapClient extends Client
{

	/**
	 * @param EndpointInterface $endpoint
	 * @param Request           $request
	 *
	 * @return Response
	 */
	public function request(EndpointInterface $endpoint, Request $request): Response
	{
		// @var SoapService $service
		$service = $endpoint->getService();
		$nusoap  = $service->getNusoapClient();

		$result = $nusoap->call($endpoint->getRequestName(), $request->getBody());

		$responseObject = (object) [
			'methodResult' => $result,
			'response'     => $nusoap->response,
			'responseData' => $nusoap->responseData,
		];

		$responseBody = $responseObject->responseData;

		return new Response($responseObject, $responseBody);
	}
}
