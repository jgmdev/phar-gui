# Phar GUI

A graphical user interface developed with [wxPHP](http://wxphp.org) to manage, 
extract and view the content of PHP phar files. 

The interface of the application was developed with wxFormBuilder and its 
source code can serve as an example of how to integrated graphical user 
interfaces designed with wxFormBuilder in your code.

## Features

* Create phar files
* View the content of a phar
* Extract all the content of a phar file
* Extract single files in the phar
* Add empty directories to a phar file
* Add external files to a phar
* Delete files from a phar
* Modify phar file stub
* Modify phar file alias
* View the code of php files inside the phar by double clicking them.

## Missing Features

* Edit compression options

## Installation

You will need the latest version of wxPHP which you can find at
http://github.com/wxphp/wxphp

Download this source files to a directory on your machine and execute:

    php phar-gui/main.php

## Screenshots

Main Window:

![main window](https://raw.github.com/jgmdev/phar-gui/master/screenshots/ss01.png)

Context Menu:

![context menu](https://raw.github.com/jgmdev/phar-gui/master/screenshots/ss02.png)

Editing Options:

![editing options](https://raw.github.com/jgmdev/phar-gui/master/screenshots/ss03.png)

Stub Editing:

![stubg editing](https://raw.github.com/jgmdev/phar-gui/master/screenshots/ss04.png)

Alias Editing:

![alias editing](https://raw.github.com/jgmdev/phar-gui/master/screenshots/ss05.png)