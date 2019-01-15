<?php declare(strict_types = 1);

namespace WebProxy\Support\Enumerations;

use Consistence\Enum\Enum;

class Method extends Enum
{

	public const GET    = 'GET';
	public const POST   = 'POST';
	public const PUT    = 'PUT';
	public const DELETE = 'DELETE';
	public const RPC    = 'RPC';
}
