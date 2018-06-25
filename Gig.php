<?php

use \setasign\Fpdi;

class Gig{


function addToSet( $gigID, $order, $arrangementID){
    
    if ($arrangementID > 0 && $gigID > 0){
        $sql = "INSERT INTO setList2 (arrangementID, gigID, setListOrder) VALUES('". $arrangementID . "',". $gigID . "," . $order . ");";
        $result = Connection::my_execute( $sql );
}
 
}

function copySetList( $sourceGigID, $targetGigID){

$sqlDeleteSetList = "DELETE FROM setList2 WHERE gigID = " . $targetGigID;
$result = Connection::my_execute( $sqlDeleteSetList);
$sqlCopySetList = "INSERT INTO setList2(arrangementID,  gigID, setListOrder) SELECT arrangementID,  " . $targetGigID . ", setListOrder FROM setList2 WHERE gigID = " . $sourceGigID;
$result = Connection::my_execute( $sqlCopySetList );

}



function deleteOutput( $directoryBase ){
$files = glob($directoryBase . '/output/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}
} 



function deleteSet( $input=array()){

if (isset($input['gigID'])){    
$gigID = $input['gigID'];
$sql1 = "delete from setList2 where gigID = " . $gigID . ";";
$sql2 = "delete from gig where gigID = " . $gigID . ";";
$result = Connection::my_execute( $sql1 );
$result = Connection::my_execute( $sql2 );
}

}


function deleteSetListPart( $setListID){
    
        $sql = "DELETE FROM  setList2 where setListID = ". $setListID . ";";
        $result = Connection::my_execute( $sql );
 
}


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


function getCopySetForm(){

$sqlCountTargets = "SELECT COUNT(*) FROM (SELECT gig.gigID, COALESCE(S.countCharts,0) AS counter FROM gig LEFT JOIN (SELECT COUNT(*) as countCharts, gigID from setList2 GROUP BY gigID) AS S ON S.gigID=gig.gigID WHERE COALESCE(S.countCharts,0)=0) AS C";
    	foreach( Connection::listMultiple( $sqlCountTargets ) AS $index=>$row ){
        	$counter = $row[0];
    	}

if (0 == $counter) return "";

$form = "<fieldset><legend>Copy set</legend><form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='copySetList' />";
$form .= "<p>Source <select name='sourceGigID'>";

$sqlSource = "SELECT gig.gigID, gig.name, gig.gigDate FROM gig LEFT JOIN (SELECT COUNT(*) as countCharts, gigID from setList2 GROUP BY gigID) AS S ON S.gigID=gig.gigID WHERE COALESCE(S.countCharts,0)>0 ORDER BY gigDate DESC, name ASC";
	foreach( Connection::listMultiple( $sqlSource ) AS $index=>$row ){
        	$check = "<option value=" . $row[0] . ">" . $row[1] . " " . $row[2] . "";
        	$form = $form . $check;
    	}
$form .= "</select>";
$form .= "<p>Target <select name='targetGigID'>";
$sqlTarget = "SELECT gig.gigID, gig.name, gig.gigDate FROM gig LEFT JOIN (SELECT COUNT(*) as countCharts, gigID from setList2 GROUP BY gigID) AS S ON S.gigID=gig.gigID WHERE COALESCE(S.countCharts,0)=0 ORDER BY gigDate DESC, name ASC";
	foreach( Connection::listMultiple( $sqlTarget ) AS $index=>$row ){
        		$check = "<option value=" . $row[0] . ">" . $row[1] . " " . $row[2] . "";
        		$form = $form . $check;
    	}
$form .= "</select>";
$form .= "</p><p><input type='submit' value='COPY SET'></p></form></fieldset>";
return $form;

}





function getDeleteSetForm(){

$form = "<fieldset><legend>Delete set</legend><form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='deleteSetList' />";
$form .= "<p><select name='gigID'>";

$sql = "SELECT DISTINCT gigID, name, gigDate FROM gig ORDER BY gigDate DESC, name ASC";
	$i = 1;
    	foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
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
    	foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
        	$tr = "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        	$form = $form . $tr;
    	}
        $form .= "</table>";
	$form .= "</fieldset>";
return $form;
}



function getEditSetForm(){

$form = "<fieldset><legend>Get set to edit</legend><form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='getSetList' />";
$form .= "<p><select name='gigID'>";

$sql = "SELECT DISTINCT gigID, name, gigDate FROM gig ORDER BY gigDate DESC, name ASC";
	$i = 1;
    	foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
    	    if(11!=$row[0]){
        		$check = "<p><option value=" . $row[0] . ">" . $row[1] . " " . $row[2] . "</p>";
        		$form = $form . $check;
    	    }
    	}

$form .= "</p><p><input type='submit' value='Get setlist'></p></form></fieldset>";
return $form;
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


function getGigSetForm($gigID){
 
    $lastOrder = -999;
    $gigLabel = "";
    foreach (Connection::listMultiple("SELECT G.name, G.gigDate FROM gig as G WHERE  G.gigID = " . $gigID . "")  as $key=>$song){
        $gigLabel = $song[0] . " " . $song[1];
    }   
    $return = "";
    $return .= "<fieldset><legend>Edit set list for " . $gigLabel . "</legend>";
    $return .= "<div><table>";
    $return .= "<tr><th>Song<th> </tr>";
    $order = 999;
    foreach (Connection::listMultiple("SELECT T.setListID, T.setListOrder, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName)  FROM setList2 AS T, view_arrangement AS V WHERE T.arrangementID = V.arrangementID AND T.gigID = " . $gigID . " order by T.setListOrder ASC")  as $key=>$song){
        $order = $song[1];
        $midOrder = 0.5 * ($lastOrder + $order);
        $lastOrder = $order;
        $return .= "<tr><td>";
        $return .= "<form action='' method='POST'>";
        $return .= "<input type='hidden' name='action' value='addSetListPart'>";
        $return .= "<input type='hidden' name='gigID' value='" . $gigID . "'>";
        $return .= "<input type='hidden' name='setListOrder' value='" . $midOrder . "'>";
        $return .= "<select name='arrangementID'>";
        $return .= "<option value='" . -1 . "'>" . "" . "</option>";
        foreach (Connection::listMultiple("SELECT V.arrangementID, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName)  FROM view_arrangement AS V  order by V.name  ASC")  as $keyy=>$songg){
            $return .= "<option value='" . $songg[0] . "'>" . $songg[1] . "</option>";
        }
        $return .= "</select>";
        $return .= "<input type='submit' value='INSERT'>";
        $return .= "</form>";
        $return .= "</td></tr>";
        $return .= "<tr>";
        $return .= "<td>";
        $return .= "<form action='' method='POST'>";
        $return .= "<input type='hidden' name='action' value='deleteSetListPart'>";
        $return .= "<input type='hidden' name='setListID' value='" . $song[0] . "'>";
        $return .= "<input type='submit' value='(DELETE) " . $song[2] . "'>";
        $return .= "</form>";
        $return .= "</td>";
        $return .= "</tr>";

    }
        $lastOrder = $order + 10;
        $return .= "<tr><td>";
        $return .= "<form action='' method='POST'>";
        $return .= "<input type='hidden' name='action' value='addSetListPart'>";
        $return .= "<input type='hidden' name='gigID' value='" . $gigID . "'>";
        $return .= "<input type='hidden' name='setListOrder' value='" . $lastOrder . "'>";
        $return .= "<select name='arrangementID'>";
        $return .= "<option value='" . -1 . "'>" . "" . "</option>";
        foreach (Connection::listMultiple("SELECT V.arrangementID, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName)  FROM view_arrangement AS V  order by V.name  ASC")  as $key=>$song){
            $return .= "<option value='" . $song[0] . "'>" . $song[1] . "</option>";
        }
        $return .= "</select>";
        $return .= "<input type='submit' value='INSERT'>";
        $return .= "</form>";
        $return .= "</td></tr>";
    $return .= "</table></div>";
    $return .= "</fieldset>";
    return $return;
}


function getLatestGigID(){
    foreach (Connection::listMultiple("SELECT gigID from gig ORDER BY gigDate DESC LIMIT 1") AS $count=>$res){
        return $res[0];
    }

}


function getNewSetListForm(){

$form = "<fieldset><legend>New set</legend>";
$form .= "<form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='addSetList' />";
$form .= "<p>Gig name<textarea name='gigName'></textarea></p> ";
$form .= "<p>Gig date<input type='date' name='gigDate' ></p> ";
$form .= "<p>Performance (leave unticked if it's a practice)<input type='checkbox' name='isGig' value='isPublic' ></p> ";
$form .= "<input type='submit' value='ADD SET'></form>";
$form .= "</fieldset>";
return $form;
}


function getSetPartsForm(){

$form = "<fieldset><legend>Output parts for set</legend><form action = '' method='GET'>";
$form .= "<input type='hidden' name='action' value='getPartsForSet' />";
$form .= "<p><select name='gigID'>";

$sql = "SELECT DISTINCT gigID, name, gigDate FROM gig ORDER BY gigDate DESC, name ASC";
	$i = 1;
    	foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
    	    if(11!=$row[0]){
        		$check = "<p><option value=" . $row[0] . ">" . $row[1] . " " . $row[2] . "</p>";
        		$form = $form . $check;
    	    }
    	}
$form .= "</p><p><input type='submit' value='Get parts (output to output folder)'></p></form></fieldset>";
return $form;
}


