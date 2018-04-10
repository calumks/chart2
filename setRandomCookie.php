<?php
function setTSBcookie( $value, $expiry ){
	setcookie("tsbcode", $value, $expiry);
}

function setTSBcookieArray( $value, $expiry ){
	setcookie("tsbcodearray", json_encode($value), $expiry);
}

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

function hasValidCookie(){
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

function setValidCookie( $confirmation ){

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 
$sql = "SELECT confirmationID FROM confirmation where confirmationCode = '" . $confirmation ."' LIMIT 1;";
//echo $sql; 
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

function isrecognisedip( $cookie ){
//return true; // ignore IPs AND CODES!
$breturn = false;
//$ip = getIP();

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 
//$sql = "SELECT COUNT(*) FROM confirmation where tsbcode = '" . $cookie ."' AND ip = '" . $ip . "';";
$sql = "SELECT COUNT(*) FROM confirmation where tsbcode = '" . $cookie ."';";
//echo $sql; 
$result = mysqli_query($link, $sql);

if ($result) {
	$row = mysqli_fetch_row( $result );
	if ($row[0] > 0){
		$breturn = true;
	} else {
		;
	}
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($link);
}

mysqli_close( $link );
return $breturn;
}
setTSBcookie( '1234abc', time() + 365 * 24 * 60 * 60 );
echo "done";
