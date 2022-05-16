<?php

namespace KevAc\WmsPanel\PpvParser\Entity;

class Application
{
	private string $name;
	private array $streams;
	private array $players;

	public function __construct() {}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	public function getStreams(): array
	{
		return $this->streams;
	}

	/**
	 * @param Stream $stream
	 */
	public function addStream( Stream $stream ): void
	{
		$this->streams[] = $stream;
	}

	/**
	 * @return array
	 */
	public function getPlayers(): array
	{
		return $this->players;
	}

	/**
	 * @param Player $player
	 */
	public function addPlayer( Player $player ): void
	{
		$this->players[] = $player;
	}



}