<?php

namespace KevAc\WmsPanel\PpvParser\Exception;

use Throwable;

class SignatureValidationException extends \Exception
{
	public function __construct($message, $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function __toString()
	{
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}