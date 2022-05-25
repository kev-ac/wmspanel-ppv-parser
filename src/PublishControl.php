<?php

namespace KevAc\WmsPanel\PpvParser;

use KevAc\WmsPanel\PpvParser\Entity\Publisher;
use KevAc\WmsPanel\PpvParser\Exception\ParserException;
use KevAc\WmsPanel\PpvParser\Exception\UnsupportedProtocolException;
use KevAc\WmsPanel\PpvParser\Helper\IncomingPayloadParser;

class PublishControl
{
	/**
	 * @throws ParserException
	 */
	public static function parseAuthRequest(string $payload): array
	{
		$data = IncomingPayloadParser::parse($payload);

		if(!property_exists($data, "PublishAuthRequest"))
		{
			throw new ParserException("Not a valid publish auth request.");
		}

		$publisherList = [];
		foreach($data->PublishAuthRequest as $pb)
		{
			$publisher = new Publisher();
			$publisher->setId($pb->id);
			$publisher->setSequence($pb->seq);
			$publisher->setIp($pb->ip);
			$publisher->setStream($pb->stream);

			$publisherList[] = $publisher;
		}

		return $publisherList;
	}

	/**
	 * This function creates the auth response to an incoming auth request by a media server.
	 * You can either pass an array of Publisher entites or an array of sequence strings.
	 *
	 * @param Publisher[]|string[] $allowedPublishers
	 * @param Publisher[]|string[] $deniedPublishers
	 *
	 * @return array|array[]
	 */
	public static function createAuthResponse(array $allowedPublishers, array $deniedPublishers): array
	{
		$response = ['PublishAuthResponse' => []];

		foreach($allowedPublishers as $ap)
		{
			$response['PublishAuthResponse'][] = [
				"seq" => $ap instanceof Publisher ? $ap->getSequence() : $ap,
				"status" => "success"
			];
		}
		foreach($deniedPublishers as $dp)
		{
			$response['PublishAuthResponse'][] = [
				"seq" => $dp instanceof Publisher ? $dp->getSequence() : $dp,
				"status" => "fail"
			];
		}

		return $response;
	}

	/**
	 * This function generates a signed publishing URL used by WMSPanels Publish Control feature.
	 *
	 * @throws ParserException
	 * @throws UnsupportedProtocolException
	 */
	public static function createSignedUrl(string $serverUrl, string $applicationName, string $streamName, string $id, string $key, ?string $limitToIp = null): string
	{
		$hash = $id . "/{$applicationName}/$streamName" . $key;
		if(null !== $limitToIp) { $hash .= $limitToIp; }

		$hash = base64_encode(md5($hash, true));

		$urlParameters = "id={$id}&sign={$hash}";
		if(null !== $limitToIp) { $urlParameters .= "&ip={$limitToIp}"; }
		$urlParameters = base64_encode($urlParameters);

		$parsedServerUrl = parse_url($serverUrl);
		if(!array_key_exists("scheme", $parsedServerUrl))
		{
			throw new ParserException("Application url cannot be parsed.");
		}

		switch($parsedServerUrl['scheme'])
		{
			case "rtmp":
			case "rtmps": // RTMP over TLS
				return "{$serverUrl}/{$applicationName}?publishsign={$urlParameters}/{$streamName}";
			case "rtsp":
			case "https": // webrtc
				return "{$serverUrl}/{$applicationName}/{$streamName}?publishsign={$urlParameters}";
			case "srt":
				return "{$serverUrl}?streamid=/{$applicationName}/{$streamName}?publishsign={$urlParameters}";
			default:
				throw new UnsupportedProtocolException("\"{$parsedServerUrl['scheme']}\" is an unsupported protocol.");
		}
	}
}