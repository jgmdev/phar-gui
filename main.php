#!/usr/bin/php
<?php
/**
 * Graphical user interface to create and manage PHP phar files
 * developed with wxPHP (http://wxphp.org).
 *
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phar-gui Source code.
*/

$load_parameters = "";

//Try to load required libraries
if(!extension_loaded('wxwidgets'))
{
    $load_parameters .= "-d extension=wxwidgets." . PHP_SHLIB_SUFFIX . " ";
}

if(!extension_loaded('phar'))
{
    $load_parameters .= "-d extension=phar." . PHP_SHLIB_SUFFIX . " ";
}

if(ini_get("phar.readonly") == 1)
{
    $load_parameters .= "-d phar.readonly=0 ";
}

// If not on windows and phar.readonly is set to 1 we reload PHP CLI with
// phar.readonly set to 0 so writing and creating phar files is possible.
if(stripos(PHP_OS, "win") === false && $load_parameters != "")
{
    shell_exec($_SERVER["_"] . " $load_parameters " . __FILE__ . " > /dev/null &");
    exit;
}

// Directory with lib, images and License
define("PHARGUI_HOME", __DIR__);

// Include files
include(PHARGUI_HOME . "/resources.php");
include(PHARGUI_HOME . "/lib/autoload.php");

wxInitAllImageHandlers();

// Application initialization
$phargui = new PharGUI\Window();

$phargui->Show();

wxEntry();
