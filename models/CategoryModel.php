<?php

namespace Grapesc\GrapeFluid\FileManagementModule\Model;

use Grapesc\GrapeFluid\Model\BaseModel;


class CategoryModel extends BaseModel
{

	/**
	 * @param $name
	 * @return array
	 */
	public function getIdByName($name)
	{
		if ($name) {
			return $this->getTableSelection()
				->select('id')
				->where('name', $name)
				->where('show_in_preview = ?', 1)
				->fetchPairs('id', 'id');
		} else {
			return [];
		}
	}


	/**
	 * @param $ids
	 * @return array
	 */
	public function getIdsShowInPreview($ids)
	{
		return $this->getTableSelection()
			->select('id')
			->where('id IN ?', $ids)
			->where('show_in_preview = ?', 1)
			->fetchPairs('id', 'id');
	}

}