<?php

namespace KevAc\WmsPanel\PpvParser;

class PublishSignature
{
	public static function createForUrl(string $publishApplicationUrl, string $streamName, string $id, string $key, ?string $limitToIp = null): string
	{
		$hash = $id . $streamName . $key;
		if(null !== $limitToIp) { $hash .= $limitToIp; }

		$hash = base64_encode(md5($hash, true));

		$urlParameters = "id={$id}&sign={$hash}";
		if(null !== $limitToIp) { $urlParameters .= "&ip={$limitToIp}"; }
		$urlParameters = base64_encode($urlParameters);

		return $publishApplicationUrl . "?publishsign=" . $urlParameters;
	}
}