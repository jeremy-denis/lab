<?php
/*************************************
 * Title 			: 	Driver.php
 * Creation_date 	:	1/10/2013 
 * Author 			: 	Jeremy DENIS
 * Licence 			: 	php
 * Description 		: 	Class to have automatic sql request based on no sql API
 *************************************/
namespace Database;
use PDO;

class Driver implements DriverInterface
{
	private $connection;
	
	/**function : __construct
	 * constructor of the driver
	 * @param $connection : connection the database to the driver
	 */
	public function __construct($connection)
	{
		$this->connection = $connection;
	}
	
	/**function : save
	 * function which saved or update an entity in the database
	 * @param $arrayData : an array with the different value of the properties of the entity to save
	 * @param $table : the table where save the entity
	 * @param $id the id of the entity to save it i database by default it equal to -1
	 * @param $idColumn : the name of the id column of the entity to save
	 */
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
	
	/**function : findOneById
	 * function which find one object in the database based on his id
	 * @return one object based  on is id
	 * @param $id the id of the entity to save it i database by default it equal to -1
	 * @param $table : the table where save the entity
	 * @param $idColumn : the name of the id column of the entity to save
	 */
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
	
	/**function : findall
	 * function which find a set of object that match criteria
	 * @return a set of data based on the criteria passed on parameter
	 * @param $table : the table where save the entity
	 * @param $order : the column use to order the set
	 * @param $criteria : a key value array that match the result return
	 * @param $table : the table where find data
	 * @param $revert : to choose if the order of the set is acendent or descendent
	 * @param $where : the where clause of the request 
	 */
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
	
	/**function : delete
	 * function which delete object in the database based on his id
	 * @param $id the id of the entity to save it i database by default it equal to -1
	 * @param $columnName : the name of the id column of the entity to save
	 * @param $table : the table where save the entity
	 */
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
