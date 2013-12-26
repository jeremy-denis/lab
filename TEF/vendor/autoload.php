<?php

/*function : autoloader
 * @brief Autoloader to load class in the good path of the framework
 * @param the name of the class to load with her different namespace
 * @return true if the load of the class have succeed
 */
function autoloader($class) {
	
	$namespace = str_replace('\\','/',$class);
	$namespace .= '.php';
	foreach(array('src', 'test', 'app/config') as $dir) {
		$filename = __DIR__.'/../'.$dir.'/'.$namespace;
		//echo $filename.'<br/>';
		if (file_exists($filename))
		{
			include $filename;
			return true;
		}
	}
}

//we sign in the autoloader
spl_autoload_register('autoloader');
