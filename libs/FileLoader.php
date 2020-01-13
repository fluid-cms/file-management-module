<?php

namespace Grapesc\GrapeFluid\FileManagementModule;
use Grapesc\GrapeFluid\FileManagementModule\Extenders\IFileExtender;
use Grapesc\GrapeFluid\FileManagementModule\Model\CategoryModel;
use Nette\DI\Container;
use Nette\Http\Session;


/**
 * @author Jiri Novy <novy@grapesc.cz>
 */
class FileLoader
{

	/** @var FileRepository */
	private $fileRepository;

	/** @var IFileExtender[] */
	private $extenders = [];

	/** @var Session */
	private $session;

	/** @var CategoryModel @inject */
	public $categoryModel;



	public function __construct(FileRepository $fileRepository, Container $container, Session $session, CategoryModel $categoryModel)
	{
		$this->fileRepository = $fileRepository;
		$this->session        = $session;
		$this->categoryModel  = $categoryModel;

		foreach (array_keys($container->findByTag('fluid.extender.filemanager')) AS $extender) {
			$service = $container->getService($extender);
			if ($service instanceof IFileExtender) {
				$this->extenders[get_class($service)] = $service;
			} else {
				throw new \RuntimeException('Filemanger extender mus implement IFileExtender');
			}
		}
	}


	/**
	 * @param $categoryId
	 * @param $fileName
	 * @return null|\SplFileInfo
	 */
	public function getFile($categoryId, $fileName)
	{
		$categoryPath = $this->fileRepository->getCategoryPath($categoryId, false);
		$filePath     = $categoryPath . DIRECTORY_SEPARATOR . $fileName;

		if (file_exists($filePath)) {
			return new \SplFileInfo($filePath);
		} else {
			return null;
		}
	}


	/**
	 * @param array $categoryIds
	 * @param $userId
	 * @param null|string $extenderName
	 * @return array
	 */
	public function getFilesByExtenderAndExtenderIds(array $categoryIds, $userId = null, $extenderName = null)
	{
		$files   = [];
		$storage = [];

		$this->categoryModel->getIdsShowInPreview($categoryIds);
		foreach ($this->extenders as $name => $extender) {
			if (!$extenderName || $extenderName == $name) {
				$this->getFiles($files, $storage, $extender->getFilesByCategoryIds($categoryIds), $extender, $userId);
			}
		}

		$this->saveIntoSession($storage);

		return $files;
	}


	/**
	 * @param $category
	 * @param $userId
	 * @return array
	 */
	public function getFilesFromExtenders($category, $userId = null)
	{
		$files   = [];
		$storage = [];
		foreach ($this->extenders as $extender) {
			$this->getFiles($files, $storage, $extender->getFiles($category), $extender, $userId);
		}

		$this->saveIntoSession($storage);

		return $files;
	}


	/**
	 * @param $hash
	 * @return Extenders\FileContent
	 * @throws \Exception
	 */
	public function downloadFileByHash($hash)
	{
		$sessionSection = $this->session->getSection('FileManagement');
		if (isset($sessionSection->fileLoader) && is_array($sessionSection->fileLoader)) {
			foreach ($sessionSection->fileLoader as $extender => $md5Array) {
				if (array_key_exists($hash, $md5Array)) {
					try {
						return $this->extenders[$extender]->downloadFile($md5Array[$hash]);
					}catch (\Exception $e) {
						throw new \Exception('Soubor neexistuje');
					}
				}
			}
		}

		throw new \Exception('Soubor se nepodařilo stáhnout, protože vypršela sesion. Obnovte prosím stránku a zkuste to znovu');
	}


	/**
	 * @param $foundedFiles
	 * @param $extender
	 * @param $files
	 * @param $userId
	 * @param $storage
	 * @return array
	 */
	private function getFiles(&$files, &$storage, $foundedFiles, $extender, $userId)
	{
		if ($foundedFiles) {
			foreach ($foundedFiles as $file) {
				$md5         = md5(get_class($extender) . serialize($file) . $userId);
				$files[$md5] = $file;
				if (!array_key_exists(get_class($extender), $storage)) {
					$storage[get_class($extender)] = [];
				}
				$storage[get_class($extender)][$md5] = $file->getIdentificator();
			}
		} else {
			$storage[get_class($extender)] = [];
		}
	}


	/**
	 * @param array $storage
	 */
	private function saveIntoSession(array $storage)
	{
		$sessionSection = $this->session->getSection('FileManagement');
		if (isset($sessionSection->fileLoader) && is_array($sessionSection->fileLoader)) {
			foreach ($storage AS $extenderClass => $files) {
				$sessionSection->fileLoader[$extenderClass] = array_merge(isset($sessionSection->fileLoader[$extenderClass]) ? $sessionSection->fileLoader[$extenderClass] : [], $files);
			}
		} else {
			$sessionSection->fileLoader = $storage;
		}
	}

}