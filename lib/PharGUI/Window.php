<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phar-gui Source code.
 */

namespace PharGUI;

use wxMenu;
use wxIcon;
use wxBitmap;
use wxTreeCtrl;
use wxTreeEvent;
use wxImageList;
use wxDirDialog;
use wxFileDialog;
use wxTreeItemId;
use PharFileInfo;
use RecursiveDirectoryIterator;
use wxAboutDialogInfo;
use wxTextEntryDialog;
use PharFrameTemplate;

class Window extends PharFrameTemplate
{
    /**
     * Holds the name and path of current phar file.
     * @var File
     */
    public $phar_file;

    /**
     * A reference to the tree control
     * @var wxTreeCtrl
     */
    public $tree_ctrl;

    /**
     * The current root
     * @var wxTreeItemId
     */
    public $tree_root;

    /**
     * The current active item.
     * @var wxTreeItemId
     */
    public $tree_current_item;

    /**
     * Default constructor.
     * @param mixed $parent
     */
    public function __construct($parent = null)
    {
        parent::__construct($parent);

        $this->SetIcon(new wxIcon("images/icon.png", wxBITMAP_TYPE_PNG));

        $this->phar_file = new File;

        $this->tree_ctrl = &$this->m_treeCtrlFiles;

        $imagelist = new wxImageList(24, 24);
        $imagelist->Add(new wxBitmap("images/folder.png", wxBITMAP_TYPE_PNG));
        $imagelist->Add(new wxBitmap("images/file.png", wxBITMAP_TYPE_PNG));

        $this->tree_ctrl->SetImageList($imagelist);

        $this->m_menuItemReload->Enable(false);
        $this->m_menuItemPreferences->Enable(false);
        $this->m_menuItemExtract->Enable(false);
        $this->m_menuItemExtractAll->Enable(false);
        $this->m_menuItemAddDir->Enable(false);
        $this->m_menuItemAddFile->Enable(false);
        $this->m_menuItemDelete->Enable(false);
        $this->m_menuItemStub->Enable(false);
        $this->m_menuItemAlias->Enable(false);
    }

    /**
     * List all files and folders of the currently opened Phar file on the
     * tree_ctrl.
     */
    public function ListFiles()
    {
        $phar = &$this->phar_file->phar;

        $this->tree_ctrl->DeleteAllItems();
        $this->tree_root = $this->tree_ctrl->AddRoot($this->phar_file->name);

        foreach($phar as $file)
        {
            /* @var $file PharFileInfo */

            if($file->isDir())
            {
                $currentitem = $this->tree_ctrl->AppendItem(
                    $this->tree_root,
                    $file->getFilename(),
                    0,
                    -1,
                    new TreeData($file)
                );

                $this->ListFolderContent($currentitem, $phar);
            }
            else
            {
                $this->tree_ctrl->AppendItem(
                    $this->tree_root,
                    $file->getFilename(),
                    1,
                    -1,
                    new TreeData($file)
                );
            }
        }

        $this->tree_ctrl->Expand($this->tree_root);
    }

    /**
     * Recursive method to list all the subitems of a directory inside
     * a phar file.
     * @param wxTreeItemId $parentitem
     * @param RecursiveDirectoryIterator $iterator
     */
    public function ListFolderContent(wxTreeItemId $parentitem, $iterator)
    {
        $directoryinterator = $iterator->getChildren();

        foreach($directoryinterator as $file)
        {
            /* @var $file PharFileInfo */

            if($file->isDir())
            {
                $currentitem = $this->tree_ctrl->AppendItem(
                    $parentitem,
                    $file->getFilename(),
                    0,
                    -1,
                    new TreeData($file)
                );

                $this->ListFolderContent($currentitem, $directoryinterator);
            }
            else
            {
                $this->tree_ctrl->AppendItem(
                    $parentitem,
                    $file->getFilename(),
                    1,
                    -1,
                    new TreeData($file)
                );
            }
        }
    }

