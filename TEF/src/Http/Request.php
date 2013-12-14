<?php

namespace Http;

class Request 
{
	private $parameters = array();
    
    public function __construct(array $query = array(), array $request = array()) 
    {
		$this->parameters = array_merge($query, $request);
	}
	
	public static function createFromGlobals()
	{
		return new self($_GET,$_POST);
	}
	
	public function getURI() 
	{
		if (isset($_SERVER['REQUEST_URI']))
		{
			$uri = $_SERVER['REQUEST_URI'];
		}
		else
		{
			$uri = '/';
		}
		
		if ($pos = strpos($uri, '?')) 
		{
	    	$uri = substr($uri, 0, $pos);
		}
		
		return $uri;
	}
	
	public function getParameter($name, $default = null)
	{
		if(isset($this->parameters[$name]))
		{
			return $this->parameters[$name];
		}
		return $default;
		
	}
}
