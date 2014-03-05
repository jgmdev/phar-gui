<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phargui Source code.
*/

// Register class auto-loader
function cms_autoloader($class_name)
{
	$file = str_replace('\\', '/', $class_name) . '.php';

    print 'lib/'.$file;
    
	include('lib/'.$file);
}

spl_autoload_register('cms_autoloader');
?>
