<?php declare(strict_types = 1);

namespace WebProxy\Clients;

use WebProxy\Services\HttpService;
use WebProxy\Services\ServiceInterface;
use WebProxy\Services\SoapService;
use WebProxy\Support\Exceptions\UnknownClient;
use WebProxy\Support\Exceptions\UnknownService;

/**
 * Class ClientFactory
 *
 * Client singleton factory. Keeps instances of clients so that they do not need to be instantiated for each
 * endpoint/request.
 */
class ClientFactory
{

	/** @var array $clients Array of currently instantiated client singletons. */
	private static $clients = [];

	/**
	 * Get client by its own class.
	 *
	 * @param string $clientClass
	 *
	 * @return ClientInterface
	 * @throws UnknownClient
	 */
	public static function getClient(string $clientClass): ClientInterface
	{
		if (! class_exists($clientClass)) {
			throw new UnknownClient();
		}
		// @todo check if class is a client
		if (static::$clients[$clientClass] ?? null === null) {
			static::$clients[$clientClass] = new $clientClass();
		}

		return static::$clients[$clientClass];
	}

	/**
	 * Get client suitable for given service.
	 *
	 * @param ServiceInterface $service
	 *
	 * @return ClientInterface
	 *
	 * @throws UnknownClient
	 * @throws UnknownService
	 */
	public static function getClientForService(ServiceInterface $service): ClientInterface
	{
		switch (true) {
			case $service instanceof HttpService:
				return static::getClient(HttpClient::class);
			case $service instanceof SoapService:
				return static::getClient(SoapClient::class);
			default:
				throw new UnknownService();
		}
	}

	/**
	 * ClientFactory constructor set to private access so that the object cannot really be instantiated.
	 */
	private function __construct()
	{
	}
}
