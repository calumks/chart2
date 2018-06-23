<?php 
function getRequestForm( $arrangementID = -1, $gigID = -1){
	$out = "";
	if ($arrangementID > 0){
	    $out .= getArrangementForm($arrangementID);
	}
	$out .= getGigForm( $gigID);
	$out .= "<fieldset><legend>Alphabetical order (* = not in pads)</legend>";
	$out .= getForm( $gigID);
	$out .= "</fieldset>";
	$out .= "<fieldset><legend>Get all titles and comments (no music)</legend>";
	$out .= getChartListForm();
	$out .= "</fieldset>";
	$out .= "<p><a href='maintenance/'>Maintenance</a></p>";

	return $out;
	}
