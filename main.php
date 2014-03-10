#!/usr/bin/php -d phar.readonly=0
<?php
/**
 * Graphical user interface to create and manage PHP phar files
 * developed with wxPHP (http://wxphp.org).
 *
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phar-gui Source code.
*/

// Change to the directory that holds phargui
// source files for correct initialization.
chdir(__DIR__);

// If not on windows and phar.readonly is set to 1 we reload PHP CLI with
// phar.readonly set to 0 so writing and creating phar files is possible.
if(stripos(PHP_OS, "win") === false)
{
    if(ini_get("phar.readonly") == 1)
    {
        shell_exec("php -d phar.readonly=0 " . __FILE__);
        exit;
    }
}

// Include files
include("resources.php");
include("lib/autoload.php");

wxInitAllImageHandlers();

// Application initialization
$phargui = new PharGUI\Window();

$phargui->Show();

wxEntry();