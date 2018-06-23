<?php
if (!function_exists('saveRequest')){
function saveRequest($input){
    	$now = date("Ymd");
	$ip = getIP();
	$get = print_r($input,1);

	$sql = "INSERT INTO request( requestWhen, requestGet ) VALUES( '" .$now . "','" . $get . "');";
	$result = my_execute( $sql);

	return $result;
}
}
