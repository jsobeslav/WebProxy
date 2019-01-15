<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use WebProxy\Services\ServiceFactory;
use WebProxy\Services\ServiceInterface;
use WebProxy\Support\Wrappers\Response;

abstract class Endpoint implements EndpointInterface
{

	/** @var Response Wrapped Response. */
	protected $response;

	/**
	 * @return ServiceInterface
	 *
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 */
	public function getService(): ServiceInterface
	{
		return ServiceFactory::getService($this->getServiceClass());
	}

	/**
	 * Pass Response object to instance of the endpoint.
	 *
	 * @param Response $response
	 *
	 * @return void;
	 */
	public function setResponse(Response $response): void
	{
		$this->response = $response;
	}

	/**
	 * Return Response object.
	 *
	 * @return Response
	 */
	public function getResponse(): Response
	{
		return $this->response;
	}
}
