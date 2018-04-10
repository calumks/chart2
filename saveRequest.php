<?php
if (!function_exists('saveRequest')){
function saveRequest(){
    	$now = date("Ymd");
	$ip = getIP();
	$get = print_r($_GET,1);

	include "mysql-cred.php";
	$link  = mysqli_connect( $servername, $username, $password, $database);
	if (mysqli_connect_errno()) {
    		die("Connection failed: " . mysqli_connect_error);
	} 
	$sql = "INSERT INTO request( requestIP, requestWhen, requestGet ) VALUES( '" . $ip . "', '" .$now . "','" . $get . "');";
	$result = mysqli_query($link, $sql);

	if ($result) {
		;
	} else {
    		echo "Error: " . $sql . "<br>" . mysqli_error($link);
	}
	mysqli_close( $link );
	return $result;
}
}
