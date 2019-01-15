<?php declare(strict_types = 1);

namespace WebProxyDemo\Client\FakeNameGenerator;

use WebProxy\Services\Website;

class FakeNameGeneratorWebsite extends Website
{

	public function getUri(): string
	{
		return 'http://fakenamegenerator.com/';
	}
}
