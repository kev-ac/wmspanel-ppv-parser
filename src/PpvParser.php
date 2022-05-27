<?php

namespace KevAc\WmsPanel\PpvParser;

use KevAc\WmsPanel\PpvParser\Entity\Application;
use KevAc\WmsPanel\PpvParser\Entity\Stream;
use KevAc\WmsPanel\PpvParser\Entity\VHost;
use KevAc\WmsPanel\PpvParser\Entity\Player;
use KevAc\WmsPanel\PpvParser\Exception\ParserException;
use KevAc\WmsPanel\PpvParser\Exception\SignatureValidationException;
use KevAc\WmsPanel\PpvParser\Helper\IncomingPayloadParser;


/**
 * This class is used to work with incoming PPV data
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
		$data = IncomingPayloadParser::parse($payload);

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
	 * This function can be used to generate a response to a PPV request from a media server.
	 * The "Solution" parameter will be appended if you specified a token in the constructor.
	 *
	 * @param array $deniedIds
	 * @param $payload
	 *
	 * @return array|\array[][]
	 * @throws ParserException
	 * @throws SignatureValidationException
	 */
	public function generateResponse(array $deniedIds, $payload = null): array
	{
		if(null !== $payload && null !== $this->token)
		{
			/* We accept string data or an already parsed json payload */
			$data = IncomingPayloadParser::parse($payload);
			/* Do sanity checks and possibly validate signature */
			RequestChecker::check($data, $this->token, $this->throwOnInvalidSignature);

			$solution = SignatureValidator::generateSolution($data->Puzzle, $this->token);

			/* Cast IDs to string (as per WMSPanel documentation) */
			foreach($deniedIds as &$deniedId)
			{
				$deniedId = strval($deniedId);
			}

			return [
				"DenyList" => [
					"ID" => $deniedIds
				],
				"Solution" => $solution
			];
		}

		return [
			"DenyList" => [
				"ID" => $deniedIds
			]
		];
	}
}