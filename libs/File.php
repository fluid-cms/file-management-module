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


	public function __construct($identificator, $name, $size = null, $type = null)
	{
		$this->setIdentificator($identificator);
		$this->setName($name);
		$this->setSize($size);
		$this->setType($type);
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

}
