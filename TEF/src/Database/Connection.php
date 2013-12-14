<?php

namespace Database;

use PDO;

class Connection extends PDO
{
	public function __construct($dsn, $username, $password)
	{
		 parent::__construct($dsn, $username, $password);
		//parent::setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	
	public function executeQuery($query, $parameters = array())
	{
		$statement = $this->prepare($query);
		foreach($parameters as $name => $value) 
		{
			$param = FALSE;
			if (is_int($value))
				$param =PDO::PARAM_INT;
			elseif (is_bool($value))
			{
				$param =PDO::PARAM_BOOL;
			}
			elseif (is_string($value))
			{
				$param =PDO::PARAM_STR;
			}
			elseif (is_null($value))
			{
				$param =PDO::PARAM_NULL;
			}
			$statement->bindValue(':'.$name,$value,$param);
		}
		$statement->execute();	
		return $statement;
	} 
}
