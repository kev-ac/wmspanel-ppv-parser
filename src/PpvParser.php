<?php

namespace KevAc\WmsPanel\PpvParser;

use Couchbase\View;
use KevAc\WmsPanel\PpvParser\Entity\Application;
use KevAc\WmsPanel\PpvParser\Entity\Stream;
use KevAc\WmsPanel\PpvParser\Entity\VHost;
use KevAc\WmsPanel\PpvParser\Entity\Player;
use KevAc\WmsPanel\PpvParser\Exception\ParserException;
use KevAc\WmsPanel\PpvParser\Exception\SignatureValidationException;


/**
 * This is the main class.
 * Use parse() to receive structured data back.
 */
class PpvParser
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
		$data = $this->preparePayload($payload);

		/* Do sanity checks and possibly validate signature */
		RequestChecker::check($data, $this->token, $this->throwOnInvalidSignature);

		$parsedData = [];

		/* If there are no connected players the PayPerViewInfo object is empty. */
		if(!property_exists($data->PayPerViewInfo, "VHost"))
		{
			return $parsedData;
		}

		foreach($data->PayPerViewInfo->VHost as $vHost)
		{
			$vh = new VHost();
			$vh->setName($vHost->name);

			foreach($vHost->Application as $application)
			{
				$app = new Application();
				$app->setName($application->name);

				if(is_array($application->Instance) && sizeof($application->Instance) > 0)
				{
					foreach($application->Instance[0]->Stream as $stream)
					{
						$s = new Stream();
						$s->setName($stream->name);

						foreach($stream->Player as $player)
						{
							$p = new Player();
							$p->setId($player->id);
							$p->setIp($player->ip);
							$p->setSessionId($player->sessionid);
							$p->setDelta($player->delta);
							$p->setBytesSent($player->bytes_sent);

							$p->setStreamName($stream->name);

							foreach($player->user_agents as $userAgent)
							{
								$p->addUserAgent($userAgent);
							}

							$s->addPlayer($p);
							$app->addPlayer($p);
						}

						$app->addStream($s);
					}
				}

				$vh->addApplication($app);
			}

			$parsedData[] = $vh;
		}

		return $parsedData;
	}

	/**
	 * This function can be used to generate the "Solution" parameter, used for mutual authorization in WMSPanel.
	 *
	 * @param $payload
	 *
	 * @return string
	 * @throws ParserException
	 * @throws SignatureValidationException
	 */
	public function generateSolution($payload): string
	{
		/* We accept string data or an already parsed json payload */
		$data = $this->preparePayload($payload);

		/* Do sanity checks and possibly validate signature */
		RequestChecker::check($data, $this->token, $this->throwOnInvalidSignature);

		return SignatureValidator::generateSolution($data->Puzzle, $this->token);
	}

	/**
	 * @throws ParserException
	 */
	private function preparePayload($payload): object
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