    public function OnMenuNew($event)
    {
        $dialog = new wxFileDialog(
            $this,
            "Select a phar file name",
            "",
            "",
            "PHP Phar file|*.phar",
            wxFD_SAVE
        );

        if($dialog->ShowModal() == wxID_CANCEL)
        {
            $dialog->Destroy();
            return;
        }

        $path = $dialog->GetPath();

        $dialog->Destroy();

        if(stripos($dialog->GetPath(), ".phar") === false)
        {
            $path .= ".phar";
        }

        $this->phar_file->Load($path);

        $this->ListFiles();

        $title = $this->phar_file->name;

        $this->m_menuItemReload->Enable();
        $this->m_menuItemPreferences->Enable();
        $this->m_menuItemExtract->Enable();
        $this->m_menuItemExtractAll->Enable();

        if(!$this->phar_file->phar->canWrite())
        {
            $title .= " (read only)";

            $this->m_menuItemAddDir->Enable(false);
            $this->m_menuItemAddFile->Enable(false);
            $this->m_menuItemDelete->Enable(false);
            $this->m_menuItemStub->Enable(false);
            $this->m_menuItemAlias->Enable(false);

            wxMessageBox(
                "In order to modify phar files you need to set "
                . "phar.readonly to 0 on your php.ini", "Warning"
            );
        }
        else
        {
            $this->m_menuItemAddDir->Enable();
            $this->m_menuItemAddFile->Enable();
            $this->m_menuItemDelete->Enable();
            $this->m_menuItemStub->Enable();
            $this->m_menuItemAlias->Enable();
        }

        $this->SetTitle($title . " - Phar GUI");
    }

    function OnMenuOpen($event)
    {
        $dialog = new wxFileDialog(
            $this,
            "Select a phar file to load",
            "",
            "",
            "PHP Phar file|*.phar"
        );

        if($dialog->ShowModal() == wxID_CANCEL)
        {
            $dialog->Destroy();
            return;
        }

        $this->phar_file->Load($dialog->GetPath());

        $this->ListFiles();

        $title = $this->phar_file->name;

        $this->m_menuItemReload->Enable();
        $this->m_menuItemPreferences->Enable();
        $this->m_menuItemExtract->Enable();
        $this->m_menuItemExtractAll->Enable();

        if(!$this->phar_file->phar->canWrite())
        {
            $title .= " (read only)";

            $this->m_menuItemAddDir->Enable(false);
            $this->m_menuItemAddFile->Enable(false);
            $this->m_menuItemDelete->Enable(false);
            $this->m_menuItemStub->Enable(false);
            $this->m_menuItemAlias->Enable(false);

            wxMessageBox(
                "In order to modify phar files you need to set "
                . "phar.readonly to 0 on your php.ini", "Warning"
            );
        }
        else
        {
            $this->m_menuItemAddDir->Enable();
            $this->m_menuItemAddFile->Enable();
            $this->m_menuItemDelete->Enable();
            $this->m_menuItemStub->Enable();
            $this->m_menuItemAlias->Enable();
        }

        $this->SetTitle($title . " - Phar GUI");

        $dialog->Destroy();
    }

    function OnMenuBuild($event)
    {
        $dir_dialog = new wxDirDialog(
            $this,
            "Select the directory that contains all the files that are "
            . "going to be added into the new phar file."
        );

        if($dir_dialog->ShowModal() == wxID_CANCEL)
        {
            $dir_dialog->Destroy();
            return;
        }

        $input = $dir_dialog->GetPath();

        $dir_dialog->Destroy();

        $dialog = new wxFileDialog(
            $this,
            "Select where to save the new phar file.",
            "",
            "",
            "PHP Phar file|*.phar",
            wxFD_SAVE
        );

        if($dialog->ShowModal() == wxID_CANCEL)
        {
            $dialog->Destroy();
            return;
        }

        $phar_file_path = $dialog->GetPath();

        if(stripos($dialog->GetPath(), ".phar") === false)
        {
            $phar_file_path .= ".phar";
        }

        $dialog->Destroy();

        $this->phar_file->Load($phar_file_path);

        $this->phar_file->phar->buildFromDirectory($input . "/");

        $this->phar_file->Load($phar_file_path);

        $this->ListFiles();

        $title = $this->phar_file->name;

        $this->m_menuItemReload->Enable();
        $this->m_menuItemPreferences->Enable();
        $this->m_menuItemExtract->Enable();
        $this->m_menuItemExtractAll->Enable();

        if(!$this->phar_file->phar->canWrite())
        {
            $title .= " (read only)";

            $this->m_menuItemAddDir->Enable(false);
            $this->m_menuItemAddFile->Enable(false);
            $this->m_menuItemDelete->Enable(false);
            $this->m_menuItemStub->Enable(false);
            $this->m_menuItemAlias->Enable(false);

            wxMessageBox(
                "In order to modify phar files you need to set "
                . "phar.readonly to 0 on your php.ini", "Warning"
            );
        }
        else
        {
            $this->m_menuItemAddDir->Enable();
            $this->m_menuItemAddFile->Enable();
            $this->m_menuItemDelete->Enable();
            $this->m_menuItemStub->Enable();
            $this->m_menuItemAlias->Enable();
        }

        $this->SetTitle($title . " - Phar GUI");

        $dialog->Destroy();
    }

