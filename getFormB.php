<?php

function getForm(){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
//$form = "<form action = 'allChart.php' method='GET'>";
$form = "<form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='getChart' />";

$sql = "SELECT DISTINCT V.arrangementID, CONCAT(V.songName,', ',A.arrangerFirstName, ' ',A.arrangerLastName) FROM view_efilePart AS V, view_arrangement AS A WHERE A.arrangementID=V.arrangementID ORDER BY V.songName ASC";
//echo $sql;
$result = mysqli_query($link, $sql);
if ($result){
	$i = 1;
    	while($row = mysqli_fetch_row( $result )) {
		$check = "<p><input type='checkbox' name='arrangement[]' value='" . $row[0] . "'>" . $i++ . ". " . $row[1];
		$form = $form . $check;
    	}
}

$sql = "SELECT DISTINCT partID, partName FROM view_efilePart";
$result = mysqli_query($link, $sql);
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
		$check = "<p><input type='checkbox' name='part[]' value='" . $row[0] . "'>" . $row[1];
		$form = $form . $check;
    	}
}




mysqli_close( $link );
$form .= "<input type='submit' value='Get pdf'></form>";
return $form;
}
