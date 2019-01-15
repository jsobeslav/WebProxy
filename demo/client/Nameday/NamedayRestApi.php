<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\Nameday;

use WebProxy\Services\RestApi;

class NamedayRestApi extends RestApi
{

	public function getUri(): string
	{
		return 'https://api.abalin.net';
	}
}