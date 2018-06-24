<?php

use \setasign\Fpdi;

class Arrangement{


function getAllNotes($pdf, $arrangements){
$filter = "(";
foreach ($arrangements as $key=>$value){
	$filter .= $value . ", ";
}
$filter .= "-999)";

$sqlCharts = "SELECT name, description, noteText, date_format(noteDate, '%Y-%m-%d') FROM view_note  WHERE noteID in (SELECT noteID from note where publicationID in (SELECT publicationID from publication where arrangementID IN " . $filter . ")) ORDER BY name ASC, noteDate DESC"; 
//echo $sqlCharts;
	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
	$rowcount = 0;
    	foreach( Connection::listMultiple( $sqlCharts ) AS $index=>$row ){
            $pdf->Write(5,$row[0] . " (" . $row[1] . ")\n\n");
            $pdf->Write(5,$row[2] . "\n\n\n\n"); // no date
	}
            
return $pdf;
}


function getArrangementForm( $arrangementID){

$form = "";
include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "SELECT noteText from note  INNER JOIN publication as PUB on note.publicationID = PUB.publicationID INNER JOIN arrangement as A on A.arrangementID = PUB.arrangementID  WHERE A.arrangementID=" . $arrangementID . " ORDER BY note.noteID ASC ";
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

$sql = "SELECT DISTINCTROW P.partID, P.name as partName, song.name, VA.arrangerFirstName, VA.arrangerLastName from efilePart as EF INNER JOIN efile as E on E.efileID = EF.efileID INNER JOIN publication as PUB on E.publicationID = PUB.publicationID INNER JOIN arrangement as A on A.arrangementID = PUB.arrangementID INNER JOIN view_arrangement AS VA ON VA.arrangementID = A.arrangementID INNER JOIN song ON song.songID = A.songID INNER JOIN part as P ON EF.partID = P.partID inner join section as S on S.sectionID = P.minSectionID WHERE A.arrangementID=" . $arrangementID . " ORDER BY S.printOrder ASC, P.partID ASC ";
$result = mysqli_query($link, $sql);
$songName = "NOT FOUND";
if ($result){
	$i = 1;
    	while($row = mysqli_fetch_row( $result )) {
	        $check = "<p>  <a href='.?action=getChart&arrangement[]= " . $arrangementID . "&part[]=" . $row[0] . "'>"  . $row[1] . "</a></p>\n";
		$form = $form . $check;
		$songName = $row[2] ." arranged by " . $row[3] . " " .$row[4];
    	}
}

$form = "<fieldset><legend>" . $songName . "</legend>\n" . $form . "</fieldset>\n"; 
return $form;
}



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


function pdfFromGet( $input){

$where=" OR V.efileID=-999 ";
$partWhere=" OR V.partID=-999 ";
$arrangeWhere=" OR V.efileID in (SELECT efileID from efile where publicationID in (SELECT publicationID from publication WHERE arrangementID IN (-999";
if (isset($input['chart'])){
   foreach ($input['chart'] AS $index=>$value){
      $where .= " OR V.efileID='" . $value . "' ";
   }
}
if (isset($input['part'])){
    foreach ($input['part'] AS $index=>$value){
      $partWhere .= " OR P.partID='" . $value . "' ";
    }
}
if (isset($input['arrangement'])){
    foreach ($input['arrangement'] AS $index=>$value){
      $arrangeWhere .= "," .   $value;
    }
}
$arrangeWhere .=")))";
$where .=   $arrangeWhere;
$sql = "SELECT V.fileName, V.startPage, V.endPage, V.formatID, V.partName, V.songName FROM view_efilePart AS V INNER JOIN part as P on P.partID = V.partID INNER JOIN section AS S on P.minSectionID = S.sectionID where 1 AND ( 0 " . $partWhere . ") AND ( 0 " . $where . " ) ORDER BY V.songName, S.printOrder ASC, P.partID ASC";

$pdf = new Fpdi\Fpdi();

$pageCount = 1;
	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
    	foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
 	$pdf->Write(5,$pageCount . "  (" . $row[4] . ") ");
        $pdf->Write(5,$row[5] . "\n");
	$pageCount = $pageCount + 1 + $row[2] - $row[1];
	}
if (isset($input['arrangement'])){
	self::getAllNotes($pdf, $input['arrangement']);
}
    	foreach( Connection::listMultiple( $sql ) AS $index=>$row ){
	$pdf->setSourceFile("pdf/" . $row[0]);
	for ($i = $row[1], $ii = $row[2]; $i <= $ii; $i++){
		$tplIdx = $pdf->importPage($i);
		if (0 == $row[3]){
			$pdf->AddPage();
			$pdf->useImportedPage($tplIdx, 10, 10, 200);
		} else {
			$pdf->AddPage('L');
			$pdf->useImportedPage($tplIdx, 10, -2, 280);
		}
	}
        }
Connection::saveRequest($input);
$yourFile =  'output/'. md5(time()) . 'myfile.pdf';
$pdf->Output(getcwd() . "/" . $yourFile,'F');
return $yourFile;
}


} // end class Arrangement
