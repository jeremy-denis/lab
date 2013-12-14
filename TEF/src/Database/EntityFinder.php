<?php

namespace Database;

use Database\Finder;
use Model\Entity;

class EntityFinder implements Finder
{
	private $driver;
	private $entity;
	
	public function __construct($driver, Entity $entity)
	{
		$this->driver = $driver;
		$this->entity = $entity;
	}
	
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
	
	public function getEntityName()
	{
		return $this->entity->getName();
	}
	
	public function getEntityTable()
	{
		return $this->entity->toTableName($this->entity->getName());
	}
	
	public function getColumnId()
	{
		return $this->entity->getIdColumn();
	}
}
