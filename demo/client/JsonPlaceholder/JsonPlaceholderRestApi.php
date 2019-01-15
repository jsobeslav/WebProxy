<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\JsonPlaceholder;

use WebProxy\Services\RestApi;

class JsonPlaceholderRestApi extends RestApi
{

	public function getUri(): string
	{
		return 'https://jsonplaceholder.typicode.com';
	}
}