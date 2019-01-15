<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\FakeNameGenerator;

use WebProxy\Endpoints\Webpage;

class HomeWebpage extends Webpage
{

	public function getServiceClass(): string
	{
		return FakeNameGeneratorWebsite::class;
	}

	public function getRequestName(): string
	{
		return '/';
	}

	public function getName()
	{
		return $this->getCrawler()->filter('.address h3')->text();
	}
}
