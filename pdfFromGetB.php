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
//echo "\n\n";
//echo $sql;
//echo "\n\n";
require_once('fpdf/fpdf.php');
require_once('fpdi2/src/autoload.php');

$pdf = new Fpdi\Fpdi();

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 

$result = mysqli_query($link, $sql);
$pageCount = 1;
if ($result){
	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
    	while($row = mysqli_fetch_row( $result )) {
 	$pdf->Write(5,$pageCount . "  (" . $row[4] . ") ");
        $pdf->Write(5,$row[5] . "\n");
	$pageCount = $pageCount + 1 + $row[2] - $row[1];
	}
}
require_once('getAllNotes.php');
getAllNotes($pdf, $_GET['arrangement']);

$result = mysqli_query($link, $sql);
if ($result){
    while($row = mysqli_fetch_row( $result )) {
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
		// use the imported page and place it at point 10,10 with a width of 200 mm
//		$pdf->useImportedPage($tplIdx);
//		$pdf->useImportedPage($tplIdx, 10, 10, 200);
	}
    }
}
mysqli_close( $link );
include "saveRequest.php";
saveRequest();
//$yourFile =  '/output/myfile.pdf';
$yourFile =  'output/'. md5(time()) . 'myfile.pdf';
$pdf->Output(getcwd() . "/" . $yourFile,'F');
//$pdf->Output();      
//echo "<pre>" . __FILE__ . print_r(getcwd() . $yourFile,1) . "</pre>";      
return $yourFile;
}
//pdfFromGet();
