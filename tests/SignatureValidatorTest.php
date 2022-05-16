<?php

use KevAc\WmsPanel\PpvParser\SignatureValidator;
use PHPUnit\Framework\TestCase;

final class SignatureValidatorTest extends TestCase
{
	public function testValidSignaturePasses(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103"}';
		$demoPayload = json_decode($demoPayload, false);

		$result = SignatureValidator::validate($demoPayload->Signature, $demoPayload->ID, $demoPayload->Puzzle, "bc91b48588048871f4b898af9371ccc8");

		$this->assertTrue($result);
	}

	public function testInvalidSignatureDoesNotPass(): void
	{
		$demoPayload = '{"ID":"5f0d5962-33b1-5137-ae6a-32776bcabc69","Signature":"7r/RZeJ18rMuRYc8luBdFQ==","Puzzle":"8c537e85-9684-a3b5-cbd9-bbebe952b103"}';
		$demoPayload = json_decode($demoPayload, false);

		$result = SignatureValidator::validate($demoPayload->Signature, $demoPayload->ID, $demoPayload->Puzzle, "Saisahz5yeiC4gaitei3taeshah3joab");

		$this->assertFalse($result);
	}

}