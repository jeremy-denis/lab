<?php

use Message;

namespace Message\News;

class News:MessageInterface
{
	private $id;
	private $from;
	private $body;
	private $date;
	
	public function __construct($id,$from,$date,$body=null)
	{
		$this->id = $id;
		$this->from = $from;
		$this->date = $date;
		if ($body != null)
		{
			$this->body = $body;
		}
	}
	
	public function setBody($body)
	{
		$this->body = $body;
	}
	
	public function send()
	{
		
	}
}
