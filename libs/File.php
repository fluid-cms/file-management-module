<?php

namespace Grapesc\GrapeFluid\FileManagementModule;

use Nette\Utils\Html;

class File
{

	/** @var mixed */
	private $identificator;

	/** @var string */
	private $name;

	/** @var string */
	private $type;

	/** @var int */
	private $size;

	/** @var array additional data provided by the extender (e.g. tags, created date), available in templates */
	private $metadata = [];


	public function __construct($identificator, $name, $size = null, $type = null, array $metadata = [])
	{
		$this->setIdentificator($identificator);
		$this->setName($name);
		$this->setSize($size);
		$this->setType($type);
		$this->setMetadata($metadata);
	}


	/**
	 * @return Html|string
	 */
	public function getIdentificator()
	{
		return $this->identificator;
	}


	/**
	 * @param Html|string $identificator
	 */
	public function setIdentificator($identificator)
	{
		$this->identificator = $identificator;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}


	/**
	 * @return int
	 */
	public function getSize()
	{
		return $this->size;
	}


	/**
	 * @param int $size
	 */
	public function setSize($size)
	{
		$this->size = $size;
	}


	/**
	 * @return array
	 */
	public function getMetadata()
	{
		return $this->metadata;
	}


	/**
	 * @param array $metadata
	 */
	public function setMetadata(array $metadata)
	{
		$this->metadata = $metadata;
	}


	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getMeta($key, $default = null)
	{
		return array_key_exists($key, $this->metadata) ? $this->metadata[$key] : $default;
	}

}
