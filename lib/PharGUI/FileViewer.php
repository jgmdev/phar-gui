<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/jgmdev/phar-gui Source code.
*/

namespace PharGUI;

use wxColour;
use wxBoxSizer;
use wxStyledTextCtrl;
use PharViewerTemplate;

/**
 * Dialog to view the content of a file directly from a phar.
 */
class FileViewer extends PharViewerTemplate
{
    const MODE_PHP=1;

    /**
     * The code viewer.
     * @var wxStyledTextCtrl
     */
    public $editor;

    /**
     * Default constructor.
     * @param mixed $parent Owner of this dialog.
     */
    public function __construct($parent = null)
    {
        parent::__construct($parent);

        $mainSizer = new wxBoxSizer( wxVERTICAL );

		$this->editor = new wxStyledTextCtrl($this);
        $this->editor->SetMarginWidth (0, 50);
        $this->editor->StyleSetForeground (wxSTC_STYLE_LINENUMBER, new wxColour (75, 75, 75) );
        $this->editor->StyleSetBackground (wxSTC_STYLE_LINENUMBER, new wxColour (220, 220, 220));
        $this->editor->SetMarginType (0, wxSTC_MARGIN_NUMBER);

        $mainSizer->Add( $this->editor, 1, wxEXPAND | wxALL, 5 );

		$this->SetSizer( $mainSizer );
		$this->Layout();

		$mainSizer->Fit( $this );

		$this->Centre( wxBOTH );
    }

    /**
     * Set the hilighting mode of the text editor.
     * @param int $mode For now only FileViewer::MODE_PHP is supported.
     */
    public function SetMode($mode)
    {
        switch($mode)
        {
            case self::MODE_PHP:
                $this->editor->SetStyleBits(7);

                $this->editor->SetLexer(wxSTC_LEX_PHPSCRIPT | wxSTC_LEX_HTML);

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_DEFAULT,
                    new wxColour(0,0,0)
                );

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_HSTRING,
                    new wxColour(255,0,0)
                );

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_SIMPLESTRING,
                    new wxColour(255,0,0)
                );

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_WORD,
                    new wxColour(0,0,155)
                );

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_NUMBER,
                    new wxColour(0,150,0)
                );

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_VARIABLE,
                    new wxColour(0,0,150)
                );

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_COMMENT,
                    new wxColour(150,150,150)
                );

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_COMMENTLINE,
                    new wxColour(150,150,150)
                );

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_HSTRING_VARIABLE,
                    new wxColour(0,0,150)
                );

                $this->editor->StyleSetForeground(
                    wxSTC_HPHP_OPERATOR,
                    new wxColour(0,150,0)
                );

                break;
        }
    }

    /**
     * Add text to current editor and turns readonly on.
     * @param string $text
     */
    public function AddText($text)
    {

        $this->editor->SetReadOnly(false);

        $this->editor->ClearAll();
        $this->editor->AddText($text);

        $this->editor->SetReadOnly(true);
    }
}