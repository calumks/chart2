<?php

function getArrangementForm( $arrangementID){

$form = "";
include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "SELECT noteText from note  INNER JOIN publication as PUB on note.publicationID = PUB.publicationID INNER JOIN arrangement as A on A.arrangementID = PUB.arrangementID  WHERE A.arrangementID=" . $arrangementID . " ORDER BY note.noteID ASC ";
//echo $sql;
$result = mysqli_query($link, $sql);
$noteText = "";
if ($result){
	$i = 1;
    	while($row = mysqli_fetch_row( $result )) {
	        $check = "<p>" . $row[0] . "</p>\n";
		$noteText .= $check;
    	}
}

$form .= $noteText;

$sql = "SELECT DISTINCTROW P.partID, P.name as partName, song.name from efilePart as EF INNER JOIN efile as E on E.efileID = EF.efileID INNER JOIN publication as PUB on E.publicationID = PUB.publicationID INNER JOIN arrangement as A on A.arrangementID = PUB.arrangementID INNER JOIN song ON song.songID = A.songID INNER JOIN part as P ON EF.partID = P.partID inner join section as S on S.sectionID = P.minSectionID WHERE A.arrangementID=" . $arrangementID . " ORDER BY S.printOrder ASC, P.partID ASC ";
//echo $sql;
$result = mysqli_query($link, $sql);
$songName = "NOT FOUND";
if ($result){
	$i = 1;
    	while($row = mysqli_fetch_row( $result )) {
	        $check = "<p>  <a href='.?action=getChart&arrangement[]= " . $arrangementID . "&part[]=" . $row[0] . "'>"  . $row[1] . "</a></p>\n";
		$form = $form . $check;
		$songName = $row[2];
    	}
}

$form = "<fieldset><legend>" . $songName . "</legend>\n" . $form . "</fieldset>\n"; 
return $form;
}

function getForm(){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
//$form = "<form action = 'allChart.php' method='GET'>";
$form = "<form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='getChart' />";

$sql = "SELECT DISTINCT V.arrangementID, CONCAT(XYZ.partLabel, ' ', IF(AA.isInPads=1,'','*')), CONCAT(V.songName,', ',A.arrangerFirstName, ' ',A.arrangerLastName) FROM view_efilePart AS V, view_arrangement AS A, arrangement AS AA, (SELECT XX.arrangementID, CONCAT('S',XX.count4, ' T',XX.count1,' B', XX.count2, ' R', XX.count5, ' V', XX.count6, ' C', XX.count9) as partLabel FROM
(SELECT 
SUM(case when Csec.sectionID = 1 then Csec.countParts else 0 end) as count1, 
SUM(case when Csec.sectionID = 2 then Csec.countParts else 0 end) as count2, 
SUM(case when Csec.sectionID = 4 then Csec.countParts else 0 end) as count4, 
SUM(case when Csec.sectionID = 5 then Csec.countParts else 0 end) as count5, 
SUM(case when Csec.sectionID = 6 then Csec.countParts else 0 end) as count6, 
SUM(case when Csec.sectionID = 9 then Csec.countParts else 0 end) as count9, 
arrangementID FROM (SELECT count(*) as countParts, CC.arrangementID, CC.sectionID FROM (SELECT count(*) AS countAll, A.arrangementID, P.name as partName, S.sectionID as sectionID from efilePart as EF INNER JOIN efile as E on E.efileID = EF.efileID INNER JOIN publication as PUB on E.publicationID = PUB.publicationID INNER JOIN arrangement as A on A.arrangementID = PUB.arrangementID INNER JOIN part as P ON EF.partID = P.partID inner join section as S on S.sectionID = P.minSectionID GROUP BY A.arrangementID, S.sectionID, P.name) AS CC GROUP BY CC.arrangementID, CC.sectionID) as Csec GROUP BY arrangementID) AS XX) AS XYZ  WHERE A.arrangementID=V.arrangementID AND AA.arrangementID=A.arrangementID AND A.arrangementID=XYZ.arrangementID ORDER BY V.songName ASC";
//echo $sql;
$result = mysqli_query($link, $sql);
if ($result){
	$i = 1;
    	while($row = mysqli_fetch_row( $result )) {
//		$check = "<p><input type='checkbox' name='arrangement[]' value='" . $row[0] . "'>" . $i++ . ". " . $row[1] . " " . getPartLabel( $row[0] );
		$check = "<p>";
//		$check .= "<input type='checkbox' name='arrangement[]' value='" . $row[0] . "'>";
		$check .= $i++ . ". " . $row[1] . "<a href='.?arrangementID=" . $row[0] . "'>".$row[2] . "</a>" . " ";
		$form = $form . $check;
    	}
}

$sql = "SELECT DISTINCT partID, partName FROM view_efilePart";
$result = mysqli_query($link, $sql);
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
		$check = "<p><input type='checkbox' name='part[]' value='" . $row[0] . "'>" . $row[1];
//		$form = $form . $check;
    	}
}




mysqli_close( $link );
//$form .= "<input type='submit' value='Get pdf'></form>";
return $form;
}


function getPartLabel( $arrangementID){ // slow!

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "SELECT ST.shortName, cSec.* FROM (SELECT count(*) as countParts, CC.arrangementID, CC.sectionID FROM (SELECT count(*) AS countAll, A.arrangementID, P.name as partName, S.sectionID as sectionID from efilePart as EF INNER JOIN efile as E on E.efileID = EF.efileID INNER JOIN publication as PUB on E.publicationID = PUB.publicationID INNER JOIN arrangement as A on A.arrangementID = PUB.arrangementID INNER JOIN part as P ON EF.partID = P.partID inner join section as S on S.sectionID = P.minSectionID WHERE A.arrangementID = ". $arrangementID . " GROUP BY A.arrangementID, S.sectionID, P.name) AS CC GROUP BY CC.arrangementID, CC.sectionID) AS cSec INNER JOIN section as ST ON ST.sectionID = cSec.sectionID ORDER BY cSec.arrangementID ASC, ST.printOrder ASC ";
//echo $sql;
$result = mysqli_query($link, $sql);
if ($result){
		$check = "";
    	while($row = mysqli_fetch_row( $result )) {
		$check .= $row[0] . $row[1] . " ";
    	}
}


mysqli_close( $link );
return $check;
}
