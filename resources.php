<?php

/*
* PHP code generated with wxFormBuilder (version Nov  5 2013)
* http://www.wxformbuilder.org/
*
* PLEASE DO "NOT" EDIT THIS FILE!
*/

/*
 * Class PharFrameTemplate
 */

class PharFrameTemplate extends wxFrame {
	
	function __construct( $parent=null ){
		parent::__construct ( $parent, wxID_ANY, "Phar GUI", wxDefaultPosition, new wxSize( 640,480 ), wxDEFAULT_FRAME_STYLE|wxTAB_TRAVERSAL );
		
		$this->SetSizeHints( wxDefaultSize, wxDefaultSize );
		
		$this->m_menubar = new wxMenuBar( 0 );
		$this->m_menuFile = new wxMenu();
		$this->m_menuItemNew = new wxMenuItem( $this->m_menuFile, wxID_NEW, "New". "\t" . "Ctrl-N", "Create a new phar file.", wxITEM_NORMAL );
		$this->m_menuFile->Append( $this->m_menuItemNew );
		
		$this->m_menuItemOpen = new wxMenuItem( $this->m_menuFile, wxID_OPEN, "Open". "\t" . "Ctrl-O", "Open an existing phar file.", wxITEM_NORMAL );
		$this->m_menuFile->Append( $this->m_menuItemOpen );
		
		$this->m_menuItemBuild = new wxMenuItem( $this->m_menuFile, wxID_ANY, "Build from directory". "\t" . "Ctrl-B", "Create a new phar file using the content of a given directory.", wxITEM_NORMAL );
		$this->m_menuFile->Append( $this->m_menuItemBuild );
		
		$this->m_menuFile->AppendSeparator();
		
		$this->m_menuItemReload = new wxMenuItem( $this->m_menuFile, wxID_REFRESH, "Reload". "\t" . "Ctrl-R", "Reload the content of the current phar file.", wxITEM_NORMAL );
		$this->m_menuFile->Append( $this->m_menuItemReload );
		
		$this->m_menuItemPreferences = new wxMenuItem( $this->m_menuFile, wxID_PREFERENCES, "Preferences". "\t" . "Ctrl-P", "Configure the current phar options.", wxITEM_NORMAL );
		$this->m_menuFile->Append( $this->m_menuItemPreferences );
		
		$this->m_menuItemExtract = new wxMenuItem( $this->m_menuFile, wxID_ANY, "Extract". "\t" . "Ctrl-E", "Extract a selected file from the phar.", wxITEM_NORMAL );
		$this->m_menuFile->Append( $this->m_menuItemExtract );
		
		$this->m_menuItemExtractAll = new wxMenuItem( $this->m_menuFile, wxID_ANY, "Extract All". "\t" . "Shift-Ctrl-E", "Extract all the files and directories on the phar.", wxITEM_NORMAL );
		$this->m_menuFile->Append( $this->m_menuItemExtractAll );
		
		$this->m_menuFile->AppendSeparator();
		
		$this->m_menuItemQuit = new wxMenuItem( $this->m_menuFile, wxID_CLOSE, "Quit". "\t" . "Alt-F4", wxEmptyString, wxITEM_NORMAL );
		$this->m_menuFile->Append( $this->m_menuItemQuit );
		
		$this->m_menubar->Append( $this->m_menuFile, "&File" ); 
		
		$this->m_menuEdit = new wxMenu();
		$this->m_menuItemAddDir = new wxMenuItem( $this->m_menuEdit, wxID_ADD, "Add Directory". "\t" . "Ctrl-D", "Add new empty directory to the phar.", wxITEM_NORMAL );
		$this->m_menuEdit->Append( $this->m_menuItemAddDir );
		
		$this->m_menuItemAddFile = new wxMenuItem( $this->m_menuEdit, wxID_FILE, "Add File". "\t" . "Ctrl-F", "Add a new file to the phar.", wxITEM_NORMAL );
		$this->m_menuEdit->Append( $this->m_menuItemAddFile );
		
		$this->m_menuItemDelete = new wxMenuItem( $this->m_menuEdit, wxID_DELETE, "Delete". "\t" . "Shift-Ctrl-D", "Delete a selected file from the phar.", wxITEM_NORMAL );
		$this->m_menuEdit->Append( $this->m_menuItemDelete );
		
		$this->m_menuItemStub = new wxMenuItem( $this->m_menuEdit, wxID_EXECUTE, "Stub". "\t" . "Ctrl-T", "PHP bootstrap code that is executed when invoking the phar file.", wxITEM_NORMAL );
		$this->m_menuEdit->Append( $this->m_menuItemStub );
		
		$this->m_menuItemAlias = new wxMenuItem( $this->m_menuEdit, wxID_ANY, "Alias". "\t" . "Ctrl-L", "Alias used internally to reference the phar file.", wxITEM_NORMAL );
		$this->m_menuEdit->Append( $this->m_menuItemAlias );
		
		$this->m_menubar->Append( $this->m_menuEdit, "&Edit" ); 
		
		$this->m_menuHelp = new wxMenu();
		$this->m_menuItemAbout = new wxMenuItem( $this->m_menuHelp, wxID_ABOUT, "About". "\t" . "F1", "Information about PharGUI", wxITEM_NORMAL );
		$this->m_menuHelp->Append( $this->m_menuItemAbout );
		
		$this->m_menubar->Append( $this->m_menuHelp, "&Help" ); 
		
		$this->SetMenuBar( $this->m_menubar );
		
		$this->m_statusBar = $this->CreateStatusBar( 1, wxST_SIZEGRIP, wxID_ANY );
		$mainSizer = new wxBoxSizer( wxVERTICAL );
		
		$this->m_treeCtrlFiles = new wxTreeCtrl( $this, wxID_ANY, wxDefaultPosition, wxDefaultSize, wxTR_DEFAULT_STYLE );
		$mainSizer->Add( $this->m_treeCtrlFiles, 1, wxALL|wxEXPAND|wxALIGN_CENTER_HORIZONTAL, 5 );
		
		
		$this->SetSizer( $mainSizer );
		$this->Layout();
		
		$this->Centre( wxBOTH );
		
		// Connect Events
		$this->Connect( $this->m_menuItemNew->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuNew") );
		$this->Connect( $this->m_menuItemOpen->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuOpen") );
		$this->Connect( $this->m_menuItemBuild->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuBuild") );
		$this->Connect( $this->m_menuItemReload->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuReload") );
		$this->Connect( $this->m_menuItemPreferences->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuPreferences") );
		$this->Connect( $this->m_menuItemExtract->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuExtract") );
		$this->Connect( $this->m_menuItemExtractAll->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuExtractAll") );
		$this->Connect( $this->m_menuItemQuit->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuQuit") );
		$this->Connect( $this->m_menuItemAddDir->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuAddDir") );
		$this->Connect( $this->m_menuItemAddFile->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuAddFile") );
		$this->Connect( $this->m_menuItemDelete->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuDelete") );
		$this->Connect( $this->m_menuItemStub->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuStub") );
		$this->Connect( $this->m_menuItemAlias->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuAlias") );
		$this->Connect( $this->m_menuItemAbout->GetId(), wxEVT_COMMAND_MENU_SELECTED, array($this, "OnMenuAbout") );
		$this->m_treeCtrlFiles->Connect( wxEVT_LEFT_DCLICK, array($this, "OnTreeLeftClick") );
		$this->m_treeCtrlFiles->Connect( wxEVT_COMMAND_TREE_ITEM_RIGHT_CLICK, array($this, "OnTreeRightClick") );
		$this->m_treeCtrlFiles->Connect( wxEVT_COMMAND_TREE_SEL_CHANGED, array($this, "OnTreeSelChanged") );
	}
	
	
	function __destruct( ){
	}
	
	
	// Virtual event handlers, overide them in your derived class
	function OnMenuNew( $event ){
		$event->Skip();
	}
	
	function OnMenuOpen( $event ){
		$event->Skip();
	}
	
	function OnMenuBuild( $event ){
		$event->Skip();
	}
	
	function OnMenuReload( $event ){
		$event->Skip();
	}
	
	function OnMenuPreferences( $event ){
		$event->Skip();
	}
	
	function OnMenuExtract( $event ){
		$event->Skip();
	}
	
	function OnMenuExtractAll( $event ){
		$event->Skip();
	}
	
	function OnMenuQuit( $event ){
		$event->Skip();
	}
	
	function OnMenuAddDir( $event ){
		$event->Skip();
	}
	
	function OnMenuAddFile( $event ){
		$event->Skip();
	}
	
	function OnMenuDelete( $event ){
		$event->Skip();
	}
	
	function OnMenuStub( $event ){
		$event->Skip();
	}
	
	function OnMenuAlias( $event ){
		$event->Skip();
	}
	
	function OnMenuAbout( $event ){
		$event->Skip();
	}
	
	function OnTreeLeftClick( $event ){
		$event->Skip();
	}
	
	function OnTreeRightClick( $event ){
		$event->Skip();
	}
	
	function OnTreeSelChanged( $event ){
		$event->Skip();
	}
	
}

/*
 * Class PharViewerTemplate
 */

class PharViewerTemplate extends wxDialog {
	
	function __construct( $parent=null ){
		parent::__construct( $parent, wxID_ANY, "File Viewer", wxDefaultPosition, wxDefaultSize, wxDEFAULT_DIALOG_STYLE );
		
		$this->SetSizeHints( new wxSize( 800,600 ), wxDefaultSize );
		
		
		$this->Centre( wxBOTH );
	}
	
	
	function __destruct( ){
	}
	
}

?>
