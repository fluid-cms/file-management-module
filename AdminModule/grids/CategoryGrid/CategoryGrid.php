<?php

namespace Grapesc\GrapeFluid\FileManagementModule\Grid;

use Grapesc\GrapeFluid\FluidGrid;
use Grapesc\GrapeFluid\FileManagementModule\FileRepository;
use Nette\Database\Table\ActiveRow;


/**
 * @model \Grapesc\GrapeFluid\FileManagementModule\Model\CategoryModel
 */
class CategoryGrid extends FluidGrid
{

	/** @var FileRepository @inject */
	public $fileRepository;


	protected function build()
	{
		$this->setItemsPerPage(15);
		$this->skipColumns(["created_on", "created_by_id"]);
		$this->addRowAction("files", "Spravovat soubory", [$this, 'files']);
		$this->addRowAction("show_in_preview", "Zobrazovat v celkovém náhledu", function(ActiveRow $record) {
			$this->model->update(["show_in_preview" => !$record->show_in_preview], $record->id);
		});
		$this->addRowAction("edit", "Upravit", [$this, 'editCategory']);
		$this->addRowAction("delete", "Smazat", [$this, 'deleteCategory']);
		parent::build();
		$this->addColumn("count");
	}


	public function deleteCategory(ActiveRow $record)
	{
		if ($this->fileRepository->deleteCategory($record->id)) {
			$this->getPresenter()->flashMessage("Galerie smazána", "success");
		} else {
			$this->getPresenter()->flashMessage("Galerii se nepodařilo odstranit", "danger");
		}
		$this->getPresenter()->redrawControl("flashMessages");
	}


	public function editCategory(ActiveRow $record)
	{
		$this->getPresenter()->redirect("category-form", ["id" => $record->id]);
	}


	public function files(ActiveRow $record)
	{
		$this->getPresenter()->redirect("category-files", ["id" => $record->id]);
	}

}