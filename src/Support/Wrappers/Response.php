<?php declare(strict_types = 1);

namespace WebProxy\Support\Wrappers;

/**
 * Class Response
 *
 * Response wrapper
 *
 * @package WebProxy\Support\Wrappers
 */
class Response
{

	/** @var $wrappedObject Object representation of the response from client. */
	protected $wrappedObject;

	/** @var string $body String representation of body */
	protected $body;

	/**
	 * Response constructor.
	 *
	 * @param        $wrappedObject
	 * @param string        $body
	 */
	public function __construct($wrappedObject, string $body)
	{
		$this->wrappedObject = $wrappedObject;
		$this->body          = $body;
	}

	public function getWrappedObject()
	{
		return $this->wrappedObject;
	}

	public function getBody(): string
	{
		return $this->body;
	}
}
