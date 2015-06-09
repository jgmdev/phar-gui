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

    if(Phar::running())
    {
        include('phar://phar-gui.phar/lib/'.$file);
    }
    else
    {
        include(__DIR__.'/'.$file);
    }
}

spl_autoload_register('phargui_autoloader');
