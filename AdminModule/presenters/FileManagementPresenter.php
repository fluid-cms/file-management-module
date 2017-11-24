<?php

namespace Grapesc\GrapeFluid\AdminModule\Presenters;

use Grapesc\GrapeFluid\FileManagementFileManagementModule\CategoryForm;
use Grapesc\GrapeFluid\FileManagementModule\FileHelper;
use Grapesc\GrapeFluid\FileManagementModule\FileRepository;
use Grapesc\GrapeFluid\FileManagementModule\FileUploader;
use Grapesc\GrapeFluid\FileManagementModule\Grid\CategoryGrid;
use Grapesc\GrapeFluid\FileManagementModule\Grid\FilesGrid;
use Grapesc\GrapeFluid\FileManagementModule\Model\CategoryModel;
use Grapesc\GrapeFluid\FileManagementModule\Model\FileModel;
use Grapesc\GrapeFluid\FluidFormControl\FluidFormFactory;
use Grapesc\GrapeFluid\FluidGrid\FluidGridFactory;

class FileManagementPresenter extends BasePresenter
{

	/** @var FluidFormFactory @inject */
	public $fluidFormFactory;

	/** @var FluidGridFactory @inject */
	public $fluidGridFactory;

	/** @var CategoryModel @inject */
	public $categoryModel;

	/** @var FileUploader @inject */
	public $fileUploader;

	/** @var FileRepository @inject */
	public $fileRepository;

	/** @var FileModel @inject */
	public $fileModel;


	public function actionCategoryForm($id = null)
	{
		if ($id) {
			$category = $this->categoryModel->getItem($id);
			if (!$category) {
				$this->flashMessage("Kategorie neexistuje", "warning");
				$this->redirect("category");
			}
			$this->getComponent("categoryForm")->setDefaults($this->categoryModel->getItem($id));
		}
	}


	public function renderCategoryFiles($id)
	{
		$category = $this->categoryModel->getItem($id);

		if (!$category) {
			$this->flashMessage("Kategorie neexistuje", "warning");
			$this->redirect("category");
		}

		$this->template->maxUploadSize = FileHelper::getMaxUploadSize();
		$this->template->category      = $category;
	}


	public function handleUpload($categoryId)
	{
		$filesUploads          = $this->getHttpRequest()->getFiles();
		$sucessedCount         = $this->fileUploader->uploadFile($filesUploads, $categoryId);
		$this->payload->errors = count($filesUploads) - $sucessedCount;
		$this->sendPayload();
	}


	public function handleDelete($fileId)
	{
		if ($this->fileRepository->deleteFile($fileId)) {
			$this->flashMessage('Soubor odebrán');
		} else {
			$this->flashMessage('Soubor se nepodařilo odebrat', 'danger');
		}

		$this->redrawControl('flashMessages');
		$this->redrawControl('files');
	}


	public function handleRefreshFiles()
	{
		$this->redrawControl('files');
	}


	public function createComponentCategoryForm()
	{
		return $this->fluidFormFactory->create(CategoryForm::class);
	}


	public function createComponentCategoryGrid()
	{
		return $this->fluidGridFactory->create(CategoryGrid::class);
	}


	public function createComponentFilesGrid()
	{
		return $this->fluidGridFactory->create(FilesGrid::class);
	}

}