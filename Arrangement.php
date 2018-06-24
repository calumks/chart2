<?php

class Arrangement{


function getChartListForm(){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$form = "<form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='getChartList' />";

$sql = "SELECT V.partName, V.partID FROM (SELECT DISTINCT partName, partID FROM view_efilePart) as V INNER JOIN (SELECT P.partID, S.printOrder from part as P INNER JOIN section AS S on P.minSectionID = S.sectionID) AS PP ON PP.partID= V.partID order by PP.printOrder ASC,  PP.partID ASC";
$result = mysqli_query($link, $sql);
if ($result){
	$form .= "<p><select name='partID'>";
    	while($row = mysqli_fetch_row( $result )) {
		$check = "<option value='" . $row[1] . "'>" . $row[0] . "</option>";
		$form = $form . $check;
    	}
	$check = "<option value='-123'>Vocals (all)</option>";
	$form = $form . $check;
	$check = "<option value='-999'>All</option>";
	$form = $form . $check;
	$form .= "</select>";
}

mysqli_close( $link );
$form .= "<input type='submit' value='Get Chart List'></form>";
return $form;
}

} // end class Arrangement
