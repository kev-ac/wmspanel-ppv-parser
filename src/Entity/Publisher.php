<?php

namespace KevAc\WmsPanel\PpvParser\Entity;

class Publisher
{
    private string $sequence;
    private string $id;
    private string $ip;
    private string $stream;

	public function __construct() {}

	/**
	 * @return string
	 */
	public function getSequence(): string {
		return $this->sequence;
	}

	/**
	 * @param string $sequence
	 */
	public function setSequence( string $sequence ): void {
		$this->sequence = $sequence;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( string $id ): void {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getIp(): string {
		return $this->ip;
	}

	/**
	 * @param string $ip
	 */
	public function setIp( string $ip ): void {
		$this->ip = $ip;
	}

	/**
	 * @return string
	 */
	public function getStream(): string {
		return $this->stream;
	}

	/**
	 * @param string $stream
	 */
	public function setStream( string $stream ): void {
		$this->stream = $stream;
	}


}