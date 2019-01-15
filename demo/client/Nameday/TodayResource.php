<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\Nameday;

use WebProxy\Endpoints\RestResource;

class TodayResource extends RestResource
{

	public function getServiceClass(): string
	{
		return NamedayRestApi::class;
	}

	public function getRequestName(): string
	{
		return '/get/today';
	}

	public function getName()
	{
		return $this->getResponseData()['data']['name_cz'];
	}
}