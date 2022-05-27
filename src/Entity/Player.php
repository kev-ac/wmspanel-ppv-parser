<?php

namespace KevAc\WmsPanel\PpvParser\Entity;

class Player
{
	private string $id;
	private ?string $ip = null;
	private string $sessionId;
	private int $delta;
	private int $bytesSent;
	private array $userAgents = [];

	private string $streamName;

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( string $id ): void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getIp(): string
	{
		return $this->ip;
	}

	/**
	 * @param string $ip
	 */
	public function setIp( string $ip ): void
	{
		$this->ip = $ip;
	}

	/**
	 * @return string
	 */
	public function getSessionId(): string
	{
		return $this->sessionId;
	}

	/**
	 * @param string $sessionId
	 */
	public function setSessionId( string $sessionId ): void
	{
		$this->sessionId = $sessionId;
	}

	/**
	 * @return int
	 */
	public function getDelta(): int
	{
		return $this->delta;
	}

	/**
	 * @param int $delta
	 */
	public function setDelta( int $delta ): void
	{
		$this->delta = $delta;
	}

	/**
	 * @return int
	 */
	public function getBytesSent(): int
	{
		return $this->bytesSent;
	}

	/**
	 * @param int $bytesSent
	 */
	public function setBytesSent( int $bytesSent ): void
	{
		$this->bytesSent = $bytesSent;
	}

	/**
	 * @return array
	 */
	public function getUserAgents(): array
	{
		return $this->userAgents;
	}

	/**
	 * @param string $userAgent
	 */
	public function addUserAgent( string $userAgent ): void
	{
		$this->userAgents[] = $userAgent;
	}

	/**
	 * @return string
	 */
	public function getStreamName(): string
	{
		return $this->streamName;
	}

	/**
	 * @param string $streamName
	 */
	public function setStreamName( string $streamName ): void
	{
		$this->streamName = $streamName;
	}


}