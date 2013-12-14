<?php
/*************************************
 * Title 			: 	EntityDao.php
 * Creation_date 	:	1/10/2013 
 * Author 			: 	Jeremy DENIS
 * Licence 			: 	apache2
 * Description 		: 	file to save and delete data
 *************************************/
use Database\Finder;
use Model\Entity;

namespace Database;

use Model\Extract\DataFormat;
use Model\File;
use UploadDownload\Download;

class EntityDao
{
	private $driver;
	
	public function __construct($driver)
	{
		$this->driver = $driver;
	}
	
	public function save($entity)
	{
		$id = -1;
		if ($entity->get($entity->getIdColumn()) != null)
		{
			$id = $entity->get($entity->getIdColumn());
		}
		else
		{
			$id = count($this->driver->findall(\Model\Entity::toTableName($entity->getName())))+1;
		}
		$array = $entity->getAllDataArray();
		foreach ($array as $key => $data)
		{
			if (is_string($data))
			{
				$array[$key] = '\''.$data.'\'';
			}
			else
					$array[$key] = $data;
		}
		$this->driver->save($array,\Model\Entity::toTableName($entity->getName()),$id,$entity->getIdColumn()); 
	}	
	
	public function delete()
	{
	}
	
	public static function getLine($dataLine,$finder,$printId,$write = 0)
	{
		$table = $finder->getEntityTable();
		$column = $finder->getColumnId();
		$array = "<tr><form action=\"saveentity?entity=$table&id=$column\" method=\"post\">";
		foreach ($dataLine as $key => $value)
		{
			if ($key != $column || $printId)
			{
				if ($write)
				{
					$array .= "<td><input name=\"$key\" type=\"text\" value=\"$value\"/></td>";
				}
				else
					$array .= "<td>$value</td>";
				}
		}
		$array .= '<td>';
		$array .= '<a href="'."deleteentity?id=$dataLine[$column]&columnname=$column&table=$table".'"><input type="button" value="Supprimer"/></a>';
		$array .= '</td><td>';
		if ($write)
			$array .= '<input type="submit" value="Sauvegarder"/>';
		$array .= '</td>';
		$array .= '</form></tr>';
		return $array;
	}
	
	public static function getHeader($data,$printId)
	{
		$array = '<tr>';
		foreach ($data as $key => $value)
		{
			if ($key != 'id' || $printId)
				$array .= "<th>$key</th>";
		}
		$array .= '</tr>';
		return $array;
	}
	
	public static function getTab(Finder $finder, $printId = 0,$write = 0,$orderBy = null,$criteria=null,$revert=0,$download=1)
	{
		$array = '<table>';
		$datas = $finder->findAll($orderBy,$criteria,$revert);
		foreach ($datas as $data)
		{
			$array .= EntityDao::getHeader($data->getDataArray(),$printId);
			break;
		}
		foreach ($datas as $data)
		{
			$array .= EntityDao::getLine($data->getDataArray(),$finder,$printId,$write);
		}
		$array .= '</table>';
		if ($download)
		{
			$array .= '<form action="downloadData" method="post">';
			$text = '';
			foreach ($datas as $data)
			{
				$data = DataFormat::export($data);
				$text .= $data;
			}
			$array .= '<input type="text" name="data" value="'.$text.'" style="display: none;"/>';
			$array .= '<input type="submit" value="Télécharger"/>';
			$array .= '</form>';
		}
		return $array;
	}
	
	public static function loadUrl($app, $comonData)
	{
		$app = $app->newRoad('saveentity',function() use($comonData,$app){
			if (isset($_SESSION['admin']))
			{
				if (isset($_GET['id']) && isset($_GET['entity']))
				{
					$entity = new \Model\Entity($_GET['entity'],$_POST,null,$_GET['id']);
					$entitydao = new EntityDao($comonData['driver']);
					$entitydao->save($entity);
				}
			}
			return $app->redirect($comonData['precuri']);
		}
		);
		
		$app = $app->newRoad('deleteentity',function() use($comonData,$app){
			if(isset($_SESSION['admin']))
			{
				if (isset($_GET['id']) && isset($_GET['table']) && isset($_GET['columnname']))
				{
					$comonData['driver']->delete($_GET['id'],$_GET['columnname'],$_GET['table']);
				}
			}
			return $app->redirect($comonData['precuri']);
		}
		);
		
		$app = $app->newRoad('downloadData',function() use($comonData,$app){
			
			if(isset($_POST['data']))
			{
				$filepath = '/tmp/extract.don';
				$text = $_POST['data'];
				$file = new File($filepath);
				$file->createFile();
				$file->fillFile($text);
				Download::downloadFile($filepath);
				$file->removeFile();
			}
			return $app->redirect($comonData['precuri']);
		}
		);
		
		return $app;
	}
	
}