function getSetPartsOutput( $gigID, $directoryBase ){

$sql = "SELECT DISTINCT partName from view_efilePartSetList2 where gigID = " . $gigID . " ORDER BY partName ASC ";
    	foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
        		self::pdfFromGigExplicit($gigID, $row[0], $directoryBase, "Gig" . $gigID . $row[0] );
        		echo $row[0] . " ";
    	}

}


function pdfFromGig( $input, $dummyGigID=-1, $dummyPart=-1){
    $includeMusic = false;
if (isset($input['includeMusic'])){
    if ( 'include' == $input['includeMusic']){
        $includeMusic = true;
    } 
}    
if (isset($input['gigID']) && isset($input['part'])){
    self::deleteOutput( getcwd() );
    Connection::saveRequest($input);    
    return self::pdfFromGigExplicit($input['gigID'], $input['part'], getcwd(), $includeMusic );
}
}

function pdfFromGigExplicit($gigID, $partName, $directoryBase, $includeMusic = true, $outputStem=''){

$where="";
$partWhere="";
$distinctOrder = " ,v.setListOrder ";
$orderByFile = " ORDER BY setListOrder ASC ";
$orderByList = " ORDER BY v.setListOrder ASC ";
if (isset($gigID)){
    $where .= " OR g.gigID = '" . $gigID . "' ";
}
if (isset($partName)){
    $partWhere .= " OR partName='" . $partName . "' ";
}


$pdf = new Fpdi\Fpdi();

$arrange = array();
if (isset($partName)){
    $sqlCharts = "SELECT DISTINCTROW IF(AC.arrCount>1, CONCAT(S.name, ', ', VA.arrangerFirstName, ' ', VA.arrangerLastName), S.name) as songName, g.name, g.gigDate, c.countParts, v.arrangementID, XXX.countPages " . $distinctOrder . " FROM (setList2 as v INNER JOIN arrangement AS A on v.arrangementID=A.arrangementID INNER JOIN song as S on S.songID = A.songID 
    INNER JOIN view_arrangement AS VA on VA.arrangementID = A.arrangementID
    INNER JOIN (SELECT COUNT(*) as arrCount, songID FROM arrangement AS A GROUP BY songID) AS AC ON AC.songID = S.songID 
    INNER JOIN gig as g ON g.gigID = v.gigID) LEFT JOIN (SELECT count(*) as countParts, arrangementID from  view_efilePartSetList2 WHERE  partName='" . $partName . "' GROUP BY arrangementID) as c on c.arrangementID = v.arrangementID   LEFT JOIN (SELECT SUM(countPages) as countPages, arrangementID FROM (SELECT 1 + endPage-startPage as countPages, arrangementID FROM (SELECT DISTINCTROW fileName, startPage, endPage, arrangementID FROM view_efilePart as g WHERE partName='" . $partName . "') AS PP ) AS X GROUP BY arrangementID) AS XXX ON XXX.arrangementID = A.arrangementID WHERE ( 0 " . $where . " ) " . $orderByList . ";";
} else {
    $sqlCharts = "SELECT 1 from dual where false;";
}
$pageCount=1;
	$rowcount = 0;
    	foreach(Connection::listMultiple( $sqlCharts ) AS $index=>$row ){
	$arrange[] = $row[4];
        if (0==$rowcount){
	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
            $pdf->Write(5,$row[1] . " " . $row[2] . "\n\n\n");
        }
        if ((NULL === $row[3])){
		$pdf->SetTextColor(255,255,255);
	} else {
		$pdf->SetTextColor(0,0,0);
	}
	if (isset($partName)){
	$pdf->Write(5,$pageCount . "  (" . $partName . ") ");
	}
	$pdf->SetTextColor(0,0,0);
        $pdf->Write(5,$row[0] . "\n");
        if (!(NULL === $row[3])){
		$pageCount = $pageCount + $row[5];
	}
        $rowcount++;
	}
