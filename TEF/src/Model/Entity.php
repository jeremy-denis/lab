<?php
/*************************************
 * Title 			: 	Entity.php
 * Creation_date 	:	1/10/2013 
 * Author 			: 	Jeremy DENIS
 * Licence 			: 	apache2
 * Description 		: 	fbasic element of the framework
 *************************************/
namespace Model;

use Config;
use Database\EntityFinder;
use Database\Driver;

class Entity implements IEntity
{
	private $properties;
	private $idColumn;
	private $exclude;
	private $name;
	private $types;
	 
	public function __construct($entityname, $properties = array(), $exclude = array(),$idColumn = 'id',$types = array())
	{
		$this->idColumn = $idColumn;
		$this->name = $entityname;
		$this->types = $types;
		$this->exclude = $exclude;
		$this->properties = array();
		foreach ($properties as $name => $property)
		{
			if ($name != 'entityname')
				$this->properties[$name] = $property;
		}
	}
	
	public static function toTableName($word)
	{
		return strtoupper($word);
	}
	
	public static function toColumnName($word)
	{
		return strtolower($word);
	}
	
	public function getIdColumn()
	{
		return $this->idColumn;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function get($key)
	{
		return $this->properties[$key];
	}
	
	public function set($key, $value)
	{
		if (isset($this->properties[$key]))
			$this->properties[$key] = $value;
	}
	
	public function getDataArray()
	{
		return array_diff_key($this->properties, $this->exclude);
	}
	
	public function getAllDataArray()
	{
		return $this->properties;
	}
	
	public function getSubstring($string, $start, $end)
	{
		$first = explode($start,$string);
		$second = explode($end,$first[1]);
		if ($this->is_null_or_empty($second[0]))
			return null;
		return $second[0];
	}
	
	public function is_null_or_empty($string)
	{
		return is_null($string) || trim($string)=='';
	}

	public function startsWith($haystack, $needle)
	{
		return !strncmp($haystack, $needle, strlen($needle));
	}
	
	public function printEntity($displayed=null,$baseUri='../',$printLabel=1)
	{
		$printEntity = '<div>';
		foreach ($this->getDataArray() as $label => $property)
		{
			if ($displayed == null || in_array($label,$displayed))
			{
				if (array_key_exists($label,$this->types))
				{
					if ($this->types[$label] == 'file')
					$printEntity .= '<div class="'.$this->name.'_file"><img src="'.$baseUri.$property.'" alt="'.$label.'"/></div><br/>';
				}
				else
				{	
					if ($printLabel)
					{
						$printEntity .= $label;
						$printEntity .= ' : ';
					}
					$printEntity .= $property;
					$printEntity .= '<br/>';
				}
			}
		}
		$printEntity .= '</div>';
		return $printEntity;
	}
	
	public function getDataForm(Config $config, Driver $driver, $displayed=null)
	{
		$form = '';
		foreach ($this->getDataArray() as $label => $property)
		{
			if ($displayed == null || in_array($label,$displayed))
			{
				$form .= "$label : ";
				if ($this->startsWith($property,'@') && !$this->is_null_or_empty($property) && ($result = $this->getSubstring($property,'@','@')) != null)
				{
					$array = explode('.',$result);
					$entity = $config->loadEntity($array[0]);
					$finder = new EntityFinder($driver,$entity);
					$entities = $finder->findAll();
					$form .= "<select id=\"".$this->name."_$label\" name=\"".$this->name."_$label\">";
					$form .= "<option value=\"\"></option>";
					foreach ($entities as $key => $value)
					{
						$name = $value->get($array[1]);
						$id = $value->get($value->getIdColumn());
						$form .= "<option value=\"$id\">$name</option>";
					}
					$form .= "</select><br/>\n";
				}
				else
				{
					if (array_key_exists($label,$this->types))
					{
						$type = $this->types[$label];
					}
					else
					{
						$type = 'text';
					}
					$form .= "<input type=\"$type\" id=\"".$this->name."_$label\" name=\"".$this->name."_$label\" value=\"$property\"/><br/>\n";
				
				}
			}
		}
		return $form;
	}
	
	public function newInstance()
	{
		$properties = array();
		foreach($this->properties as $key => $value)
		{
			$properties[$key] = '';
		}
		$entity = new Entity($this->name, $properties, $this->exclude,$this->idColumn,$this->types);
		return $entity;
	}
}
