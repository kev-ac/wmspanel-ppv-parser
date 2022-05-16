<?php

namespace KevAc\WmsPanel\PpvParser\Entity;

class VHost
{
	private string $name;
	private array $applications;

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
	public function getApplications(): array
	{
		return $this->applications;
	}

	/**
	 * @param Application $application
	 */
	public function addApplication( Application $application ): void
	{
		$this->applications[] = $application;
	}

}