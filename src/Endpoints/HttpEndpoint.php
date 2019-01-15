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

	/** @var string $queryString */
	protected $queryString;

	/**
	 * Endpoint constructor.
	 *
	 * @param string $queryString
	 */
	public function __construct(string $queryString = '')
	{
		$this->queryString = trim_slashes($queryString);
	}

	/**
	 * @return string
	 */
	public function getQueryString(): string
	{
		return $this->queryString;
	}

	/**
	 * Return endpoint resource path with query string appended.
	 *
	 * @return string
	 */
	public function getFullRequestName(): string
	{
		return trim_slashes($this->getRequestName())
			   . prepend_slash($this->getQueryString());
	}
}
