<?php
use \setasign\Fpdi;
//function pdfFromGig($dummyGigID, $dummyPart){
function pdfFromGig($dummyGigID=-1, $dummyPart=-1){
    
if (isset($_GET['gigID']) && isset($_GET['part'])){
    deleteOutput( getcwd() );
    include "saveRequest.php";
    saveRequest();    
    return pdfFromGigExplicit($_GET['gigID'], $_GET['part'], getcwd());
}
}

function pdfFromGigExplicit($gigID, $partName, $directoryBase, $outputStem=''){

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
require_once('fpdf/fpdf.php');
require_once('fpdi2/src/autoload.php');

$pdf = new Fpdi\Fpdi();
include "mysql-cred.php";

$arrange = array();
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 
if (isset($partName)){
    $sqlCharts = "SELECT DISTINCTROW S.name as songName, g.name, g.gigDate, c.countParts, v.arrangementID, 1+X.countPages " . $distinctOrder . " FROM (setList2 as v INNER JOIN arrangement AS A on v.arrangementID=A.arrangementID INNER JOIN song as S on S.songID = A.songID INNER JOIN gig as g ON g.gigID = v.gigID) LEFT JOIN (SELECT count(*) as countParts, arrangementID from  view_efilePartSetList2 WHERE  partName='" . $partName . "' GROUP BY arrangementID) as c on c.arrangementID = v.arrangementID   LEFT JOIN (SELECT SUM(endPage)-SUM(startPage) as countPages, arrangementID FROM (SELECT DISTINCTROW fileName, startPage, endPage, arrangementID FROM view_efilePart as g WHERE partName='Alto Sax 1') AS PP GROUP BY arrangementID ) AS X ON X.arrangementID = A.arrangementID WHERE ( 0 " . $where . " ) " . $orderByList . ";";
} else {
    $sqlCharts = "SELECT 1 from dual where false;";
}
$result = mysqli_query($link, $sqlCharts);
$pageCount=1;
if ($result){
	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
	$rowcount = 0;
    	while($row = mysqli_fetch_row( $result )) {
//echo $row[0];
	$arrange[] = $row[4];
        if (0==$rowcount){
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
}
//echo "<pre>" . __FILE__ . print_r($arrange,1) . "</pre>";
require_once('getAllNotes.php');
getAllNotes($pdf, $arrange);

    $sql = "SELECT DISTINCTROW fileName, startPage, endPage, formatID, setListOrder FROM view_efilePartSetList2 as g WHERE  ( 0 " . $partWhere . ") AND ( 0 " . $where . " ) " . $orderByFile . ";";

$result = mysqli_query($link, $sql);
if ($result){
    while($row = mysqli_fetch_row( $result )) {
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
}
mysqli_close( $link );
$yourFile =  'output/'. $outputStem . md5(time()) . 'myfile.pdf';
//echo "<pre>" . __FILE__ . print_r($yourFile,1) . "</pre>";
$pdf->Output($directoryBase . "/" . $yourFile,'F');
//$pdf->Output();            
return $yourFile;
}

function deleteOutput( $directoryBase ){
$files = glob($directoryBase . '/output/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}
    
}