<?php declare(strict_types = 1);

namespace WebProxy\Services;

use nusoap_client;
use WebProxy\Support\Exceptions\NusoapConstructorErrorException;

abstract class SoapService extends Service
{

	/** @var nusoap_client $nusoap Each service keeps own client. */
	protected $nusoap;

	/**
	 * SoapService constructor.
	 *
	 * @throws NusoapConstructorErrorException
	 */
	public function __construct()
	{
		$this->nusoap = new nusoap_client($this->getUri(), true);

		if ($this->nusoap->getError()) {
			throw new NusoapConstructorErrorException();
		}
	}

	/**
	 * @return nusoap_client
	 */
	public function getNusoapClient(): nusoap_client
	{
		return $this->nusoap;
	}
}
