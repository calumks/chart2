<?php

function getGigForm(){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
//$form = "<form action = 'allChart.php' method='GET'>";
$form = "<form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='getGig' />";

$sql = "SELECT gigID, name, gigDate FROM gig ORDER BY gigDate DESC";
$result = mysqli_query($link, $sql);
if ($result){
        $i = 1;
	$form .= "<p><select name='gigID'>";
    	while($row = mysqli_fetch_row( $result )) {
		$check = "<option value='" . $row[0] . "'>" . $row[1] . " "  . ". " . $row[2] . "</option>";
		$form = $form . $check;
                $i++;
    	}
	$form .= "</select>";
}

$sql = "SELECT DISTINCT partName FROM view_efilePart";
$result = mysqli_query($link, $sql);
if ($result){
	$form .= "<p><select name='part'>";
    	while($row = mysqli_fetch_row( $result )) {
		$check = "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
		$form = $form . $check;
    	}
	$form .= "</select>";
}




mysqli_close( $link );
$form .= "<input type='submit' value='Get pdf'></form>";
return $form;
}
