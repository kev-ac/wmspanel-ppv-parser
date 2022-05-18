<?php

use KevAc\WmsPanel\PpvParser\Exception\ParserException;
use KevAc\WmsPanel\PpvParser\Helper\IncomingPayloadParser;
use PHPUnit\Framework\TestCase;

final class IncomingPayloadParserTest extends TestCase
{
	public function testJsonStringPayloadCanBeParsed(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103"}';

		$result = IncomingPayloadParser::parse($demoPayload);

		$this->assertIsObject($result);
	}

	public function testInvalidJsonStringPayloadCannotBeParsed(): void
	{
		$demoPayload = '\[{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103"}';

		$this->expectException(ParserException::class);
		$result = IncomingPayloadParser::parse($demoPayload);

	}


}