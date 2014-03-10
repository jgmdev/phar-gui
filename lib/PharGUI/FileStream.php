<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phar-gui Source code.
 */

namespace PharGUI;

use Phar;
use wxInputStream;

/**
 * Implements wxInputStream in order to load images from a phar file into
 * wxphp.
 */
class FileStream extends wxInputStream
{
    private $image;
    
    public function __construct($image_path)
    {
        parent::__construct();
        
        if(Phar::running())
        {
            $image_path = "phar://phar-gui.phar/$image_path";
        }
        
        $this->image = fopen($image_path, "rb");
    }
    
    public function __destruct()
    {
        fclose($this->image);
    }
    
    public function OnSysRead(&$buffer, $bufsize)
    {
        if(!feof($this->image))
        {
            $buffer = fread($this->image, $bufsize);
        }
        else
        {
            return 0;
        }
        
        return strlen($buffer);
    }
}
