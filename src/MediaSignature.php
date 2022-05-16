<?php

namespace KevAc\WmsPanel\PpvParser;

class MediaSignature
{
	public static function createForUrl(string $streamUrl, string $key, string $id, int $expiresInMinutes = 60, ?string $limitToIp = null): string
	{
		$dateTimeString = gmdate("n/j/Y g:i:s A");

		$hash = "";
		if(null !== $limitToIp) { $hash .= $limitToIp; }
		$hash .= $id . $key . $dateTimeString . $expiresInMinutes;

		$hash = base64_encode(md5($hash, true));

		$urlParameters = "server_time={$dateTimeString}&hash_value={$hash}&validminutes={$expiresInMinutes}&id={$id}";
		if(null !== $limitToIp) { $urlParameters .= "&checkip=true"; }
		$urlParameters = base64_encode($urlParameters);

		return $streamUrl . "?wmsAuthSign={$urlParameters}";
	}
}