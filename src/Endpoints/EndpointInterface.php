<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use WebProxy\Services\ServiceInterface;
use WebProxy\Support\Wrappers\Response;

interface EndpointInterface
{

	/**
	 * Get class of service the endpoint is part of.
	 * Must be implemented in child classes.
	 *
	 * @return string Service class.
	 */
	public function getServiceClass(): string;

	/**
	 * Get instance of service the endpoint is part of.
	 *
	 * @return ServiceInterface Service instance.
	 */
	public function getService(): ServiceInterface;

	/**
	 * Get name of request that is performed by this endpoint **class**. For REST it's resource path; for SOAP it's
	 * method name. Must be implemented in child classes.
	 *
	 * @return string Request name.
	 */
	public function getRequestName(): string;

	/**
	 * Get full name of request that is performed by this endpoint's **instance**.
	 * It is possible to use this superclass method to modify request name returned from child class before it's sent
	 * to client.
	 *
	 * @see HttpEndpoint for example where this method is used to append query string to request name.
	 *
	 * @return string
	 */
	public function getFullRequestName(): string;

	/**
	 * Pass Response object to instance of the endpoint.
	 *
	 * @param Response $response
	 *
	 * @return void
	 */
	public function setResponse(Response $response): void;

	/**
	 * Return Response object.
	 *
	 * @return Response
	 */
	public function getResponse(): Response;
}
