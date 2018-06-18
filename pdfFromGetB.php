<?php
use \setasign\Fpdi;
function pdfFromGet(){

//print_r($_GET);
$where=" OR V.efileID=-999 ";
$partWhere=" OR V.partID=-999 ";
$arrangeWhere=" OR V.efileID in (SELECT efileID from efile where publicationID in (SELECT publicationID from publication WHERE arrangementID IN (-999";
if (isset($_GET['chart'])){
   foreach ($_GET['chart'] AS $index=>$value){
      $where .= " OR V.efileID='" . $value . "' ";
   }
}
if (isset($_GET['part'])){
    foreach ($_GET['part'] AS $index=>$value){
      $partWhere .= " OR P.partID='" . $value . "' ";
    }
}
if (isset($_GET['arrangement'])){
    foreach ($_GET['arrangement'] AS $index=>$value){
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
    	foreach( listMultiple( $sql ) AS $index=>$row ){
 	$pdf->Write(5,$pageCount . "  (" . $row[4] . ") ");
        $pdf->Write(5,$row[5] . "\n");
	$pageCount = $pageCount + 1 + $row[2] - $row[1];
	}
require_once('getAllNotes.php');
getAllNotes($pdf, $_GET['arrangement']);

    	foreach( listMultiple( $sql ) AS $index=>$row ){
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
include "saveRequest.php";
saveRequest();
$yourFile =  'output/'. md5(time()) . 'myfile.pdf';
$pdf->Output(getcwd() . "/" . $yourFile,'F');
return $yourFile;
}

