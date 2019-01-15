<?php declare(strict_types = 1);

namespace WebProxy\Services;

use WebProxy\Support\Exceptions\UnknownService;

/**
 * Class ServiceFactory
 *
 * Service singleton factory. Keeps instances of services so that they do not need to be instantiated for each request
 * upon one of their endpoints.
 */
class ServiceFactory
{

	/** @var array $services Array of currently existing service singletons. */
	private static $services = [];

	/**
	 * @param string $serviceClass
	 *
	 * @return ServiceInterface
	 *
	 * @throws UnknownService
	 */
	public static function getService(string $serviceClass): ServiceInterface
	{
		if (! class_exists($serviceClass)) {
			throw new UnknownService();
		}
		// @todo check if class is a service
		if (static::$services[$serviceClass] ?? null === null) {
			static::$services[$serviceClass] = new $serviceClass();
		}

		return static::$services[$serviceClass];
	}

	/**
	 * ClientFactory constructor set to private access so that the object cannot really be instantiated.
	 */
	private function __construct()
	{
	}
}
