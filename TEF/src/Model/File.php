<?php

namespace Model;

Class File
{
	private $path;
	
	public function __construct($path)
	{
		$this->path = $path;
	}
	
	public function createFile()
	{
		$file = fopen($this->path, 'wb'); 
		fclose($file);
	}
	
	public function removeFile()
	{
		if (file_exists($this->path))
		{
			unlink($this->path);
		}
	}
	
	public function fillFile($data)
	{
		$file = fopen($this->path, 'w+'); 
		if (fwrite($file, $data) === FALSE) {
        echo "Impossible d'écrire dans le fichier ($this->path)";
        exit;
    }
		fclose($file);
	}
	
}
