<?php

namespace KevAc\WmsPanel\PpvParser\Helper;

use KevAc\WmsPanel\PpvParser\Exception\ParserException;

class IncomingPayloadParser
{
	/**
	 * @throws ParserException
	 */
	public static function parse($payload): object
	{
		if(is_string($payload))
		{
			try
			{
				$payload = json_decode( $payload, false, 512, JSON_THROW_ON_ERROR );
			}
			catch ( \JsonException $e )
			{
				throw new ParserException("Incoming payload cannot be parsed.");
			}
		}

		return $payload;
	}
}