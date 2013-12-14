<?php
/*************************************
 * Title 			: 	DataFormat.php
 * Creation_date 	:	1/10/2013 
 * Author 			: 	Jeremy DENIS
 * Licence 			: 	php
 * Description 		: 	Class to extract data to the data format
 *************************************/

namespace Model\Extract;

use Model\Extract\IFormat;
use Model\Entity;

class DataFormat implements IFormat
{
	public function __construct()
	{
	}
	
	public static function export(Entity $entity,$extractHeader = 0)
	{
		$result = '';
		$values = '';
		$data = $entity->getAllDataArray();
		foreach ($data as $key => $value)
		{
			if($extractHeader)
			{
				$result .= (string)$key.',';
			}
			$values .= (string)$value.',';
		}
		
		if($extractHeader)
		{
			$result .= PHP_EOL;
		}
		
		$result .= (string)$values.'\n';
		return $result;
	}
	
	public function exportText(String $text)
	{
	}
	
	public function import()
	{
	}
}