    function OnMenuReload($event)
    {
        $this->phar_file->Load();
        $this->ListFiles();
    }

    public function OnMenuPreferences($event)
    {
        wxMessageBox("Not implemented yet");

        $event->Skip();
    }

    function OnMenuExtract($event)
    {
        $this->OnExtractClick($event);
    }

    function OnMenuExtractAll($event)
    {
        $dirDialog = new wxDirDialog(
            $this,
            "Select the extract destination",
            dirname($this->phar_file->path)
        );

        if($dirDialog->ShowModal() == wxID_CANCEL)
        {
            $dirDialog->Destroy();
            return;
        }

        $path = $dirDialog->GetPath();

        $dirDialog->Destroy();

        $this->phar_file->phar->extractTo($path);
    }

    public function OnMenuQuit($event)
    {
        $this->Destroy();
    }

    public function OnMenuAddDir($event)
    {
        $dirDialog = new wxTextEntryDialog(
            $this,
            "Enter a valid directory name.",
            "Add Empty Directory"
        );

        if($dirDialog->ShowModal() == wxID_CANCEL)
        {
            $dirDialog->Destroy();
            return;
        }

        $path = $dirDialog->GetValue();

        $dirDialog->Destroy();

        $itemdata = $this->tree_ctrl->GetItemData(
            $this->tree_ctrl->GetSelection()
        );

        /* @var $itemdata TreeData */

        if(isset($itemdata->fileinfo))
        {
            if($itemdata->fileinfo->isDir())
            {
                $element = explode(
                    $this->phar_file->name . "/",
                    $itemdata->fileinfo->getPathname()
                )[1] . "/" . $path;

                $this->phar_file->phar->addEmptyDir(
                    $element
                );

                $this->tree_ctrl->AppendItem(
                    $this->tree_ctrl->GetSelection(),
                    $path,
                    0,
                    -1,
                    new TreeData($this->phar_file->phar[$element])
                );
            }
            else
            {
                $element = explode(
                    $this->phar_file->name . "/",
                    $itemdata->fileinfo->getPath()
                );

                $current_item = $this->tree_ctrl->GetSelection();

                if(count($element) > 1)
                {
                    $element = $element[1] . "/" . $path;
                }
                else
                {
                    $element = $path;
                    $current_item = $this->tree_root;
                }

                $this->phar_file->phar->addEmptyDir(
                    $element
                );

                $this->tree_ctrl->AppendItem(
                    $current_item,
                    $path,
                    0,
                    -1,
                    new TreeData($this->phar_file->phar[$element])
                );
            }
        }
        else
        {
            $this->phar_file->phar->addEmptyDir($path);

            $this->tree_ctrl->AppendItem(
                $this->tree_root,
                $path,
                0,
                -1,
                new TreeData($this->phar_file->phar[$path])
            );
        }
    }

