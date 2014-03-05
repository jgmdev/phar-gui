<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phar-gui Source code.
 */

// Register class auto-loader
function phargui_autoloader($class_name)
{
    $file = str_replace('\\', '/', $class_name) . '.php';

    include('lib/'.$file);
}

spl_autoload_register('phargui_autoloader');
