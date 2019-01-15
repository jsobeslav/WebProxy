<?php declare(strict_types = 1);

namespace WebProxy\Services;

interface ServiceInterface
{

	/**
	 * Get Base URI for the service. For HttpServices it's root URI; for SoapServices it's URI of WSDL.
	 * Must be implemented in child classes.
	 *
	 * @return string Service URI.
	 */
	public function getUri(): string;
}
