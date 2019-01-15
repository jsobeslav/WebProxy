<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\LocalServer\SoapService;

use WebProxy\Endpoints\SoapEndpoint;

class ListProductsSoapEndpoint extends SoapEndpoint
{

	public function getServiceClass(): string
	{
		return LocalSoapService::class;
	}

	public function getRequestName(): string
	{
		return 'listProducts';
	}

	public function getList(): array
	{
		return $this->getResponseData()['result'];
	}
}
