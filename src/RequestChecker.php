<?php

namespace KevAc\WmsPanel\PpvParser;

use KevAc\WmsPanel\PpvParser\Exception\SignatureValidationException;

class RequestChecker
{
	/**
	 * @throws SignatureValidationException
	 */
	public static function check(object $data, ?string $token, bool $throwOnInvalidSignature)
	{
		if($throwOnInvalidSignature)
		{
			if(null === $token)
			{
				throw new SignatureValidationException("No token specified. Cannot validate signature.");
			}

			/* Do some sanity checks */
			if(!property_exists($data, "ID") || !property_exists($data, "Signature") || !property_exists($data, "Puzzle"))
			{
				throw new SignatureValidationException("Given payload seems to be invalid.");
			}

			if(!SignatureValidator::validate($data->Signature, $data->ID, $data->Puzzle, $token))
			{
				throw new SignatureValidationException("Signature does not match.");
			}
		}
	}
}