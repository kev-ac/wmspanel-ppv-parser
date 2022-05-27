<?php

use KevAc\WmsPanel\PpvParser\Exception\SignatureValidationException;
use KevAc\WmsPanel\PpvParser\PublishControlParser;
use PHPUnit\Framework\TestCase;

final class PublishControlParserTest extends TestCase
{
	public function testValidPublishPayloadWithValidSignatureCanBeParsed(): void
	{
		$demoPayload = '{"ID":"bb4fcfd2-a1fb-49b0-aed5-22bc572ea7cf","Puzzle":"94cc0c9b-080c-d435-c2b9-cca2528fa13e","Signature":"JOWZAjro7J6fgnot+i3efg==","publish":[{"stream":"live\/stream","protocol":"RTMP","time":1653642799116,"type":"push","remote_ip":"10.0.0.5"},{"stream":"live\/stream_1080p","protocol":"ENCODER","time":1653642799362}]}';

		$parser = new PublishControlParser("bc91b48588048871f4b898af9371ccc8", true);

		$result = $parser->parse($demoPayload);

		$this->assertIsArray($result['published']);
		$this->assertCount(2, $result['published']);
	}

	public function testValidUnpublishPayloadWithValidSignatureCanBeParsed(): void
	{
		$demoPayload = '{"ID":"bb4fcfd2-a1fb-49b0-aed5-22bc572ea7cf","Puzzle":"94cc0c9b-080c-d435-c2b9-cca2528fa13e","Signature":"JOWZAjro7J6fgnot+i3efg==","unpublish":[{"stream":"live\/stream","protocol":"RTMP","time":1653642799116,"type":"push","remote_ip":"10.0.0.5"},{"stream":"live\/stream_1080p","protocol":"ENCODER","time":1653642799362}]}';

		$parser = new PublishControlParser("bc91b48588048871f4b898af9371ccc8", true);

		$result = $parser->parse($demoPayload);

		$this->assertIsArray($result['unpublished']);
		$this->assertCount(2, $result['unpublished']);
	}

	public function testValidPayloadWithInvalidSignatureCannotBeParsed(): void
	{
		$demoPayload = '{"ID":"bb4fcfd2-a1fb-49b0-aed5-22bc572ea7cf","Puzzle":"94cc0c9b-080c-d435-c2b9-cca2528fa13e","Signature":"JOWZAjro7J6fgnot+i3efg==","publish":[{"stream":"live\/stream","protocol":"RTMP","time":1653642799116,"type":"push","remote_ip":"10.0.0.5"},{"stream":"live\/stream_1080p","protocol":"ENCODER","time":1653642799362}]}';

		$parser = new PublishControlParser("invalid", true);

		$this->expectException(SignatureValidationException::class);
		$result = $parser->parse($demoPayload);

	}

}