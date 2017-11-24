<?php

namespace Grapesc\GrapeFluid\FileManagementModule\Extenders;

use Grapesc\GrapeFluid\FileManagementModule\FileContent;

/**
 * @author Jiri Novy <novy@grapesc.cz>
 */
interface IFileExtender
{

	/**
	 * @param null|string $folder
	 * @return mixed
	 */
	public function getFiles($folder = null);


	/**
	 * @param array|null $categoryIds
	 * @return mixed
	 */
	public function getFilesByCategoryIds(array $categoryIds = null);


	/**
	 * @param $identificator
	 * @return FileContent
	 */
	public function downloadFile($identificator);
	
}