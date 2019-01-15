<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\LocalServer\SoapService;

use WebProxy\Services\SoapService;

class LocalSoapService extends SoapService
{

	public function getUri(): string
	{
		return 'http://projects/WebProxy/demo/server/soap_service/?wsdl';
	}
}