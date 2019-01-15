<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use WebProxy\Support\Enumerations\Method;

abstract class HttpEndpoint extends Endpoint
{

	/** @var array $supportedMethods */
	protected $supportedMethods = [
		Method::GET,
	];

	/**
	 * Get if the endpoint supports the method
	 *
	 * @param string $method
	 *
	 * @return boolean
	 */
	public function supportsMethod(string $method): bool
	{
		return in_array(
			$method,
			$this->supportedMethods,
			true
		);
	}
}
