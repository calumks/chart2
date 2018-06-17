<?php
function storeEmail(){
    $sql = "SELECT COUNT(*) from user where md5email = md5(trim(upper('" . $_REQUEST['email'] . "')))";
    foreach( listMultiple( $sql ) AS $index=>$row ){
		if ($row[0] > 0) {
			sendCode( $_REQUEST['email'] );
			return true;
		} else {
			sendAdminDudEmail( $_REQUEST['email'] );
			return false;
		}
    }
}

function sendAdminDudEmail( $dudEmail ){
	$msg = "Unrecognised email.\n  " . $dudEmail;
	$msg = wordwrap($msg, 70);	
	$headers = 'Reply-To: ' . getOneAdminEmail();
	mail( getOneAdminEmail(), "TSB Chart dud email", $msg, $headers);
}
