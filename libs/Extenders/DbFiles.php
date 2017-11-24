<?php

namespace Grapesc\GrapeFluid\FileManagementModule\Extenders;
use Grapesc\GrapeFluid\FileManagementModule\File;
use Grapesc\GrapeFluid\FileManagementModule\FileContent;
use Grapesc\GrapeFluid\FileManagementModule\Model\CategoryModel;
use Grapesc\GrapeFluid\FileManagementModule\Model\FileModel;
use Grapesc\GrapeFluid\Security\User;

/**
 * @author Jiri Novy <novy@grapesc.cz>
 */
class DbFiles implements IFileExtender
{

	/** @var FileModel @inject */
	public $fileModel;

	/** @var CategoryModel @inject */
	public $categoryModel;

	/** @var User @inject */
	public $user;



	/**
	 * @param null|string $category
	 * @return mixed
	 */
	public function getFiles($category = null)
	{
		$categoryIds = $this->categoryModel->getIdByName($category);
		return $this->getFilesByCategoryIds($categoryIds);
	}


	/**
	 * @param array|null $categoryIds
	 * @return mixed
	 */
	public function getFilesByCategoryIds(array $categoryIds = null)
	{
		$files         = [];
		$categoryIds   = $this->getOnlyAllowedCategoryIds($categoryIds);
		$fileSelection = $this->fileModel->getFilesByCategoryIds($categoryIds);
		foreach ($fileSelection as $file) {
			$files[] = new File($file->id, $file->name, $file->size, $file->type);
		}

		return $files;
	}


	/**
	 * @param $identificator
	 * @return FileContent
	 * @throws \Exception
	 */
	public function downloadFile($identificator)
	{
		$fileRow = $this->fileModel->getItem($identificator);
		if (!$fileRow) {
			throw new \Exception('File not found');
		}

		return new FileContent($fileRow->filename, file_get_contents($fileRow->filepath));
	}


	/**
	 * @param $categoryIds
	 * @return array
	 */
	private function getOnlyAllowedCategoryIds($categoryIds)
	{
		foreach ($categoryIds as $id => $categoryId) {
			if (
				!$this->user->isAllowed('file.category', 'read.' . $categoryId)) {
				unset($categoryIds[$id]);
			}
		}

		return $categoryIds;
	}

}