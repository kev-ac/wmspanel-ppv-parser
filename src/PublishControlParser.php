<?php

namespace KevAc\WmsPanel\PpvParser;

use KevAc\WmsPanel\PpvParser\Entity\PublishedStream;
use KevAc\WmsPanel\PpvParser\Exception\ParserException;
use KevAc\WmsPanel\PpvParser\Exception\SignatureValidationException;
use KevAc\WmsPanel\PpvParser\Helper\IncomingPayloadParser;


/**
 * This class is used to work with incoming publishcontrol data
 * Use parse() to receive structured data back.
 */
class PublishControlParser
{
	private ?string $token;
	private bool $throwOnInvalidSignature;

	public function __construct(?string $token = null, bool $throwOnInvalidSignature = true)
	{
		$this->token = $token;
		$this->throwOnInvalidSignature = $throwOnInvalidSignature;
	}

	/**
	 *
	 * @param string|object $payload
	 *
	 * @return array
	 * @throws ParserException
	 * @throws SignatureValidationException
	 */
	public function parse($payload): array
	{
		/* We accept string data or an already parsed json payload */
		$data = IncomingPayloadParser::parse($payload);

		/* Do sanity checks and possibly validate signature */
		RequestChecker::check($data, $this->token, $this->throwOnInvalidSignature);

		$parsedData = ["published" => [], "unpublished" => []];

		if(property_exists($data, "publish"))
		{
			foreach($data->publish as $publishedStream)
			{
				$ps = new PublishedStream();

				[$app, $stream] = explode("/",$publishedStream->stream);
				$ps->setApplication($app);
				$ps->setStream($stream);

				if(property_exists($publishedStream, "protocol")) {
					$ps->setProtocol( $publishedStream->protocol );
				}
				if(property_exists($publishedStream, "time")) {
					$ps->setTime( new \DateTime("@".intval($publishedStream->time / 1000)) );
				}
				if(property_exists($publishedStream, "type")) {
					$ps->setType( $publishedStream->type );
				}
				if(property_exists($publishedStream, "remote_ip")) {
					$ps->setRemoteIp( $publishedStream->remote_ip );
				}

				$parsedData["published"][] = $ps;
			}
		}

		if(property_exists($data, "unpublish"))
		{
			foreach($data->unpublish as $publishedStream)
			{
				$ps = new PublishedStream();

				[$app, $stream] = explode("/",$publishedStream->stream);
				$ps->setApplication($app);
				$ps->setStream($stream);

				if(property_exists($publishedStream, "protocol")) {
					$ps->setProtocol( $publishedStream->protocol );
				}
				if(property_exists($publishedStream, "time")) {
					$ps->setTime( new \DateTime("@".intval($publishedStream->time / 1000)) );
				}
				if(property_exists($publishedStream, "type")) {
					$ps->setType( $publishedStream->type );
				}
				if(property_exists($publishedStream, "remote_ip")) {
					$ps->setRemoteIp( $publishedStream->remote_ip );
				}

				$parsedData["unpublished"][] = $ps;
			}
		}

		return $parsedData;
	}
}