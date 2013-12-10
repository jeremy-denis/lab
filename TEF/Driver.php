<?php
/*************************************
 * Title 			: 	Driver.php
 * Creation_date 	:	1/10/2013 
 * Author 			: 	Jeremy DENIS
 * Licence 			: 	apache2
 * Description 		: 	base for a non sql API
 *************************************/

namespace Database;
use PDO;

class Driver implements DriverInterface
{
	private $connection;
	
	public function __construct($connection)
	{
		$this->connection = $connection;
	}
	
	public function save($arrayData, $table, $id = -1, $idColumn = 'id')
	{
		$cpt = 0;
		$end = "";
		if ($this->findOneById($id,$table,$idColumn) == null)
		{
			$query = sprintf('INSERT INTO %s ',$table);
			$query .= '('; 
			$values ='(';
			foreach ($arrayData as $key => $value)
			{
				$query .= $key;
				$values .= $value; 
				$cpt += 1;
				if ($cpt < count($arrayData))
				{
					$query .= ',';
					$values .= ','; 
				}
			}
			$query .= ') VALUES ';
			$values .= ')';
			$query .= $values;
			$query .= $end;
		}
		else
		{
			$query = sprintf('UPDATE %s set',$table);
			foreach ($arrayData as $key => $value)
			{
				$query .= ' ';
				$query .= $key;
				$query .= '=';
				$query .= $value; 
				$cpt += 1;
				if ($cpt < count($arrayData))
				{
					$query .= ',';
				}
			}
			$query .= ' where '.$idColumn.'=\''.$id.'\''; 
		}
		$stm = $this->connection->executeQuery($query,array());
	}
	
	public function findOneById($id, $table, $idColumn = 'id')
	{
		$query = 'SELECT * from '."$table WHERE $idColumn = :id";
		$stm = $this->connection->executeQuery($query,array(
				'id' => $id,
			));
		foreach($stm as $temp)
		{
			return $temp;
		}
		return null;
	}
	
	public function findall($table,$order = null,$criteria=null,$revert=0, $where=null)
	{
		$final = array();
		$query = 'SELECT * from '.$table;
		if ($where != null)
		{
			$query .= $where;
		}
		else
		{
			if ($criteria != null)
			{
				$where = '';
				foreach ($criteria as $key => $value)
				{
					if (is_string($value))
					{
						$value = '\''.$value.'\'';
					}
					
					if ($where == '')
					{
						$where .= " WHERE $key = $value";
					}
					else
					{
						$where .= " AND $key = $value";
					}
				}
				$query .= $where;
			}
		}
				
		if ($order != null)
		{
			$query .= " ORDER BY `$order`";
			if ($revert)
			{
				$query .= ' DESC';
			}
			else
			{
				$query .= ' ASC';
			}
		}
		
		$stm = $this->connection->executeQuery($query,array());
		foreach($stm as $temp)
		{
			$final[] = $temp;
		}
		return $final;
	}
	
	public function delete($id,$columnName,$table)
	{
		if (is_string($id))
		{
			$id = "'$id'";
		}
		$query = "DELETE from $table where $columnName=$id";
		$stm = $this->connection->executeQuery($query,array());
	}
}
