<?php

function copySetList( $sourceGigID, $targetGigID){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$sqlDeleteSetList = "DELETE FROM setList2 WHERE gigID = " . $targetGigID;
$result = mysqli_execute(mysqli_prepare($link, $sqlDeleteSetList));
$sqlCopySetList = "INSERT INTO setList2(arrangementID,  gigID, setListOrder) SELECT arrangementID,  " . $targetGigID . ", setListOrder FROM setList2 WHERE gigID = " . $sourceGigID;
//echo $sqlCopySetList;
//echo "\n\n";
$result = mysqli_execute(mysqli_prepare($link, $sqlCopySetList));


}


function postNewSetList(){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$isGig = 0;    
if (isset($_POST['isGig'])){
	if ('isPublic'==$_POST['isGig']){
		$isGig = 1;
	}
}

$sqlNewGig = "insert into gig (name, gigDate, isGIG) VALUES( '".$_POST['gigName'] ."', '".$_POST['gigDate']."', " . $isGig . ");";
//echo $sqlNewGig;
//echo "\n\n";
$result = mysqli_execute(mysqli_prepare($link, $sqlNewGig));


}

function getNewSetListForm(){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
//$form = "<form action = 'allChart.php' method='GET'>";
$form = "<fieldset><legend>New set</legend>";
$form .= "<form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='addSetList' />";
$form .= "<p>Gig name<textarea name='gigName'>Gig name (enter here)</textarea></p> ";
$form .= "<p>Gig date<input type='date' name='gigDate' ></p> ";
$form .= "<p>Performance (leave unticked if it's a practice)<input type='checkbox' name='isGig' value='isPublic' ></p> ";
//$form .= "<p>Set list (lowest numbers come first)</p> ";

//$sql = "SELECT DISTINCT arrangementID, songName FROM view_efilePart ORDER BY songName ASC";
//echo $sql;
//$result = mysqli_query($link, $sql);
//if ($result){
//	$i = 1;
//    	while($row = mysqli_fetch_row( $result )) {
//		$check = "<p><input type='text' name='arrangement[" . $row[0] . "]' > " . $row[1];
//		$check = "<p><input type='checkbox' name='arrangement[]' value=" . $row[0] . " > " . $row[1];
//		$form = $form . $check;
//    	}
//}

mysqli_close( $link );
$form .= "<input type='submit' value='ADD SET'></form>";
$form .= "</fieldset>";
return $form;
}
/*
include_once "../include_refsB.php";
if (hasValidCookie()){

if (isset($_POST['action'])){
    if ('addSetList'==$_POST['action']){
        postNewSetList();
    }
}
if (hasValidCookie()){
echo getNewSetListForm();
echo "<a href='delete.php'>Delete set</a></p>";
echo "<p><a href='../'>Index</a></p>";

}
}
*/
