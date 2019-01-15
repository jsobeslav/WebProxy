<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use WebProxy\Services\ServiceFactory;
use WebProxy\Services\ServiceInterface;
use WebProxy\Support\Wrappers\Response;

abstract class Endpoint implements EndpointInterface
{

	/** @var Response Wrapped Response */
	protected $response;

	/** @var string $queryString */
	protected $queryString;

	public function __construct(string $queryString = '')
	{
		$this->queryString = trim_slashes($queryString);
	}

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
	 * @return string
	 *
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 */
	public function getServiceUri(): string
	{
		return $this->getService()
					->getUri();
	}

	public function getQueryString(): string
	{
		return $this->queryString;
	}

	public function setResponse(Response $response): void
	{
		$this->response = $response;
	}

	public function getResponse(): Response
	{
		return $this->response;
	}
}
