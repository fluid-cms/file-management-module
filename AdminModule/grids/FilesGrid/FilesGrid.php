<?php

namespace Grapesc\GrapeFluid\FileManagementModule\Grid;

use Grapesc\GrapeFluid\FluidGrid;
use Grapesc\GrapeFluid\FileManagementModule\FileRepository;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;


/**
 * @model \Grapesc\GrapeFluid\FileManagementModule\Model\FileModel
 */
class FilesGrid extends FluidGrid
{

	/** @var FileRepository @inject */
	public $fileRepository;


	protected function build()
	{
		$this->setItemsPerPage(15);
		$this->skipColumns(["id", "filename", "filepath", "filemanagement_category_id"]);
		$this->setSortableColumns(['name', 'type', 'size']);
		$this->setFilterableColumns(['name', 'type']);
		$this->addRowAction("download", "Stáhnout", [$this, 'downloadFile']);
		$this->addRowAction("delete", "Smazat", [$this, 'deleteFile']);

		parent::build();
	}


	protected function processFilter(Selection &$selection, $filters)
	{
		parent::processFilter($selection, $filters);
		$parameters = $this->getPresenter()->getParameters();
		$selection->where("filemanagement_category_id = ?", $parameters['id']);
	}


	public function deleteFile(ActiveRow $record)
	{
		if ($this->fileRepository->deleteFile($record)) {
			$this->getPresenter()->flashMessage("Soubor byl smazán", "success");
		} else {
			$this->getPresenter()->flashMessage("Soubor se nepodařilo odstranit", "danger");
		}
		$this->getPresenter()->redrawControl("flashMessages");
	}

}