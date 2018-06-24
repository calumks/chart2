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



} // end class Connection
