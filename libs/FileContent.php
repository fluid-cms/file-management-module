<?php

namespace Grapesc\GrapeFluid\FileManagementModule;


class FileContent
{

	/** @var string */
	private $name;

	/** @var string */
	private $content;


	public function __construct($name, $content)
	{
		$this->name    = $name;
		$this->content = $content;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

}
