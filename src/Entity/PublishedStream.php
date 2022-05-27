<?php

namespace KevAc\WmsPanel\PpvParser\Entity;

class PublishedStream
{
	private string $application;
	private string $stream;
	private ?string $protocol;
	private ?\DateTime $time;
	private ?string $type;
	private ?string $remoteIp;

	public function __construct() {}

	/**
	 * @return string
	 */
	public function getApplication(): string {
		return $this->application;
	}

	/**
	 * @param string $application
	 */
	public function setApplication( string $application ): void {
		$this->application = $application;
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

	/**
	 * @return string|null
	 */
	public function getProtocol(): ?string {
		return $this->protocol;
	}

	/**
	 * @param string|null $protocol
	 */
	public function setProtocol( ?string $protocol ): void {
		$this->protocol = $protocol;
	}

	/**
	 * @return \DateTime|null
	 */
	public function getTime(): ?\DateTime {
		return $this->time;
	}

	/**
	 * @param \DateTime|null $time
	 */
	public function setTime( ?\DateTime $time ): void {
		$this->time = $time;
	}

	/**
	 * @return string|null
	 */
	public function getType(): ?string {
		return $this->type;
	}

	/**
	 * @param string|null $type
	 */
	public function setType( ?string $type ): void {
		$this->type = $type;
	}

	/**
	 * @return string|null
	 */
	public function getRemoteIp(): ?string {
		return $this->remoteIp;
	}

	/**
	 * @param string|null $remoteIp
	 */
	public function setRemoteIp( ?string $remoteIp ): void {
		$this->remoteIp = $remoteIp;
	}



}