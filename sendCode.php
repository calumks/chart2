<?php
function sendCode( $email ){
// assumes email is recognised

$md5now = md5(time());

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 
    $sql = "SELECT userID from user where md5email = md5(trim(upper(' " . $email . "')))";
    $result = mysqli_query($link, $sql);
    if ($result){
    	$row = mysqli_fetch_row( $result );
	$userID = $row[0];
    }
$sql = "INSERT into confirmation (userID, confirmationCode, ip) VALUES( " . $userID . ", '" . $md5now . "', '" . getIP() . "');";
//echo $sql . __FILE__;
$result = mysqli_query($link, $sql);
if ($result){
	$msg = "To use the TSB chart printer please paste this address into your browser.\n  http://tsbchart.000webhostapp.com/?confirmation=" . $md5now;
	$msg = wordwrap($msg, 70);
	
//	$headers = 'From: ' .getOneAdminEmail() . "\r\n" . 'Reply-To: ' . getOneAdminEmail(). "\r\n" . 'Cc: ' .getOneAdminEmail();
	$headers = 'Reply-To: ' . getOneAdminEmail(). "\r\n" . 'Cc: ' .getOneAdminEmail();
//	$headers = 'Bcc: ' .getOneAdminEmail();

//echo $msg . __FILE__;
	mail( $email, "TSB Chart confirm email", $msg, $headers);
}

mysqli_close( $link );
}

function getAdminEmails(){
    $return = array();
    $sql = "SELECT AES_DECRYPT(aesEmail, UNHEX(SHA2('A String Of Pearls',512))) from user where aesEmail is not null"; 
    include "mysql-cred.php";
    $link  = mysqli_connect( $servername, $username, $password, $database);
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error);
    } 
    $result = mysqli_query($link, $sql);
    if ($result){
    	$row = mysqli_fetch_row( $result );
	$return[] = $row[0];
    }
    return $return;
}

function getOneAdminEmail(){
    $all = getAdminEmails();
    if (count($all) > 0){
        return $all[0]; 
    } else {
        return "null@null";
}
}

//print_r(getAdminEmail());

function storeNewUser( $email, $nickName ){

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 
$sql = "INSERT INTO user(md5email, nickName) SELECT md5(trim(upper(' " . $email . "'))), '" . $nickName . "';";
$result = mysqli_stmt_execute(mysqli_prepare($link, $sql));
mysqli_close( $link );
}