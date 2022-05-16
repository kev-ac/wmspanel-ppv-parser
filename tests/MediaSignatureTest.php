<?php

use KevAc\WmsPanel\PpvParser\MediaSignature;
use KevAc\WmsPanel\PpvParser\SignatureValidator;
use PHPUnit\Framework\TestCase;

final class MediaSignatureTest extends TestCase
{
	public function testMediaSignatureCanBeCreatedWithoutIp(): void
	{
		$result = MediaSignature::createForUrl("https://yourmediaserver.com/live/livestream/playlist.m3u8", "waidoengii6Nohr4EecohdeNeu2ohc", "1005", 20);

		$this->assertIsString($result);
	}

	public function testMediaSignatureCanBeCreatedWithIp(): void
	{
		$result = MediaSignature::createForUrl("https://yourmediaserver.com/live/livestream/playlist.m3u8", "waidoengii6Nohr4EecohdeNeu2ohc", "1005", 20, "127.0.0.1");

		$this->assertIsString($result);
	}

}