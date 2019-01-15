<?php declare(strict_types = 1);

namespace WebProxy\Clients;

use WebProxy\Endpoints\EndpointInterface;
use WebProxy\Support\Wrappers\Request;
use WebProxy\Support\Wrappers\Response;

interface ClientInterface
{

	public function request(EndpointInterface $endpoint, Request $request): Response;
}
