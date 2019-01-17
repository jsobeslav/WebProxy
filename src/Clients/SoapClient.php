<?php declare(strict_types = 1);

namespace WebProxy\Clients;

use WebProxy\Endpoints\EndpointInterface;
use WebProxy\Services\SoapService;
use WebProxy\Support\Wrappers\Request;
use WebProxy\Support\Wrappers\Response;

class SoapClient extends Client
{

	/**
	 * Pass the request parameters to SoapService's own native SoapClient instance and return response.
	 *
	 * @param EndpointInterface $endpoint
	 * @param Request           $request
	 *
	 * @return Response
	 */
	public function request(EndpointInterface $endpoint, Request $request): Response
	{
		/** @var SoapService $service */
		$service      = $endpoint->getService();
		$nativeClient = $service->getNativeClient();

		// Append call headers to the service.
		$headers = $this->processHeaders($request->getHeaders());
		$nativeClient->__setSoapHeaders($headers);

		// Perform call.
		$result = $nativeClient->__soapCall(
			$endpoint->getRequestName(),
			$request->getBody()
		);

		$responseObject = (object) [
			'methodResult' => $result,
			'responseData' => $nativeClient->__getLastResponse(),
		];

		$responseBody = $responseObject->responseData;

		return new Response($responseObject, $responseBody);
	}

	/**
	 * Adapt Request's headers array to SoapHeaders array.
	 *
	 * @param array $headers From Request object.
	 *
	 *  <pre>
	 * [
	 *    [
	 *        'name' => 'Header-Name',
	 *        'value' => 'Header-Value'
	 *        'namespace' => 'optional-namespace',
	 *    ]
	 * ]
	 * </pre>.
	 *
	 * @return array Array of \SoapHeader objects.
	 *
	 * [
	 *    \SoapHeader,
	 *    \SoapHeader
	 * ].
	 */
	private function processHeaders(array $headers): array
	{
		$return = [];
		foreach ($headers as $header) {
			$return[] = new \SoapHeader(
				$header['namespace'] ?? '',
				$header['name'],
				$header['value']
			);
		}

		return $return;
	}
}
