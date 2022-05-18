<?php

use KevAc\WmsPanel\PpvParser\Entity\Publisher;
use KevAc\WmsPanel\PpvParser\Exception\ParserException;
use KevAc\WmsPanel\PpvParser\Exception\UnsupportedProtocolException;
use KevAc\WmsPanel\PpvParser\PublishControl;
use PHPUnit\Framework\TestCase;

final class PublishControlTest extends TestCase
{
	public function testPublishSignatureCanBeCreatedWithIp(): void
	{
		$result = PublishControl::createSignedUrl("rtmp://yourmediaserver.com","live", "stream", "1005", "waidoengii6Nohr4EecohdeNeu2ohc", "127.0.0.1");

		$this->assertIsString($result);
	}

	public function testRtmpPublishSignatureCanBeCreated(): void
	{
		$result = PublishControl::createSignedUrl("rtmp://yourmediaserver.com","live", "stream", "1005", "waidoengii6Nohr4EecohdeNeu2ohc");

		$this->assertIsString($result);
	}

	public function testRtspPublishSignatureCanBeCreated(): void
	{
		$result = PublishControl::createSignedUrl("rtsp://yourmediaserver.com","live", "stream", "1005", "waidoengii6Nohr4EecohdeNeu2ohc");

		$this->assertIsString($result);
	}

	public function testWebRtcPublishSignatureCanBeCreated(): void
	{
		$result = PublishControl::createSignedUrl("https://yourmediaserver.com","live", "stream", "1005", "waidoengii6Nohr4EecohdeNeu2ohc");

		$this->assertIsString($result);
	}

	public function testSrtPublishSignatureCanBeCreated(): void
	{
		$result = PublishControl::createSignedUrl("srt://yourmediaserver.com","live", "stream", "1005", "waidoengii6Nohr4EecohdeNeu2ohc");

		$this->assertIsString($result);
	}

	public function testUnknownProtocolPublishSignatureCannotBeCreated(): void
	{
		$this->expectException( UnsupportedProtocolException::class);
		$result = PublishControl::createSignedUrl("myproto://yourmediaserver.com","live", "stream", "1005", "waidoengii6Nohr4EecohdeNeu2ohc");
	}

	public function testValidPublishAuthRequestCanBeParsed(): void
	{
		$result = PublishControl::parseAuthRequest('{"PublishAuthRequest":[{"seq":"1", "id":"ID_1", "ip":"192.168.1.1","stream":"publish/stream"},{"seq":"2", "id":"ID_2","ip":"192.168.1.2","stream":"publish/stream2"}]}');

		$this->assertIsArray($result);
		$this->assertCount( 2, $result );
	}

	public function testInvalidPublishAuthRequestCannotBeParsed(): void
	{
		$this->expectException( ParserException::class);
		$result = PublishControl::parseAuthRequest('{"PublisherInvalid":[{"seq":"1", "id":"ID_1", "ip":"192.168.1.1","stream":"publish/stream"},{"seq":"2", "id":"ID_2","ip":"192.168.1.2","stream":"publish/stream2"}]}');
	}

	public function testAuthResponseWithArrayOfPublishersPasses(): void
	{
		$allowedPublisher = new Publisher();
		$allowedPublisher->setSequence(5);
		$allowedPublishers = [$allowedPublisher];
		$deniedPublisher = new Publisher();
		$deniedPublisher->setSequence(1);
		$deniedPublishers = [$deniedPublisher];

		$result = PublishControl::createAuthResponse($allowedPublishers, $deniedPublishers);

		$this->assertIsArray($result);
	}

	public function testAuthResponseWithArrayOfStringsPasses(): void
	{
		$allowedPublishers = ["5"];
		$deniedPublishers = ["1"];

		$result = PublishControl::createAuthResponse($allowedPublishers, $deniedPublishers);
		$this->assertIsArray($result);
	}

	public function testAuthResponseWithEmptyArraysPasses(): void
	{
		$result = PublishControl::createAuthResponse([], []);
		$this->assertIsArray($result);
	}
}