if ($includeMusic){
Arrangement::getAllNotes($pdf, $arrange);

    $sql = "SELECT DISTINCTROW fileName, startPage, endPage, formatID, setListOrder FROM view_efilePartSetList2 as g WHERE  ( 0 " . $partWhere . ") AND ( 0 " . $where . " ) " . $orderByFile . ";";

    foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
	$pdf->setSourceFile( $directoryBase .  "/" .  "pdf/" . $row[0]);
	for ($i = $row[1], $ii = $row[2]; $i <= $ii; $i++){
		$tplIdx = $pdf->importPage($i);
		if ( 0 == $row[3] ){
			$pdf->AddPage();
			$pdf->useImportedPage($tplIdx, 10, 10, 200);
		} else {
			$pdf->AddPage('L');
			$pdf->useImportedPage($tplIdx, 10, -2, 280);
		}
		// use the imported page and place it at point 10,10 with a width of 200 mm
	}
    }
} else { // end if ($includeMusic)
$pdf->Write(5,"\n(Notes and music excluded)\n");
}
$yourFile =  'output/'. $outputStem . md5(time()) . 'myfile.pdf';
$pdf->Output($directoryBase . "/" . $yourFile,'F');            
return $yourFile;
}


function postNewSetList( $input=array()){

$isGig = 0;    
if (isset($input['isGig'])){
	if ('isPublic'==$input['isGig']){
		$isGig = 1;
	}
}

$sqlNewGig = "insert into gig (name, gigDate, isGIG) VALUES( '".$input['gigName'] ."', '".$input['gigDate']."', " . $isGig . ");";
$result = Connection::my_execute( $sqlNewGig);


}

} // end class Gig
