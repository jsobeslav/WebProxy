<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\LocalServer\HttpService;

use WebProxy\Services\Website;

class LocalWebsite extends Website
{

	public function getUri(): string
	{
		return 'http://projects/WebProxy/demo/server/http_service/';
	}
}