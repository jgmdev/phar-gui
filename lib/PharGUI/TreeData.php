<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phar-gui Source code.
*/

namespace PharGUI;

use PharFileInfo;
use wxTreeItemData;

/**
 * Used to associate the wxTreeCtrl items to the PharFileInfo object of each
 * element on the phar file.
 */
class TreeData extends wxTreeItemData
{
    /**
     * Reference to the phar file info object.
     * @var PharFileInfo
     */
    public $fileinfo;

    /**
     * Default constructor.
     * @param PharFileInfo $fileinfo
     */
    public function __construct(PharFileInfo $fileinfo)
    {
        parent::__construct();

        $this->fileinfo = $fileinfo;
    }
}