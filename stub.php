#!/usr/bin/php -d phar.readonly=0
<?php
/**
 * The stub code for phar-gui.phar
 *
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phar-gui Source code.
*/

// Include files
include("phar://phar-gui.phar/resources.php");
include("phar://phar-gui.phar/lib/autoload.php");

wxInitAllImageHandlers();

// Application initialization
$phargui = new PharGUI\Window();

$phargui->Show();

wxEntry();

__HALT_COMPILER(); ?>
