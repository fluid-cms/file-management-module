<?php

namespace Grapesc\GrapeFluid\FileManagementModule;
use Grapesc\GrapeFluid\FileManagementModule\Model\CategoryModel;
use Grapesc\GrapeFluid\FileManagementModule\Model\FileModel;
use Nette\Database\Table\IRow;
use Nette\Utils\Finder;

/**
 * @author Jiri Novy <novy@grapesc.cz>
 */
class FileRepository
{

	/** @var string */
	private $directoryPath;

	/** @var string */
	private $categoryIdPrefix;

	/** @var FileModel */
	private $fileModel;

	/** @var CategoryModel */
	private $categoryModel;


	public function __construct($directoryPath, $categoryIdPrefix = "category-", FileModel $fileModel = null, CategoryModel $categoryModel = null)
	{
		if (!file_exists($directoryPath)) {
			throw new \InvalidArgumentException("Directory path $directoryPath for files isn't accessible");
		}

		$this->directoryPath   = $directoryPath;
		$this->categoryIdPrefix = $categoryIdPrefix;
		$this->fileModel       = $fileModel;
		$this->categoryModel   = $categoryModel;
	}


	/**
	 * @param int $categoryId
	 * @param bool $createIfNotExists
	 * @return string
	 */
	public function getCategoryPath($categoryId, $createIfNotExists = false)
	{
		$path = $this->directoryPath . DIRECTORY_SEPARATOR . $this->categoryIdPrefix . $categoryId;

		if ($createIfNotExists AND !is_dir($path)) {
			mkdir($path, 0775, true);
		}

		return $path;
	}


	/**
	 * @param int|IRow $fileId
	 * @return bool
	 */
	public function deleteFile($fileId)
	{
		if (is_object($fileId) AND $fileId instanceof IRow) {
			$item = $fileId;
		} else {
			$item = $this->fileModel->getItem($fileId);
		}

		if ($item) {
			$categoryId   = $item->filemanagement_category_id;
			$categoryPath = $this->getCategoryPath($categoryId);

			foreach (Finder::findFiles($item->filename)->from($categoryPath) AS $file) {
				unlink($file->getPathname());
			}

			$item->delete();

			return true;
		}

		return false;
	}


	/**
	 * @param int $categoryId
	 * @return bool
	 */
	public function deleteCategory($categoryId)
	{
		$category = $this->categoryModel->getItem($categoryId);

		if (!$category) {
			return false;
		}

		$files = $this->fileModel->getItemsBy($categoryId, "filemanagement_category_id");

		foreach ($files AS $file) {
			$this->deleteFile($file);
		}

		$category->delete();

		if (is_dir($this->getCategoryPath($categoryId))) {
			foreach (Finder::findDirectories('*')->in($this->getCategoryPath($categoryId)) AS $dir) {
				rmdir($dir->getPathname());
			}

			return rmdir($this->getCategoryPath($categoryId));
		} else {
			return true;
		}
	}

}
