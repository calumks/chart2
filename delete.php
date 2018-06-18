<?php

function getCopySetForm(){

$sqlCountTargets = "SELECT COUNT(*) FROM (SELECT gig.gigID, COALESCE(S.countCharts,0) AS counter FROM gig LEFT JOIN (SELECT COUNT(*) as countCharts, gigID from setList2 GROUP BY gigID) AS S ON S.gigID=gig.gigID WHERE COALESCE(S.countCharts,0)=0) AS C";
//echo $sqlCountTargets;
    	foreach( listMultiple( $sqlCountTargets ) AS $index=>$row ){
        	$counter = $row[0];
    	}

if (0 == $counter) return "";

$form = "<fieldset><legend>Copy set</legend><form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='copySetList' />";
$form .= "<p>Source <select name='sourceGigID'>";

$sqlSource = "SELECT gig.gigID, gig.name, gig.gigDate FROM gig LEFT JOIN (SELECT COUNT(*) as countCharts, gigID from setList2 GROUP BY gigID) AS S ON S.gigID=gig.gigID WHERE COALESCE(S.countCharts,0)>0 ORDER BY gigDate DESC, name ASC";
	foreach( listMultiple( $sqlSource ) AS $index=>$row ){
        	$check = "<option value=" . $row[0] . ">" . $row[1] . " " . $row[2] . "";
        	$form = $form . $check;
    	}
$form .= "</select>";
$form .= "<p>Target <select name='targetGigID'>";
$sqlTarget = "SELECT gig.gigID, gig.name, gig.gigDate FROM gig LEFT JOIN (SELECT COUNT(*) as countCharts, gigID from setList2 GROUP BY gigID) AS S ON S.gigID=gig.gigID WHERE COALESCE(S.countCharts,0)=0 ORDER BY gigDate DESC, name ASC";
	foreach( listMultiple( $sqlTarget ) AS $index=>$row ){
        		$check = "<option value=" . $row[0] . ">" . $row[1] . " " . $row[2] . "";
        		$form = $form . $check;
    	}
$form .= "</select>";
$form .= "</p><p><input type='submit' value='COPY SET'></p></form></fieldset>";
return $form;

}


function deleteSet(){
    
$gigID = $_POST['gigID'];
$sql1 = "delete from setList2 where gigID = " . $gigID . ";";
$sql2 = "delete from gig where gigID = " . $gigID . ";";
$result = my_execute( $sql1 );
$result = my_execute( $sql2 );

}

function getDeleteSetForm(){

//$form = "<form action = 'allChart.php' method='GET'>";
$form = "<fieldset><legend>Delete set</legend><form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='deleteSetList' />";
$form .= "<p><select name='gigID'>";

$sql = "SELECT DISTINCT gigID, name, gigDate FROM gig ORDER BY gigDate DESC, name ASC";
//echo $sql;
	$i = 1;
    	foreach( listMultiple( $sql ) AS $index=>$row ){
    	    if(11!=$row[0]){
        		$check = "<p><option value=" . $row[0] . ">" . $row[1] . " " . $row[2] . "</p>";
        		$form = $form . $check;
    	    }
    	}

$form .= "</p><p><input type='submit' value='DELETE SET'></p></form></fieldset>";
$sql = "SELECT name, countPlays from view_popular";
	$form .= "<fieldset><legend>Appearances in set lists</legend>";
        $form .= "<table>";
        $form .= "<tr><th>Song</th><th>Appearances</th></tr>";
    	foreach( listMultiple( $sql ) AS $index=>$row ){
        	$tr = "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        	$form = $form . $tr;
    	}
        $form .= "</table>";
	$form .= "</fieldset>";
return $form;
}

function getSetPartsOutput( $gigID, $directoryBase ){

$sql = "SELECT DISTINCT partName from view_efilePartSetList2 where gigID = " . $gigID . " ORDER BY partName ASC ";
    	foreach( listMultiple( $sql ) AS $index=>$row ){
        		pdfFromGigExplicit($gigID, $row[0], $directoryBase, "Gig" . $gigID . $row[0] );
        		echo $row[0] . " ";
    	}

}

function getSetPartsForm(){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$form = "<fieldset><legend>Output parts for set</legend><form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='getPartsForSet' />";
$form .= "<p><select name='gigID'>";

$sql = "SELECT DISTINCT gigID, name, gigDate FROM gig ORDER BY gigDate DESC, name ASC";
//echo $sql;
	$i = 1;
    	foreach( listMultiple( $sql ) AS $index=>$row ){
    	    if(11!=$row[0]){
        		$check = "<p><option value=" . $row[0] . ">" . $row[1] . " " . $row[2] . "</p>";
        		$form = $form . $check;
    	    }
    	}
$form .= "</p><p><input type='submit' value='Get parts (output to output folder)'></p></form></fieldset>";
return $form;
}

function getEditSetForm(){

$form = "<fieldset><legend>Get set to edit</legend><form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='getSetList' />";
$form .= "<p><select name='gigID'>";

$sql = "SELECT DISTINCT gigID, name, gigDate FROM gig ORDER BY gigDate DESC, name ASC";
//echo $sql;
	$i = 1;
    	foreach( listMultiple( $sql ) AS $index=>$row ){
    	    if(11!=$row[0]){
        		$check = "<p><option value=" . $row[0] . ">" . $row[1] . " " . $row[2] . "</p>";
        		$form = $form . $check;
    	    }
    	}

$form .= "</p><p><input type='submit' value='Get setlist'></p></form></fieldset>";
return $form;
}


