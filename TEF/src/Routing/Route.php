<?php

namespace Routing;


class Route
{
	private $pattern;
	private $callable;
	private $arguments;
	
	public function __construct($pattern, $callable)
	{
		$this->pattern = $pattern;
		$this->callable = $callable;
        $this->arguments = array();
	}
	
	public function getPattern()
	{
		return $this->pattern;
	}
	
	public function match($uri)
	{
		//#^%s$#
		$patternCompile = sprintf('#%s$#', $this->pattern);
		if (preg_match($patternCompile, $uri, $this->arguments)) 
		{
            array_shift($this->arguments);
			return true;
        }
        return false;
	}
	
	public function getCallable()
	{
		return $this->callable;
	}
	
	public function getArguments()
	{
		return $this->arguments;
	}
}
