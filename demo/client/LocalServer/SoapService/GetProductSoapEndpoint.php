<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\LocalServer\SoapService;

use WebProxy\Endpoints\SoapEndpoint;

class GetProductSoapEndpoint extends SoapEndpoint
{

	public function getServiceClass(): string
	{
		return LocalSoapService::class;
	}

	public function getRequestName(): string
	{
		return 'getProduct';
	}

	public function getTitle(): string
	{
		return $this->getResponseData()['result'];
	}
}
