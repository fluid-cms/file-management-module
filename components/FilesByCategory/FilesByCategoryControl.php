<?php

namespace Grapesc\GrapeFluid\FileManagementModule\Control\FilesByCategory;

use Grapesc\GrapeFluid\FileManagementModule\FileLoader;
use Grapesc\GrapeFluid\FileManagementModule\Model\FileModel;
use Grapesc\GrapeFluid\MagicControl\BaseMagicTemplateControl;
use Nette\Security\User;

/**
 * Class FilesControl
 * @package Grapesc\GrapeFluid\FileManagementModule\Control
 * @usage: {magicControl filesByCategory, ['uid', 'string categoryIds', 'string title']}
 */
class FilesByCategoryControl extends BaseMagicTemplateControl
{

	/** @var User @inject */
	public $user;

	/** @var array */
	private $categoryIds = null;

	/** @var string */
	private $title = null;

	/** @var FileLoader @inject */
	public $fileLoader;

	/** @var FileModel @inject */
	public $fileModel;

	/** @var string|null */
	protected $defaultTemplateFilename = __DIR__ . '/filesByCategory.latte';
	

	/**
	 * @param array $params
	 */
	public function setParams(array $params = [])
	{
		if (isset($params[1]) && is_string($params[1])) {
			explode(',', $params[1]);
			$this->categoryIds = explode(',', $params[1]);
		}

		if (isset($params[2])) {
			$this->title = (string) $params[2];
		}
	}


	public function render()
	{
		$this->template->files = $this->fileLoader->getFilesByExtenderAndExtenderIds($this->categoryIds, $this->user->getId(), 'Grapesc\GrapeFluid\FileManagementModule\Extenders\DbFiles');
		$this->template->title = $this->title;

		$this->template->render();
	}

}
