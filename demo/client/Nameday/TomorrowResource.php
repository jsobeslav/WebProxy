<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\Nameday;

use WebProxy\Endpoints\RestResource;

class TomorrowResource extends RestResource
{

	public function getServiceClass(): string
	{
		return NamedayRestApi::class;
	}

	public function getRequestName(): string
	{
		return '/get/tomorrow';
	}

	public function getName()
	{
		return $this->responseData['data']['name_cz'];
	}
}