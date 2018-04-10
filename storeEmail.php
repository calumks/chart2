<?php
function storeEmail(){
    include "mysql-cred.php";
    $link  = mysqli_connect( $servername, $username, $password, $database);
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error);
    } 
    $sql = "SELECT COUNT(*) from user where md5email = md5(trim(upper('" . $_REQUEST['email'] . "')))";
    $result = mysqli_query($link, $sql);
    if ($result){
    	$row = mysqli_fetch_row( $result );
		if ($row[0] > 0) {
//echo "about to send code" . __FILE__ ;
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
	
//	$headers = 'From: ' .getOneAdminEmail() . "\r\n" . 'Reply-To: ' . getOneAdminEmail(). "\r\n" . 'Bcc: ' .getOneAdminEmail();
	$headers = 'Reply-To: ' . getOneAdminEmail();
//	$headers = 'Reply-To: ' . getOneAdminEmail(). "\r\n" . 'Cc: ' .getOneAdminEmail();
//	$headers = 'Bcc: ' .getOneAdminEmail();

//echo $msg . __FILE__;
	mail( getOneAdminEmail(), "TSB Chart dud email", $msg, $headers);
}