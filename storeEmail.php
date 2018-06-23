<?php
function storeEmail( $email = ""){
    $sql = "SELECT COUNT(*) from user where md5email = md5(trim(upper('" . $email . "')))";
    foreach( listMultiple( $sql ) AS $index=>$row ){
		if ($row[0] > 0) {
			sendCode( $email );
			return true;
		} else {
			sendAdminDudEmail( $email );
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
