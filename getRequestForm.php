<?php 
function getRequestForm( $arrangementID = -1){
	$out = "";
//	$out .= "<p><a href='" . listAllLink() . "'>Get all titles and comments (no music)</a></p>";
	$out .= "<fieldset><legend>Get all titles and comments (no music)</legend>";
	$out .= getChartListForm();
	$out .= "</fieldset>";
	$out .= "<fieldset><legend>(Draft) set List order</legend>";
	$out .= getGigForm();
	$out .= "</fieldset>";
	if ($arrangementID > 0){
	    $out .= getArrangementForm($arrangementID);
	}
	$out .= "<fieldset><legend>Alphabetical order (* = not in pads)</legend>";
	$out .= getForm();
	$out .= "</fieldset>";
	include_once "showRequests.php";
//	$out .= "<p>" . countRequests() . "</p>";
	$out .= "<p><a href='maintenance/'>Maintenance</a></p>";

	return $out;
	}
