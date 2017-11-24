<?php
/**
 * @author: jirka
 * Date: 21.8.17
 */

namespace Grapesc\GrapeFluid\FileManagementModule;


class FileHelper
{

	/**
	 * @return int
	 */
	public static function getMaxUploadSize()
	{
		$umfs = self::getSize(ini_get('upload_max_filesize'));
		$pms  = self::getSize(ini_get('post_max_size'));
		return $umfs > $pms ? $umfs : $pms;
	}


	/**
	 * @param $size
	 * @return int
	 */
	private static function getSize($size)
	{
		$val  = trim($size);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}

}