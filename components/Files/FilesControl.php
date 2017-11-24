<?php

namespace Grapesc\GrapeFluid\FileManagementModule\Control\Files;

use Grapesc\GrapeFluid\FileManagementModule\FileLoader;
use Grapesc\GrapeFluid\MagicControl\BaseMagicTemplateControl;
use Nette\Security\User;

/**
 * Class FilesControl
 * @package Grapesc\GrapeFluid\FileManagementModule\Control
 * @usage: {magicControl files, ['uid', 'string category', 'string title']}
 */
class FilesControl extends BaseMagicTemplateControl
{

	/** @var User @inject */
	public $user;

	/** @var string */
	private $category = null;

	/** @var string */
	private $title = null;

	/** @var FileLoader @inject */
	public $fileLoader;

	/** @var string|null */
	protected $defaultTemplateFilename = __DIR__ . '/files.latte';
	

	/**
	 * @param array $params
	 */
	public function setParams(array $params = [])
	{
		if (isset($params[1]) && is_string($params[1])) {
			$this->category = $params[1];
		}

		if (isset($params[2])) {
			$this->title = (string) $params[2];
		}
	}


	public function render()
	{
		$this->template->files = $this->fileLoader->getFilesFromExtenders($this->category, $this->user->getId());
		$this->template->title = $this->title;

		$this->template->render();
	}

}
