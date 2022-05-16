<?php

namespace KevAc\WmsPanel\PpvParser;

class SignatureValidator
{
	/**
	 * This function validates the given signature against the to be calculated signature.
	 *
	 * @param string $signature
	 * @param string $id
	 * @param string $puzzle
	 * @param string $token
	 *
	 * @return bool
	 */
	public static function validate(string $signature, string $id, string $puzzle, string $token): bool
	{
		return $signature === self::generateSignature($id, $puzzle, $token);
	}

	/**
	 * This function generates a signature and returns it.
	 *
	 * @param string $id
	 * @param string $puzzle
	 * @param string $token
	 *
	 * @return string
	 */
	public static function generateSignature(string $id, string $puzzle, string $token): string
	{
		return base64_encode(md5($id . $puzzle . $token, true));
	}

	/**
	 * This function generates the "solution" for WMSPanel mutual authorization and returns it.
	 *
	 * @param string $puzzle
	 * @param string $token
	 *
	 * @return string
	 */
	public static function generateSolution(string $puzzle, string $token): string
	{
		return base64_encode(md5($puzzle . $token, true));
	}
}