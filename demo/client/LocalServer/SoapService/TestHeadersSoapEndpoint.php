<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\LocalServer\SoapService;

use WebProxy\Endpoints\SoapEndpoint;

class TestHeadersSoapEndpoint extends SoapEndpoint
{

	public function getServiceClass(): string
	{
		return LocalSoapService::class;
	}

	public function getRequestName(): string
	{
		return 'getHeaders';
	}

	public function getHeadersMessage(): string
	{
		return $this->getResponseData()['result'];
	}
}
