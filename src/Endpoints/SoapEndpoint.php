<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use WebProxy\Support\Wrappers\Response;

abstract class SoapEndpoint extends Endpoint
{

	/** @var array $data */
	protected $responseData;

	/**
	 * Save the response and results from remote method.
	 *
	 * @param Response $response
	 *
	 * @return void
	 */
	public function setResponse(Response $response): void
	{
		parent::setResponse($response);

		$this->responseData = [
			'result' => $this->getResponse()->getWrappedObject()->methodResult,
		];
	}

	/**
	 * @return array
	 */
	public function getResponseData(): array
	{
		return $this->responseData;
	}

	/**
	 * Return endpoint request name.
	 *
	 * @return string
	 */
	public function getFullRequestName(): string
	{
		return $this->getRequestName();
	}
}
