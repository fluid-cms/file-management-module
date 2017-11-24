<?php

namespace Grapesc\GrapeFluid\FileManagementModule\Model;

use Grapesc\GrapeFluid\Model\BaseModel;


class FileModel extends BaseModel
{

	/**
	 * @param $categoryIds
	 * @return array|\Nette\Database\Table\IRow[]|\Nette\Database\Table\Selection
	 */
	public function getFilesByCategoryIds($categoryIds)
	{
		return $selection = $this->getTableSelection()
			->select('*')
			->where('filemanagement_category_id IN ?', $categoryIds)
			->fetchAll();
	}

}