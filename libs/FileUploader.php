<?php

namespace Grapesc\GrapeFluid\FileManagementModule;
use Grapesc\GrapeFluid\FileManagementModule\Model\FileModel;
use Nette\Http\FileUpload;


/**
 * @author Jiri Novy <novy@grapesc.cz>
 */
class FileUploader
{

	/** @var FileRepository */
	private $fileRepository;

	/** @var FileModel */
	private $fileModel;


	public function __construct(FileRepository $fileRepository, FileModel $fileModel)
	{
		$this->fileRepository = $fileRepository;
		$this->fileModel      = $fileModel;
	}


	/**
	 * @param FileUpload[] $filesUploads
	 * @param int $categoryId
	 * @return int
	 */
	public function uploadFile(array $filesUploads, $categoryId)
	{
		$processedCount = 0;

		foreach ($filesUploads AS $fileUpload) {
			if ($fileUpload->isOk()) {
				$filename = $this->getUniqueName($fileUpload->getSanitizedName());
				$filePath = $this->fileRepository->getCategoryPath($categoryId, true) . DIRECTORY_SEPARATOR . $filename;

				if ($fileUpload->move($filePath)) {
					$this->fileModel->insert([
						'name'                       => $fileUpload->getName(),
						'filepath'                   => $filePath,
						'filename'                   => $filename,
						'size'                       => $fileUpload->getSize(),
						'filemanagement_category_id' => $categoryId,
						'type'                       => $fileUpload->getContentType()
					]);
					$processedCount++;
				}
			}
		}

		return $processedCount;
	}


	/**
	 * @param string $fileName
	 * @return string
	 */
	protected function getUniqueName($fileName)
	{
		return microtime(true) . $fileName;
	}

}