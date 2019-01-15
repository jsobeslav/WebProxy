<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use WebProxy\Services\ServiceInterface;
use WebProxy\Support\Wrappers\Response;

interface EndpointInterface
{

	public function getServiceClass(): string;

	public function getService(): ServiceInterface;


	public function getServiceUri(): string;

	public function getRequestName(): string;

	public function getQueryString(): string;


	public function setResponse(Response $response): void;

	public function getResponse(): Response;
}
