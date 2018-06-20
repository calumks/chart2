<?php

function getGigLabel( $gigID){
    foreach (listMultiple("SELECT name, gigDate  from gig WHERE gigID = " . $gigID) AS $count=>$res){
        return $res[0] . " " . $res[1];
    }

}

function getLatestGigID(){
    foreach (listMultiple("SELECT gigID from gig ORDER BY gigDate DESC LIMIT 1") AS $count=>$res){
        return $res[0];
    }

}
//echo getLatestGigID();

function getChartsForGig( $gigID = -1){
    $return = "";
    if ($gigID < 1){
        $gigID = getLatestGigID();
    }
    $sql = "SELECT T.setListID, T.setListOrder, V.name, V.arrangementID, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName), AC.arrCount, IF(AC.arrCount>1, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName), V.name), A.isBackedUp FROM setList2 AS T, view_arrangement AS V, (SELECT COUNT(*) as arrCount, songID FROM arrangement AS A GROUP BY songID) AS AC, arrangement AS A WHERE AC.songID = A.songID AND A.arrangementID = V.arrangementID AND T.arrangementID = V.arrangementID AND T.gigID = " . $gigID . " order by T.setListOrder ASC";
 //echo $sql;
 $i = 1;
    $return = "<ol>";
    foreach (listMultiple($sql) AS $count=>$res){
        $label = $res[6];
        $label2 = "";
        if( !$res[7]) $label2 .= " (no back-up)";
//        if( $res[5]>1) $label= $res[4];
        $check = "<a href='.?gigID=". $gigID . "&arrangementID=" . $res[3] . "'>".$label . "</a>". $label2 . "\n" . " ";
        $return .= "<li>" . $check . "</li>";
    }
    $return .= "</ol>";
    return $return;
}

function getGigForm( $gigID = -1){


    if ($gigID < 1){
        $gigID = getLatestGigID();
    }

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
//$form = "<form action = 'allChart.php' method='GET'>";
$form = "<form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='changeGig' />";

$sql = "SELECT gigID, name, gigDate FROM gig ORDER BY gigDate DESC";
$result = mysqli_query($link, $sql);
if ($result){
        $i = 1;
	$form .= "<p><select name='gigID'>";
    	while($row = mysqli_fetch_row( $result )) {
    	    if ($gigID==$row[0]){
    	        $selected = " selected ";
    	    } else {
    	        $selected = "";
    	    }
		$check = "<option value='" . $row[0] . "'" . $selected . ">" . $row[1] . " "  . ". " . $row[2] . "</option>";
		$form = $form . $check;
                $i++;
    	}
	$form .= "</select>";
}
$form .= "<input type='submit' value='Change gig'></form>";

$form .= getChartsForGig( $gigID);

$form .= "<form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='getGig' />";
$form .= "<input type='hidden' name='gigID' value='" . $gigID . "' />";


$sql = "SELECT V.partName, V.partID FROM (SELECT DISTINCT partName, partID FROM view_efilePart) as V INNER JOIN (SELECT P.partID, S.printOrder from part as P INNER JOIN section AS S on P.minSectionID = S.sectionID) AS PP ON PP.partID= V.partID order by PP.printOrder ASC,  PP.partID ASC";
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
$form .= "<input type = 'checkbox' name='includeMusic' value='include' checked>Include Music";
$form .= "<input type='submit' value='Get pdf of whole set'></form>";

	$out = "<fieldset><legend>" . getGigLabel($gigID) . "</legend>";
	$out .= $form . "</fieldset>";

return $out;
    
}


function getChartListForm(){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
//$form = "<form action = 'allChart.php' method='GET'>";
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
