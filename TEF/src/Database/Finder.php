<?php
/*************************************
 * Title 			: 	Finder.php
 * Creation_date 	:	1/10/2013 
 * Author 			: 	Jeremy DENIS
 * Licence 			: 	php
 * Description 		: 	Interface that get the entities in the database
 *************************************/
namespace Database;

interface Finder
{
	/**function : findOneByID
	 * @return one entity find by his id
	 * @param $id the id of the entity to get
	 */
	public function findOneById($id);
	
	/**function : findAll
	 * function to get all the entity in the database
	 * @return a set of all th entity
	 * @param $orderBy : the column use to order the set
	 * @param $criteria : a key value array that match the result return
	 * @param $revert : to choose if the order of the set is acendent or descendent
	 * @param $where : the where clause of the request 
	 */
	public function findAll($orderBy = null,$criteria=null,$revert=0,$where = null);
	
	/**function : getEntityTable
	 * function to get the table from the database where the entity is recover
	 * @return the name of the table where the entity is recover
	 */
	public function getEntityTable();
	
	/**function : getIdColumn
	 * function to get the name of the column where are saved the id of the entity in the database
	 * @return the name of the id column of the entity
	 */
	public function getColumnId();
	
	/**function : getEntityName
	 * function to get the name of the entity that match the finder 
	 * @return the name of the entity table where the data are extract 
	 */
	public function getEntityName();
}
