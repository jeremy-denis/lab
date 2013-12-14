<?php

namespace UploadDownload;

class Upload
{
	public static function uploadFile($from, $to)
	{
		return move_uploaded_file($from, $to);
	} 
}
