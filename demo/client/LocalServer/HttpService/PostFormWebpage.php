<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\LocalServer\HttpService;

use WebProxy\Endpoints\Webpage;
use WebProxy\Support\Enumerations\Method;

class PostFormWebpage extends Webpage
{

	public function getServiceClass(): string
	{
		return LocalWebsite::class;
	}

	public function getRequestName(): string
	{
		return '/';
	}

	protected $supportedMethods = [
		Method::POST,
	];

	public function getResponseMessage()
	{
		return $this->crawler->filter('.result')->text();
	}

	public function getHeadersMessage()
	{
		return trim(
			$this->crawler->filter('.headers')->text()
		);
	}

}