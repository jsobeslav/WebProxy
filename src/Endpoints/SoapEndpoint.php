<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use WebProxy\Support\Wrappers\Response;

abstract class SoapEndpoint extends Endpoint
{

	/** @var array $data */
	protected $responseData;

	public function setResponse(Response $response): void
	{
		parent::setResponse($response);

		$this->responseData = [
			'result' => $this->getResponse()->getWrappedObject()->methodResult,
		];
	}

	public function getResponseData(): array
	{
		return $this->responseData;
	}
}
