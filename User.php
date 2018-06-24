<?php

class User
{

function addToCookieArray( $newValue, $expiry ){
	$oldarray = array();
	if (isset($_COOKIE['tsbcodearray'])){
		$oldarray = json_decode($_COOKIE['tsbcodearray']);
	}
	$oldarray[] = $newValue;
	setTSBcookieArray( $oldarray, $expiry );
}


function deleteCookie(){
	setTSBcookie( "", time()-3600 );
	setTSBcookieArray( "", time()-3600 );
}


function getAdminEmails(){
    $return = array();
    $sql = "SELECT AES_DECRYPT(aesEmail, UNHEX(SHA2('A String Of Pearls',512))) from user where aesEmail is not null"; 
    foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
	$return[] = $row[0];
    }
    return $return;
}


function getEmailForm(){

$form = "<form action = '' method='post'>";
$form .= "<p>Your email <textarea rows='1' cols='50' name='email' ></textarea></p>";
$form .= "<input type='hidden' name='action' value='storeEmail'>";
$form .= "<p><input type='submit' value='submit'></p></form>";
$form .= "<p>To get a confirmation code to enable you to use the chart printer please enter your email and hit submit.  The confirmation code will store a cookie on your computer that gives you access.  You can remove the cookie but then you'll need to enter your email again.  If your email isn't recognised, please let us know at a rehearsal.</p>";
return $form;
}


function getOneAdminEmail(){
    $all = getAdminEmails();
    if (count($all) > 0){
        return $all[0]; 
    } else {
        return "null@null";
    }
}


function getUserFromTsbcode( $tsbcode ){
$sql = "SELECT userid FROM confirmation where tsbcode = '" . $tsbcode ."' LIMIT 1;";
$ret = -1; // if nothing found
foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
	$ret = $row[0];
    } 
return $ret;
}


function hasAdminCookie(){
if (ALL_USERS_ARE_ADMINS == 'All') return true;
$breturn = false;
if (!isset($_COOKIE['tsbcode'])){
	return false;
}
$breturn = isrecognisedAdmin( $_COOKIE['tsbcode'] );
	if (isset($_COOKIE['tsbcodearray'])){
		$oldarray = json_decode($_COOKIE['tsbcodearray']);
		foreach ($oldarray as $ckie){
			$breturn = $breturn || isrecognisedAdmin( $ckie );
		}
	}
return $breturn;
}

function hasCookieForEmail( $email ){
$breturn = false;
if (!isset($_COOKIE['tsbcode'])){
	return false;
}
$breturn = isCookieForEmail( $_COOKIE['tsbcode'], $email );
	if (isset($_COOKIE['tsbcodearray'])){
		$oldarray = json_decode($_COOKIE['tsbcodearray']);
		foreach ($oldarray as $ckie){
			$breturn = $breturn || isCookieForEmail( $ckie, $email );
		}
	}
return $breturn;
}


function hasValidCookie(){
if (ALL_USERS_ARE_ADMINS == 'All') return true;
$breturn = false;
if (!isset($_COOKIE['tsbcode'])){
	return false;
}
$breturn = isrecognisedip( $_COOKIE['tsbcode'] );
	if (isset($_COOKIE['tsbcodearray'])){
		$oldarray = json_decode($_COOKIE['tsbcodearray']);
		foreach ($oldarray as $ckie){
			$breturn = $breturn || isrecognisedip( $ckie );
		}
	}
return $breturn;
}

function isCookieForEmail( $cookie, $email ){
$breturn = false;

$sql = "SELECT COUNT(*) FROM confirmation INNER JOIN user ON confirmation.userID = user.userID where tsbcode = '" . $cookie ."' AND user.md5email = md5(trim(upper('" . $email . "')));";

foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
	if ($row[0] > 0){
		$breturn = true;
	}
}

return $breturn;
}

function isrecognisedAdmin( $cookie ){
$breturn = false;

$sql = "SELECT COUNT(*) FROM confirmation INNER JOIN user on confirmation.userID = user.userID where tsbcode = '" . $cookie ."' AND user.aesEmail is not null";
foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
	if ($row[0] > 0){
		$breturn = true;
	}
}

return $breturn;
}

function isrecognisedip( $cookie ){
$breturn = false;
$sql = "SELECT COUNT(*) FROM confirmation where tsbcode = '" . $cookie ."';";

foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
	if ($row[0] > 0){
		$breturn = true;
	}
}

return $breturn;
}


function sendAdminDudEmail( $dudEmail ){
	$msg = "Unrecognised email.\n  " . $dudEmail;
	$msg = wordwrap($msg, 70);	
	$headers = 'Reply-To: ' . getOneAdminEmail();
	mail( getOneAdminEmail(), "TSB Chart dud email", $msg, $headers);
}

function sendCode( $email ){

$md5now = md5(time());
$sql = "SELECT userID from user where md5email = md5(trim(upper(' " . $email . "'))) LIMIT 1";

    foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
	$userID = $row[0];
    }

$sql = "INSERT into confirmation (userID, confirmationCode, ip) VALUES( " . $userID . ", '" . $md5now . "', '" . getIP() . "');";
$result = Connection::my_execute( $sql);
if ($result){
	$msg = "To use the TSB chart printer please paste this address into your browser.\n  http://tsbchart.000webhostapp.com/?confirmation=" . $md5now;
	$msg = wordwrap($msg, 70);
	
	$headers = 'Reply-To: ' . getOneAdminEmail(). "\r\n" . 'Cc: ' .getOneAdminEmail();
	mail( $email, "TSB Chart confirm email", $msg, $headers);
	}

}

function setTSBcookie( $value, $expiry ){
	setcookie("tsbcode", $value, $expiry);
}

function setTSBcookieArray( $value, $expiry ){
	setcookie("tsbcodearray", json_encode($value), $expiry);
}


function setValidCookie( $confirmation ){

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 
$sql = "SELECT confirmationID FROM confirmation where confirmationCode = '" . $confirmation ."' LIMIT 1;";
$result = mysqli_query($link, $sql);

if ($result) {
	$row = mysqli_fetch_row( $result );
	$confirmationID = $row[0];
	if ($confirmationID > 0){
	$md5now = md5(time());
	$sql = "UPDATE confirmation SET confirmationCode='EXPIRED', tsbcode = '" . $md5now . "' WHERE confirmationID = " . $confirmationID . ";";
	$result = mysqli_query($link, $sql);
	setTSBcookie( $md5now, time() + 365 * 24 * 60 * 60 );
	addToCookieArray( $md5now, time() + 365 * 24 * 60 * 60 );
	}
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($link);
	return false;
}

mysqli_close( $link );
}


function storeEmail( $email = ""){
    $sql = "SELECT COUNT(*) from user where md5email = md5(trim(upper('" . $email . "')))";
    foreach(Connection::listMultiple( $sql ) AS $index=>$row ){
		if ($row[0] > 0) {
			sendCode( $email );
			return true;
		} else {
			sendAdminDudEmail( $email );
			return false;
		}
    }
}



function storeNewUser( $email, $nickName ){

$sql = "INSERT INTO user(md5email, nickName) SELECT md5(trim(upper(' " . $email . "'))), '" . $nickName . "';";
$result = Connection::my_execute($sql);
}

} // end class User
