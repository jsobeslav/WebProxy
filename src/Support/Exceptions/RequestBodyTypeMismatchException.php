<?php declare(strict_types = 1);

namespace WebProxy\Support\Exceptions;

use Throwable;

class RequestBodyTypeMismatchException extends \Exception
{

	public function __construct(
		string $message = "Request body type is invalid",
		int $code = 0,
		Throwable $previous = null
	) {
		parent::__construct($message, $code, $previous);
	}
}
