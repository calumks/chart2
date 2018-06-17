<?php
use \setasign\Fpdi;

function pdfFromGig($dummyGigID=-1, $dummyPart=-1){
    $includeMusic = false;
if (isset($_GET['includeMusic'])){
    if ( 'include' == $_GET['includeMusic']){
        $includeMusic = true;
    } 
}    
if (isset($_GET['gigID']) && isset($_GET['part'])){
    deleteOutput( getcwd() );
    include "saveRequest.php";
    saveRequest();    
    return pdfFromGigExplicit($_GET['gigID'], $_GET['part'], getcwd(), $includeMusic );
}
}

function pdfFromGigExplicit($gigID, $partName, $directoryBase, $includeMusic = true, $outputStem=''){

//print_r($_GET);
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
    $sqlCharts = "SELECT DISTINCTROW S.name as songName, g.name, g.gigDate, c.countParts, v.arrangementID, XXX.countPages " . $distinctOrder . " FROM (setList2 as v INNER JOIN arrangement AS A on v.arrangementID=A.arrangementID INNER JOIN song as S on S.songID = A.songID INNER JOIN gig as g ON g.gigID = v.gigID) LEFT JOIN (SELECT count(*) as countParts, arrangementID from  view_efilePartSetList2 WHERE  partName='" . $partName . "' GROUP BY arrangementID) as c on c.arrangementID = v.arrangementID   LEFT JOIN (SELECT SUM(countPages) as countPages, arrangementID FROM (SELECT 1 + endPage-startPage as countPages, arrangementID FROM (SELECT DISTINCTROW fileName, startPage, endPage, arrangementID FROM view_efilePart as g WHERE partName='" . $partName . "') AS PP ) AS X GROUP BY arrangementID) AS XXX ON XXX.arrangementID = A.arrangementID WHERE ( 0 " . $where . " ) " . $orderByList . ";";
} else {
    $sqlCharts = "SELECT 1 from dual where false;";
}
///echo $sqlCharts;
$pageCount=1;
	$rowcount = 0;
    	foreach(listMultiple( $sqlCharts ) AS $index=>$row ){
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
//}
//echo "<pre>" . __FILE__ . print_r($arrange,1) . "</pre>";
if ($includeMusic){
require_once('getAllNotes.php');
getAllNotes($pdf, $arrange);

    $sql = "SELECT DISTINCTROW fileName, startPage, endPage, formatID, setListOrder FROM view_efilePartSetList2 as g WHERE  ( 0 " . $partWhere . ") AND ( 0 " . $where . " ) " . $orderByFile . ";";

    foreach( listMultiple( $sql ) AS $index=>$row ){
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
//}
} else { // end if ($includeMusic)
$pdf->Write(5,"\n(Notes and music excluded)\n");
}
$yourFile =  'output/'. $outputStem . md5(time()) . 'myfile.pdf';
$pdf->Output($directoryBase . "/" . $yourFile,'F');            
return $yourFile;
}

function deleteOutput( $directoryBase ){
$files = glob($directoryBase . '/output/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}
    
}
