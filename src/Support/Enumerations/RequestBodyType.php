<?php declare(strict_types = 1);

namespace WebProxy\Support\Enumerations;

class RequestBodyType extends \Consistence\Enum\Enum
{

	public const JSON        = 'json';
	public const FORM_PARAMS = 'form_params';
	public const MULTIPART   = 'multipart';
}
