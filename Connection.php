<?php

class Connection{

function listSingle($sql){

$pairedFiles = array();
    	foreach(listMultiple( $sql ) AS $index=>$row ){
    	$pairedFiles[] = $row[0];
    	}
return $pairedFiles;
}

function listMultiple($sql){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$result = mysqli_query($link, $sql);
$pairedFiles = array();
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
    	$pairedFiles[] = $row;
    	}
}
mysqli_close( $link );
return $pairedFiles;
}


function my_execute( $sql ){

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
//echo "sql: " . $sql;
$statement = mysqli_prepare($link, $sql);
$result = mysqli_execute($statement);
mysqli_close($link);
return $result;    
}


function my_insert_id( $sql ){

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$result = mysqli_execute(mysqli_prepare($link, $sql));
$lastID = mysqli_insert_id( $link );
mysqli_close($link);
return $lastID;    
}


function saveRequest($input){
    	$now = date("Ymd");
	$ip = User::getIP();
	$get = print_r($input,1);

	$sql = "INSERT INTO request( requestWhen, requestGet ) VALUES( '" .$now . "','" . $get . "');";
	$result = self::my_execute( $sql);

	return $result;
}

} // end class Connection
