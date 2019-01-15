<?php declare(strict_types = 1);

namespace WebProxy\Support\Enumerations;

use Consistence\Enum\Enum;

class RequestBodyType extends Enum
{

	public const JSON        = 'json';
	public const FORM_PARAMS = 'form_params';
	public const MULTIPART   = 'multipart';
}
