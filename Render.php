<?php

class Render{

private $gig;
private $arrangement;

    function __construct() {
        $this->gig = New Gig();
        $this->arrangement = New Arrangement();
    }

function getFooter(){

$form = "<p>Any bugs, please let Owen know at a rehearsal, or create an issue at  <a href='https://github.com/owen-kellie-smith/chart2'>Github</a>.</p>";
$form .= "<p><a href='.'>Main menu</a></p>";
$form .= "<p><a href='.?action=logout'>Logout</a></p>";
$form .= "<p>If you want to print an A3 landscape pdf onto to 2 A4 portrait pages, here's one way that worked in June 2018. 
<ol><li>Split the pdf into single pages via <a href='https://www.splitpdf.com'>www.splitpdf.com</a> . Select 'Extract all pages to separate files'.  Splitpdf gives you a zip file which you download and extract on your computer.</li>
<li>Upload one of the separate A3 pages <a href='https://www.sejda.com/split-pdf-down-the-middle'>www.sejda.com</a>. Click on Upload pdf files, then when it's uploaded (took a minute on my machine) click on split vertically.  Sejda lets you split 3 times in an hour for free.</li></ol></p>";
$form .= "<div>
            <strong>Other links</strong>
          <p><a href='http://thornburyswingband.weebly.com/'>Thornbury Swing Band website</a></p>
            <p><a href='https://padlet.com/andyh/TSB_Tunes'>TSB_Tunes (Andy's padlet)</a></p>
          <p><a href='https://www.youtube.com/channel/UCX2K1BCZ6PR3AsjbFsi88og'>You tube (Thornbury Swing Band)</a></p>
          </div>";
return $form;
}

function getOutputLink( $filename ){
	$out = "";
	$out .= "<fieldset><legend>Your requested  charts</legend>";
	$out .= "<a href='" . $filename . "'>Download your pdf</a>";
	$out .= "</fieldset>";
	return $out;
	}


function getRequestForm( $arrangementID = -1, $gigID = -1, $input=array()){
	$out = "";
	if ($arrangementID > 0){
	    $out .= $this->arrangement->getArrangementForm($arrangementID);
	}
	$out .= $this->gig->getGigForm( $gigID, $input);
	$out .= "<fieldset><legend>Alphabetical order (* = not in pads)</legend>";
	$out .= $this->gig->getForm( $gigID);
	$out .= "</fieldset>";
	$out .= "<fieldset><legend>Get all titles and comments (no music)</legend>";
	$out .= $this->arrangement->getChartListForm();
	$out .= "</fieldset>";
	$out .= "<p><a href='maintenance/'>Maintenance</a></p>";
	$out .= "<p><a href='images/'>Images</a></p>";

	return $out;
	}



} // end class Render
