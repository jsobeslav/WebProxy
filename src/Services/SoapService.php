<?php declare(strict_types = 1);

namespace WebProxy\Services;

use SoapClient;

abstract class SoapService extends Service
{

	/** @var SoapClient $nativeClient Each service keeps own **native** SoapClient object. */
	protected $nativeClient;

	/**
	 * SoapService constructor.
	 */
	public function __construct()
	{
		$this->nativeClient = new SoapClient($this->getUri(), ['trace' => true, 'exceptions' => true]);
	}

	/**
	 * @return SoapClient
	 */
	public function getNativeClient(): SoapClient
	{
		return $this->nativeClient;
	}
}
