<?php 
function getOutputLink( $filename ){
	$out = "";
	$out .= "<fieldset><legend>Your requested  charts</legend>";
	$out .= "<a href='" . $filename . "'>Download your pdf</a>";
	$out .= "</fieldset>";
	return $out;
	}
