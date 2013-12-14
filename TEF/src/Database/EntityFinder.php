<?php
/*************************************
 * Title 			: 	EntityFinder.php
 * Creation_date 	:	1/10/2013 
 * Author 			: 	Jeremy DENIS
 * Licence 			: 	php
 * Description 		: 	class to find the Entity in the database
 *************************************/
namespace Database;

use Database\Finder;
use Model\Entity;

class EntityFinder implements Finder
{
	private $driver;
	private $entity;
	
	/**function __construct
	 * Constructor of the entity finder
	 * @param $driver the driver use to recover data
	 * @param the entity concern by the finder
	 */
	public function __construct($driver, Entity $entity)
	{
		$this->driver = $driver;
		$this->entity = $entity;
	}
	
	/**function : findOneByID
	 * @return one entity find by his id
	 * @param $id the id of the entity to get
	 */
	public function findOneById($id)
	{
		$result = $this->driver->findOneById($id,Entity::toTableName($this->entity->getName()),$this->entity->getIdColumn());
		if ($result != null)
		{
			$entity = $this->entity->newInstance();
			foreach ($result as $key => $value)
			{
				$entity->set($key,$value);
			}
			return $entity;
		}
		return null;
	}

	/**function : findAll
	 * function to get all the entity in the database
	 * @return a set of all th entity
	 * @param $orderBy : the column use to order the set
	 * @param $criteria : a key value array that match the result return
	 * @param $revert : to choose if the order of the set is acendent or descendent
	 * @param $where : the where clause of the request 
	 */
	public function findAll($orderBy = null,$criteria=null,$revert=0,$where = null)
	{
		$entities = $this->driver->findAll(Entity::toTableName($this->entity->getName()),$orderBy,$criteria,$revert,$where);
		$arrayEntity = array();
		foreach ($entities as $temp)
		{
			$entity = $this->entity->newInstance();
			foreach ($temp as $key => $value)
			{
				$entity->set($key,$value);
			}
			$arrayEntity[$temp[$this->entity->getIdColumn()]] = $entity;
		}
		return $arrayEntity;
	}	
	
	/**function : getEntityName
	 * function to get the name of the entity that match the finder 
	 * @return the name of the entity table where the data are extract 
	 */
	public function getEntityName()
	{
		return $this->entity->getName();
	}
	
	/**function : getIdColumn
	 * function to get the name of the column where are saved the id of the entity in the database
	 * @return the name of the id column of the entity
	 */
	public function getEntityTable()
	{
		return $this->entity->toTableName($this->entity->getName());
	}
	
	/**function : getIdColumn
	 * function to get the name of the column where are saved the id of the entity in the database
	 * @return the name of the id column of the entity
	 */
	public function getColumnId()
	{
		return $this->entity->getIdColumn();
	}
}
