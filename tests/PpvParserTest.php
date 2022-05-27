<?php

use KevAc\WmsPanel\PpvParser\Exception\ParserException;
use KevAc\WmsPanel\PpvParser\Exception\SignatureValidationException;
use PHPUnit\Framework\TestCase;
use KevAc\WmsPanel\PpvParser\PpvParser;

final class PpvParserTest extends TestCase
{
	public function testValidPayloadWithValidSignatureCanBeParsed(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103","PayPerViewInfo":{"VHost":[{"name":"your-edgeserver.yourdomain.com","Application":[{"name":"live","Instance":[{"name":"_definst_","Stream":[{"name":"livestream","Player":[{"id":"500","ip":"127.0.0.1","sessionid":"1001","delta":7000,"bytes_sent":13981102,"user_agents":["Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/15.4 Safari\/605.1.15"]}]}]}]},{"name":"vod_abr","Instance":[{"name":"_definst_","Stream":[{"name":"vodfile/smil:vod.smil","Player":[{"id":"599","ip":"127.0.0.1","sessionid":"1010","delta":15000,"bytes_sent":4309566,"user_agents":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/101.0.4951.64 Safari\/537.36 Edg\/101.0.1210.47"]}]}]}]}]}]}}';

		$parser = new PpvParser("bc91b48588048871f4b898af9371ccc8", true);

		$result = $parser->parse($demoPayload);

		$this->assertIsArray($result);
		$this->assertEquals(1, sizeof($result));
	}


	public function testEmptyPayloadWithValidSignatureCanBeParsed(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103","PayPerViewInfo":{}}';

		$parser = new PpvParser("bc91b48588048871f4b898af9371ccc8", true);

		$result = $parser->parse($demoPayload);

		$this->assertIsArray($result);
		$this->assertEquals(0, sizeof($result));
	}

	public function testValidPayloadWithoutClientIpWithValidSignatureCanBeParsed(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103","PayPerViewInfo":{"VHost":[{"name":"your-edgeserver.yourdomain.com","Application":[{"name":"live","Instance":[{"name":"_definst_","Stream":[{"name":"livestream","Player":[{"id":"500","sessionid":"1001","delta":7000,"bytes_sent":13981102,"user_agents":["Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/15.4 Safari\/605.1.15"]}]}]}]},{"name":"vod_abr","Instance":[{"name":"_definst_","Stream":[{"name":"vodfile/smil:vod.smil","Player":[{"id":"599","ip":"127.0.0.1","sessionid":"1010","delta":15000,"bytes_sent":4309566,"user_agents":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/101.0.4951.64 Safari\/537.36 Edg\/101.0.1210.47"]}]}]}]}]}]}}';

		$parser = new PpvParser("bc91b48588048871f4b898af9371ccc8", true);

		$result = $parser->parse($demoPayload);

		$this->assertIsArray($result);
		$this->assertEquals(1, sizeof($result));
	}

	public function testValidPayloadWithoutUserAgentWithValidSignatureCanBeParsed(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103","PayPerViewInfo":{"VHost":[{"name":"your-edgeserver.yourdomain.com","Application":[{"name":"live","Instance":[{"name":"_definst_","Stream":[{"name":"livestream","Player":[{"id":"500","ip":"127.0.0.1","sessionid":"1001","delta":7000,"bytes_sent":13981102}]}]}]},{"name":"vod_abr","Instance":[{"name":"_definst_","Stream":[{"name":"vodfile/smil:vod.smil","Player":[{"id":"599","ip":"127.0.0.1","sessionid":"1010","delta":15000,"bytes_sent":4309566,"user_agents":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/101.0.4951.64 Safari\/537.36 Edg\/101.0.1210.47"]}]}]}]}]}]}}';

		$parser = new PpvParser("bc91b48588048871f4b898af9371ccc8", true);

		$result = $parser->parse($demoPayload);

		$this->assertIsArray($result);
		$this->assertEquals(1, sizeof($result));
	}


	public function testIncompletePayloadWithValidSignatureCannotBeParsed(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","PayPerViewInfo":{"VHost":[{"name":"your-edgeserver.yourdomain.com","Application":[{"name":"live","Instance":[{"name":"_definst_","Stream":[{"name":"livestream","Player":[{"id":"500","ip":"127.0.0.1","sessionid":"1001","delta":7000,"bytes_sent":13981102,"user_agents":["Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/15.4 Safari\/605.1.15"]}]}]}]},{"name":"vod_abr","Instance":[{"name":"_definst_","Stream":[{"name":"vodfile/smil:vod.smil","Player":[{"id":"599","ip":"127.0.0.1","sessionid":"1010","delta":15000,"bytes_sent":4309566,"user_agents":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/101.0.4951.64 Safari\/537.36 Edg\/101.0.1210.47"]}]}]}]}]}]}}';

		$parser = new PpvParser("bc91b48588048871f4b898af9371ccc8", true);

		$this->expectException( SignatureValidationException::class);
		$result = $parser->parse($demoPayload);
	}

	public function testInvalidPayloadWithValidSignatureCannotBeParsed(): void
	{
		$demoPayload = '!!!"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103","PayPerViewInfo":{"VHost":[{"name":"your-edgeserver.yourdomain.com","Application":[{"name":"live","Instance":[{"name":"_definst_","Stream":[{"name":"livestream","Player":[{"id":"500","ip":"127.0.0.1","sessionid":"1001","delta":7000,"bytes_sent":13981102,"user_agents":["Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/15.4 Safari\/605.1.15"]}]}]}]},{"name":"vod_abr","Instance":[{"name":"_definst_","Stream":[{"name":"vodfile/smil:vod.smil","Player":[{"id":"599","ip":"127.0.0.1","sessionid":"1010","delta":15000,"bytes_sent":4309566,"user_agents":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/101.0.4951.64 Safari\/537.36 Edg\/101.0.1210.47"]}]}]}]}]}]}}';

		$parser = new PpvParser("bc91b48588048871f4b898af9371ccc8", true);

		$this->expectException( ParserException::class);
		$result = $parser->parse($demoPayload);
	}

	public function testResponseWithSolutionCanBeGeneratedWithValidPayload(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103","PayPerViewInfo":{"VHost":[{"name":"your-edgeserver.yourdomain.com","Application":[{"name":"live","Instance":[{"name":"_definst_","Stream":[{"name":"livestream","Player":[{"id":"500","ip":"127.0.0.1","sessionid":"1001","delta":7000,"bytes_sent":13981102,"user_agents":["Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/15.4 Safari\/605.1.15"]}]}]}]},{"name":"vod_abr","Instance":[{"name":"_definst_","Stream":[{"name":"vodfile/smil:vod.smil","Player":[{"id":"599","ip":"127.0.0.1","sessionid":"1010","delta":15000,"bytes_sent":4309566,"user_agents":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/101.0.4951.64 Safari\/537.36 Edg\/101.0.1210.47"]}]}]}]}]}]}}';
		$denyIds = [1, '5', 10];

		$parser = new PpvParser("bc91b48588048871f4b898af9371ccc8", true);

		$result = $parser->generateResponse($denyIds, $demoPayload);

		$this->assertIsArray($result);
		$this->assertArrayHasKey("Solution", $result);

	}

	public function testResponseWithoutSolutionCanBeGeneratedWithValidPayload(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103","PayPerViewInfo":{"VHost":[{"name":"your-edgeserver.yourdomain.com","Application":[{"name":"live","Instance":[{"name":"_definst_","Stream":[{"name":"livestream","Player":[{"id":"500","ip":"127.0.0.1","sessionid":"1001","delta":7000,"bytes_sent":13981102,"user_agents":["Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/15.4 Safari\/605.1.15"]}]}]}]},{"name":"vod_abr","Instance":[{"name":"_definst_","Stream":[{"name":"vodfile/smil:vod.smil","Player":[{"id":"599","ip":"127.0.0.1","sessionid":"1010","delta":15000,"bytes_sent":4309566,"user_agents":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/101.0.4951.64 Safari\/537.36 Edg\/101.0.1210.47"]}]}]}]}]}]}}';
		$denyIds = [1, '5', 10];

		$parser = new PpvParser();

		$result = $parser->generateResponse($denyIds, $demoPayload);

		$this->assertIsArray($result);
		$this->assertArrayNotHasKey("Solution", $result);
	}
}