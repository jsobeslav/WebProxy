<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use WebProxy\Support\Wrappers\Response;

abstract class RestResource extends HttpEndpoint
{

	/** @var array $data */
	protected $responseData;

	public function setResponse(Response $response): void
	{
		parent::setResponse($response);

		$this->responseData = json_decode($this->getResponse()->getBody(), true);
	}

	public function getResponseData(): array
	{
		return $this->responseData;
	}
}
