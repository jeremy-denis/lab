<?php

namespace UploadDownload;

class Download
{
	protected static function createHeader($file)
	{
		$size = filesize($file);
		$name = basename($file);
		
		header('Content-Description: File Transfer');
		header('Content-type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$name);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: '.$size);
	}
	 
	public static function downloadFile($file)
	{
		if (file_exists($file))
		{
			Download::createHeader($file);
			ob_clean();
			flush();
			readfile($file);
			exit();
		}
		else
		{
			echo 'file not found';
		}
	}
}
