<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use WebProxy\Support\Wrappers\Response;

abstract class RestResource extends HttpEndpoint
{

	/** @var array $data */
	protected $responseData;

	/**
	 * Save response and array representation of JSON body.
	 *
	 * @param Response $response
	 *
	 * @return void
	 */
	public function setResponse(Response $response): void
	{
		parent::setResponse($response);

		$this->responseData = json_decode($this->getResponse()->getBody(), true);
	}

	/**
	 * Return parsed JSON data.
	 *
	 * @return array Array representation of JSON response.
	 */
	public function getResponseData(): array
	{
		return $this->responseData;
	}
}
