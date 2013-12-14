<?php

namespace Parser;

class XmlParser implements ParserInterface
{
	private $filepath;
	
	public function __construct($filepath)
	{
		$this->filepath = $filepath;
	}
	
	public function ParseToDataArray()
	{
		$array = array();
		return $array;
	}
}
