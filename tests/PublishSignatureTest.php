<?php

use KevAc\WmsPanel\PpvParser\PublishSignature;
use PHPUnit\Framework\TestCase;

final class PublishSignatureTest extends TestCase
{
	public function testPublishSignatureCanBeCreatedWithoutIp(): void
	{
		$result = PublishSignature::createForUrl("https://yourmediaserver.com/ingest", "stream", "1005", "waidoengii6Nohr4EecohdeNeu2ohc");

		$this->assertIsString($result);
	}

	public function testPublishSignatureCanBeCreatedWithIp(): void
	{
		$result = PublishSignature::createForUrl("https://yourmediaserver.com/ingest", "stream", "1005", "waidoengii6Nohr4EecohdeNeu2ohc", "127.0.0.1");

		$this->assertIsString($result);
	}

}