    public function OnMenuAddFile($event)
    {
        $fileDialog = new wxFileDialog(
            $this, "Select the file to add", "Add File"
        );

        if($fileDialog->ShowModal() == wxID_CANCEL)
        {
            $fileDialog->Destroy();
            return;
        }

        $path = $fileDialog->GetPath();
        $name = $fileDialog->GetFilename();

        $fileDialog->Destroy();

        $itemdata = $this->tree_ctrl->GetItemData(
            $this->tree_ctrl->GetSelection()
        );

        /* @var $itemdata TreeData */

        if(isset($itemdata->fileinfo))
        {
            if($itemdata->fileinfo->isDir())
            {
                $element = explode(
                    $this->phar_file->name . "/",
                    $itemdata->fileinfo->getPathname()
                )[1] . "/" . $name;

                $this->phar_file->phar->addFile(
                    $path, $element
                );

                $this->tree_ctrl->AppendItem(
                    $this->tree_ctrl->GetSelection(),
                    $name,
                    1,
                    -1,
                    new TreeData($this->phar_file->phar[$element])
                );
            }
            else
            {
                $element = explode(
                    $this->phar_file->name . "/",
                    $itemdata->fileinfo->getPath()
                );

                $current_item = $this->tree_ctrl->GetItemParent(
                    $this->tree_ctrl->GetSelection()
                );

                if(count($element) > 1)
                {
                    $element = $element[1] . "/" . $name;
                }
                else
                {
                    $element = $name;
                    $current_item = $this->tree_root;
                }

                $this->phar_file->phar->addFile(
                    $path, $element
                );

                $this->tree_ctrl->AppendItem(
                    $current_item,
                    $name,
                    1,
                    -1,
                    new TreeData($this->phar_file->phar[$element])
                );
            }
        }
        else
        {
            $this->phar_file->phar->addFile($path, $name);

            $this->tree_ctrl->AppendItem(
                $this->tree_root,
                $name,
                1,
                -1,
                new TreeData($this->phar_file->phar[$name])
            );
        }
    }

    public function OnMenuDelete($event)
    {
        $itemdata = $this->tree_ctrl->GetItemData(
            $this->tree_ctrl->GetSelection()
        );

        if(!isset($itemdata->fileinfo))
        {
            return;
        }

        /* @var $itemdata TreeData */

        if(!$itemdata->fileinfo->isDir())
        {
            $element = explode(
                $this->phar_file->name . "/",
                $itemdata->fileinfo->getPathname()
            )[1];

            if($this->phar_file->phar->delete($element))
            {
                $this->tree_ctrl->Delete(
                    $this->tree_ctrl->GetSelection()
                );
            }
        }
    }

    function OnMenuStub($event)
    {
        $editor = new FileViewer($this);

        $stub = "";

        try
        {
            $stub .= $this->phar_file->phar->getStub();
        }
        catch(Exception $ex)
        {
            $stub = "#!/usr/bin/php\n"
                . "<?php\n\n"
                . "//Insert your code here\n\n"
                . "__HALT_COMPILER();\n"
                . "?>\n"
            ;
        }

        $stub_md5 = md5($stub);

        $editor->AddText($stub);

        $editor->editor->SetReadOnly(false);

        $editor->SetMode(FileViewer::MODE_PHP);

        $title = $this->phar_file->name . " stub";

        if(!$this->phar_file->phar->canWrite())
        {
            $title .= " (read only)";
        }

        $editor->SetTitle($title);

        $editor->ShowModal();

        if($this->phar_file->phar->canWrite())
        {
            $new_stub_md5 = md5($editor->editor->GetText());

            if($stub_md5 != $new_stub_md5)
            {
                $result = wxMessageBox(
                    "Do you want to save the changes?",
                    "Stub Changes",
                    wxYES_NO,
                    $this
                );

                if($result == wxYES)
                {
                    try
                    {
                        $this->phar_file->phar->setStub(
                            $editor->editor->GetText()
                        );
                    }
                    catch(Exception $ex)
                    {
                        wxMessageBox($ex->getMessage());
                    }
                }
            }
        }
    }

    function OnMenuAlias($event)
    {
        $dialog = new wxTextEntryDialog(
            $this,
            "Enter a valid alias for the file, eg: myphar-1.0.phar\n\n"
            . "This alias can be used when referring to the file using\n"
            . "the phar stream syntax, for example: \n\n"
            . "phar://myphar-1.0.phar/somefile.php", "Phar Alias"
        );

        $dialog->SetValue($this->phar_file->phar->getAlias());

        if($dialog->ShowModal() == wxID_CANCEL)
        {
            $dialog->Destroy();
            return;
        }

        $alias = $dialog->GetValue();

        $dialog->Destroy();

        $this->phar_file->phar->setAlias($alias);
    }

