<?php declare(strict_types = 1);

namespace WebProxy\Support\Wrappers;

/**
 * Class Response
 *
 * Response wrapper
 */
class Response
{

	/** @var mixed $wrappedObject Object representation of the response from client guzzle response, or wrapped nusoap_client response. */
	protected $wrappedObject;

	/** @var string $body String representation of body of the response. */
	protected $body;

	/**
	 * Response constructor.
	 *
	 * @param mixed  $wrappedObject Object returned from client.
	 * @param string $body          String representation of response from server.
	 */
	public function __construct($wrappedObject, string $body)
	{
		$this->wrappedObject = $wrappedObject;
		$this->body          = $body;
	}

	/**
	 * @return mixed Object returned from client. See self::$wrappedObject.
	 */
	public function getWrappedObject()
	{
		return $this->wrappedObject;
	}

	/**
	 * @return string String representation of response from server.
	 */
	public function getBody(): string
	{
		return $this->body;
	}
}
