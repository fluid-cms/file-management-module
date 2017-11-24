<?php

namespace Grapesc\GrapeFluid\FileManegementModule\Presenters;


use Grapesc\GrapeFluid\Application\BasePresenter;
use Grapesc\GrapeFluid\FileManagementModule\FileContent;
use Grapesc\GrapeFluid\FileManagementModule\FileLoader;
use Grapesc\GrapeFluid\FileManagementModule\Model\FileModel;
use Grapesc\GrapeFluid\Security\User;

final class FileManagementPresenter extends BasePresenter
{

	/** @var User @inject */
	public $user;

	/** @var FileLoader @inject */
	public $fileLoader;

	/** @var FileModel @inject */
	public $fileModel;


	/**
	 * @param $hash
	 */
	public function actionDownload($hash)
	{
		try {
			/** @var FileContent $fileContent */
			$fileContent = $this->fileLoader->downloadFileByHash($hash);
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}

		$this->downloadFile($fileContent);
	}


	/**
	 * @param $id int FileDBId
	 */
	public function actionDownloadLocalDbFile($id)
	{
		$fileContent = null;

		try {
			$fileRow = $this->fileModel->getItem($id);
			if ($fileRow) {
				$fileContent = new FileContent($fileRow->filename, file_get_contents($fileRow->filepath));
			} else {
				throw new \Exception('File not found');
			}
		} catch (\Exception $e) {
			$this->error('Soubor neexistuje');
		}

		$this->downloadFile($fileContent);
	}


	/**
	 * @param $fileContent
	 */
	private function downloadFile($fileContent)
	{
		$httpResponse = $this->getHttpResponse();
		$httpResponse->setContentType('application/octet-stream');
		$httpResponse->setHeader('Content-Disposition', 'attachment; filename="' . $fileContent->getName() . '"');
		$httpResponse->setHeader('Content-Length', strlen($fileContent->getContent()));
		echo $fileContent->getContent();

		$this->terminate();
	}

}
