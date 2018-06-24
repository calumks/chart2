<?php

class Gig{

function getChartsForGig( $gigID = -1){
    $return = "";
    if ($gigID < 1){
        $gigID = self::getLatestGigID();
    }
    $sql = "SELECT T.setListID, T.setListOrder, V.name, V.arrangementID, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName), AC.arrCount, IF(AC.arrCount>1, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName), V.name), A.isBackedUp FROM setList2 AS T, view_arrangement AS V, (SELECT COUNT(*) as arrCount, songID FROM arrangement AS A GROUP BY songID) AS AC, arrangement AS A WHERE AC.songID = A.songID AND A.arrangementID = V.arrangementID AND T.arrangementID = V.arrangementID AND T.gigID = " . $gigID . " order by T.setListOrder ASC";
 $i = 1;
    $return = "<ol>";
    foreach (Connection::listMultiple($sql) AS $count=>$res){
        $label = $res[6];
        $label2 = "";
        if( !$res[7]) $label2 .= " (no back-up)";
        $check = "<a href='.?gigID=". $gigID . "&arrangementID=" . $res[3] . "'>".$label . "</a>". $label2 . "\n" . " ";
        $return .= "<li><p>" . $check . "</p></li>";
    }
    $return .= "</ol>";
    return $return;
}


function getForm( $gigID ){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$form = "";

$sql = "SELECT DISTINCT V.arrangementID, CONCAT(XYZ.partLabel, ' ', IF(AA.isInPads=1,'','*')), IF(AC.arrCount<2,V.songName,CONCAT(V.songName,', ',A.arrangerFirstName, ' ',A.arrangerLastName)) FROM arrangement AS AAA, (SELECT COUNT(*) AS arrCount, songID FROM arrangement GROUP BY songID) AS AC, view_efilePart AS V, view_arrangement AS A, arrangement AS AA, (SELECT XX.arrangementID, CONCAT('S',XX.count4, ' T',XX.count1,' B', XX.count2, ' R', XX.count5, ' V', XX.count6, ' C', XX.count9) as partLabel FROM (SELECT SUM(case when Csec.sectionID = 1 then Csec.countParts else 0 end) as count1, SUM(case when Csec.sectionID = 2 then Csec.countParts else 0 end) as count2, SUM(case when Csec.sectionID = 4 then Csec.countParts else 0 end) as count4, SUM(case when Csec.sectionID = 5 then Csec.countParts else 0 end) as count5, SUM(case when Csec.sectionID = 6 then Csec.countParts else 0 end) as count6, SUM(case when Csec.sectionID = 9 then Csec.countParts else 0 end) as count9, arrangementID FROM (SELECT count(*) as countParts, CC.arrangementID, CC.sectionID FROM (SELECT count(*) AS countAll, A.arrangementID, P.name as partName, S.sectionID as sectionID from efilePart as EF INNER JOIN efile as E on E.efileID = EF.efileID INNER JOIN publication as PUB on E.publicationID = PUB.publicationID INNER JOIN arrangement as A on A.arrangementID = PUB.arrangementID INNER JOIN part as P ON EF.partID = P.partID inner join section as S on S.sectionID = P.minSectionID GROUP BY A.arrangementID, S.sectionID, P.name) AS CC GROUP BY CC.arrangementID, CC.sectionID) as Csec GROUP BY arrangementID) AS XX) AS XYZ WHERE A.arrangementID=V.arrangementID AND AA.arrangementID=A.arrangementID AND A.arrangementID=XYZ.arrangementID AND AAA.arrangementID=A.arrangementID AND AC.songID=AAA.songID ORDER BY V.songName ASC";
$result = mysqli_query($link, $sql);
if ($result){
    $form .= "<div>";
    $form .= "<ol>";

	$i = 1;
    	while($row = mysqli_fetch_row( $result )) {
		$check = "";
		$check .= "<li>";
		$check .= "<p>";
		$check .= $row[1] . "<a href='.?arrangementID=" . $row[0] . "&gigID=". $gigID . "'>".$row[2] . "</a>" . " ";
		$check .= "</p>";
		$check .= "</li>";
		$form = $form . $check;
    	}
    $form .= "</ol>";
    $form .= "</div>";

}

$sql = "SELECT DISTINCT partID, partName FROM view_efilePart";
$result = mysqli_query($link, $sql);
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
		$check = "<p><input type='checkbox' name='part[]' value='" . $row[0] . "'>" . $row[1];
    	}
}

mysqli_close( $link );
return $form;
}


function getGigForm( $gigID = -1){


    if ($gigID < 1){
        $gigID = self::getLatestGigID();
    }

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
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

$form .= self::getChartsForGig( $gigID);

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

	$out = "<fieldset><legend>" . self::getGigLabel($gigID) . "</legend>";
	$out .= $form . "</fieldset>";

return $out;
    
}


function getGigLabel( $gigID){
    foreach (Connection::listMultiple("SELECT name, gigDate  from gig WHERE gigID = " . $gigID) AS $count=>$res){
        return $res[0] . " " . $res[1];
    }

}

function getLatestGigID(){
    foreach (Connection::listMultiple("SELECT gigID from gig ORDER BY gigDate DESC LIMIT 1") AS $count=>$res){
        return $res[0];
    }

}




} // end class Gig
