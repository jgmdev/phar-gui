<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phar-gui Source code.
 */

namespace PharGUI;

use Phar;

/**
 * Representation of a phar file currently loaded on the graphical interface.
 */
class File
{
    /**
     * Name of phar file.
     * @var string
     */
    public $name;

    /**
     * Full path to phar file.
     * @var string
     */
    public $path;

    /**
     * The phar file itself.
     * @var Phar
     */
    public $phar;

    /**
     * Loads a phar file.
     * @param type $path
     */
    public function Load($path = "")
    {
        unset($this->phar);

        if($path != "")
        {
            $this->name = basename($path);
            $this->path = $path;

            $this->phar = new Phar($path);
        }
        else
        {
            $this->phar = new Phar($this->path);
        }

        return true;
    }
}
