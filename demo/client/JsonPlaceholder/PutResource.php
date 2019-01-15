<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\JsonPlaceholder;

use WebProxy\Endpoints\RestEndpoint;
use WebProxy\Endpoints\RestResource;
use WebProxy\Support\Enumerations\Method;

class PutResource extends RestResource
{

	public function getServiceClass(): string
	{
		return JsonPlaceholderRestApi::class;
	}

	public function getRequestName(): string
	{
		return '/posts';
	}

	protected $supportedMethods = [
		Method::PUT,
	];

	public function getNewTitle()
	{
		return $this->responseData['title'];
	}
}