    public function OnMenuAbout($event)
    {
        $aboutinfo = new wxAboutDialogInfo();

        $aboutinfo->SetDescription(
            "Graphical user interface to create and manage PHP phar files. "
            . "This application was developed with wxPHP (http://wxphp.org)"
        );

        $aboutinfo->SetIcon(new wxIcon("images/logo.png", wxBITMAP_TYPE_PNG));

        $aboutinfo->SetVersion("1.0");

        $aboutinfo->SetLicence(file_get_contents("LICENSE.txt"));

        $aboutinfo->SetWebSite("http://github.com/jgmdev/phar-gui");

        $aboutinfo->AddDeveloper("Jefferson González <jgmdev@gmail.com>");

        $aboutinfo->AddArtist("Logo - Yaritza Luyando <yluyando@gmail.com>");

        wxAboutBox($aboutinfo);
    }

    function OnTreeSelChanged(wxTreeEvent $event)
    {
        $event->Skip();
    }

    function OnTreeLeftClick($event)
    {
        $itemdata = $this->tree_ctrl->GetItemData(
            $this->tree_ctrl->GetSelection()
        );

        /* @var $itemdata TreeData */

        if(!isset($itemdata->fileinfo))
            return;

        if($itemdata->fileinfo->isDir())
        {
            $this->tree_ctrl->Toggle($this->tree_ctrl->GetSelection());
        }
        else
        {
            $editor = new FileViewer($this);

            $editor->AddText($itemdata->fileinfo->getContent());

            $filenameparts = explode(".", $itemdata->fileinfo->getFilename());

            if(strtolower($filenameparts[count($filenameparts) - 1]) == "php")
            {
                $editor->SetMode(FileViewer::MODE_PHP);
            }

            $editor->Maximize(true);

            $editor->ShowModal();

            $editor->Destroy();
        }
    }

    function OnTreeRightClick($event)
    {
        $itemdata = $this->tree_ctrl->GetItemData(
            $this->tree_ctrl->GetSelection()
        );

        /* @var $itemdata TreeData */

        $menu = new wxMenu();

        $menu->Append(1, "Extract All");
        $menu->Connect(
            1, wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuExtractAll")
        );

        $menu->Append(2, "Extract");
        $menu->Connect(
            2, wxEVT_COMMAND_MENU_SELECTED, array($this, "OnExtractClick")
        );

        $menu->Append(3, "Add Directory");
        $menu->Connect(
            3, wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuAddDir")
        );

        $menu->Append(4, "Add File");
        $menu->Connect(
            4, wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuAddFile")
        );

        if(
            isset($itemdata->fileinfo) &&
            $this->phar_file->phar->canWrite() &&
            !$itemdata->fileinfo->isDir()
        )
        {
            $menu->Append(5, "Delete");

            $menu->Connect(
                5, wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuDelete")
            );
        }

        $this->tree_ctrl->PopupMenu($menu);
    }

    function OnExtractClick($event)
    {
        $dirDialog = new wxDirDialog(
            $this,
            "Select the extract destination",
            dirname($this->phar_file->path)
        );

        if($dirDialog->ShowModal() == wxID_CANCEL)
        {
            $dirDialog->Destroy();
            return;
        }

        $path = $dirDialog->GetPath();

        $dirDialog->Destroy();

        $itemdata = $this->tree_ctrl->GetItemData(
            $this->tree_ctrl->GetSelection()
        );

        /* @var $itemdata TreeData */

        if(!isset($itemdata->fileinfo))
        {
            $this->phar_file->phar->extractTo($path);
        }
        else
        {
            if($itemdata->fileinfo->isDir())
            {
                $element = explode(
                    $this->phar_file->name . "/",
                    $itemdata->fileinfo->getPathname()
                )[1] . "/";

                //Bug #54289 Phar::extractTo() does not accept specific
                //directories to be extracted. We need to implement a
                //recursive function that iterates the directory and does
                //manual extraction.

                return;

                $this->phar_file->phar->extractTo($path, $element);
            }
            else
            {
                file_put_contents(
                    $path . "/" . $itemdata->fileinfo->getFilename(),
                    $itemdata->fileinfo->getContent()
                );
            }
        }
    }

}
