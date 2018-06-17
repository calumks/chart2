<?php

function listAll( $partID=''){
require_once('fpdf/fpdf.php');

/*
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output();
*///function pdfFromGig($dummyGigID, $dummyPart){

$title = "";

class myListAllPDF extends FPDF
{
var $col = 0;

function SetCol($col)
{
    // Move position to a column
    $this->col = $col;
    $x = 10+$col*100;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

function AcceptPageBreak()
{
    // Method accepting or not automatic page break
    if($this->col<1)
    {
        // Go to next column
        $this->SetCol($this->col+1);
        // Set ordinate to top
        $this->SetY($this->y0);
        // Keep on page
        return false;
    }
    else
    {
        // Go back to first column
        $this->SetCol(0);
        // Page break
        return true;
    }
}
}


$where="";
$partWhere="";
$distinctOrder = " ,v.setListOrder ";
$orderByFile = " ORDER BY setListOrder ASC ";
$orderByList = " ORDER BY v.setListOrder ASC ";
        $where .= " OR 1  ";
        $distinctOrder = "  ";
        $orderByFile = " ORDER BY songname ASC";
        $orderByList = " ORDER BY songname ASC";
    $partWhere .= " OR partName='Drums' ";
require_once('fpdf/fpdf.php');

$pdf = new myListAllPDF();
include "mysql-cred.php";

if ('' == $partID){
    $partID = 0;
}

    $partList = array();

if (-999==$partID){
    $wherePart = " 1 ";   
    $bIsSection = false;
} elseif (-123==$partID){
    $partList = array(6); ///hard-coded Database ID (OK, probably only labels change)
    $bIsSection = true; 
} else {
    $wherePart = " partID = " . $partID;
    $bIsSection = false;
}
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 


//$sqlParts = "SELECT P.partID from part as P WHERE " . $wherePart . " order by P.partID ASC";
if (!$bIsSection){
    $sqlParts = "SELECT P.partID, P.name from part as P INNER JOIN section AS S on P.minSectionID = S.sectionID WHERE " . $wherePart . "  order by S.printOrder ASC,  P.partID ASC";
//echo $sqlParts;
$arrange = array();
    $resultP = mysqli_query($link, $sqlParts);
    $rowCount = 0;
    if ($resultP){
    	while($rowP = mysqli_fetch_row( $resultP )) {
    	    $partList[] =  $rowP[0];
//    	    echo $rowCount;
    	    $rowCount++;
    	}
    }
}
//    $sqlCharts = "SELECT DISTINCTROW CONCAT(IF(A.isInPads=1,'','*'), S.name) as songName, 'Thornbury Swing Band', NOW(), c.countParts, A.arrangementID, 1+X.countPages  FROM (arrangement AS A INNER JOIN song as S on S.songID = A.songID) LEFT JOIN (SELECT count(*) as countParts, arrangementID from  view_efilePart GROUP BY arrangementID) as c on c.arrangementID = A.arrangementID  LEFT JOIN (SELECT SUM(endPage)-SUM(startPage) as countPages, arrangementID FROM (SELECT DISTINCTROW fileName, startPage, endPage, arrangementID FROM view_efilePart as g ) AS PP GROUP BY arrangementID ) AS X ON X.arrangementID = A.arrangementID  WHERE ( c.countParts > 0   ) ORDER BY S.Name ASC;";

//$partID = 13; // Alto Sax 1
//$partID = 18; // Vocal Solo
//$partID = 12; // Piano
//$partID = 19; // Vocal Soprano

foreach ($partList AS $partID){
$title = "";
if ($partID > 0){
    if (!$bIsSection){
        $sqlCharts = "SELECT shortName, name from part where partid=" . $partID . ";";
    } else {
        $sqlCharts = "SELECT shortName, name from section where sectionid=" . $partID . ";";
    }
//    echo $sqlCharts;
    $result = mysqli_query($link, $sqlCharts);
    if ($result){
    	while($row = mysqli_fetch_row( $result )) {
    	    $partShortName =  $row[0];
    	    $partLongName =  $row[1];
	    }
    }
    if (!$bIsSection){
        $sqlCharts = "SELECT DISTINCTROW CONCAT(IF(A.isInPads=1,'','*'), S.name, IF(c2.countParts>0,'',' (No ".$partShortName.")')) as songName, 'Thornbury Swing Band (". $partLongName . ")', NOW(), c.countParts, A.arrangementID, 1+X.countPages  FROM (arrangement AS A INNER JOIN song as S on S.songID = A.songID) LEFT JOIN (SELECT count(*) as countParts, arrangementID from  view_efilePart GROUP BY arrangementID) as c on c.arrangementID = A.arrangementID 
        LEFT JOIN (SELECT count(*) as countParts, arrangementID from  view_efilePart WHERE partID = " . $partID . " GROUP BY arrangementID) as c2 on c2.arrangementID = A.arrangementID 
        LEFT JOIN (SELECT SUM(endPage)-SUM(startPage) as countPages, arrangementID FROM (SELECT DISTINCTROW fileName, startPage, endPage, arrangementID FROM view_efilePart as g ) AS PP GROUP BY arrangementID ) AS X ON X.arrangementID = A.arrangementID  WHERE ( c.countParts > 0   ) ORDER BY S.Name ASC;";
    } else {
        $sqlCharts = "SELECT DISTINCTROW CONCAT(IF(A.isInPads=1,'','*'), S.name, IF(c2.countParts>0,'',' (No ".$partShortName.")')) as songName, 'Thornbury Swing Band (". $partLongName . ")', NOW(), c.countParts, A.arrangementID, 1+X.countPages  FROM (arrangement AS A INNER JOIN song as S on S.songID = A.songID) LEFT JOIN (SELECT count(*) as countParts, arrangementID from  view_efilePart GROUP BY arrangementID) as c on c.arrangementID = A.arrangementID 
        LEFT JOIN (SELECT count(*) as countParts, VVV.arrangementID from  view_efilePart AS VVV INNER JOIN part as PPP on VVV.partID=PPP.partID INNER JOIN section as SSS ON SSS.sectionID = PPP.minSectionID WHERE sectionID = " . $partID . " GROUP BY arrangementID) as c2 on c2.arrangementID = A.arrangementID 
        LEFT JOIN (SELECT SUM(endPage)-SUM(startPage) as countPages, arrangementID FROM (SELECT DISTINCTROW fileName, startPage, endPage, arrangementID FROM view_efilePart as g ) AS PP GROUP BY arrangementID ) AS X ON X.arrangementID = A.arrangementID  WHERE ( c.countParts > 0   ) ORDER BY S.Name ASC;";
    }
    
}

 //   echo "\n\n <br><br> sqlCharts"; echo "\n\n <br><br>";echo $sqlCharts; echo "\n\n <br><br>";

//echo "<pre>" . __FILE__ . print_r($sqlCharts,1) . "</pre>";
$result = mysqli_query($link, $sqlCharts);
$pageCount=1;
$txtIndex = "";
$i =1;
if ($result){
	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
	$rowcount = 0;
    	while($row = mysqli_fetch_row( $result )) {
//echo $row[0];
	$arrange[] = $row[4];
        if (0==$rowcount){
            $title .= $row[1] . " (* = not in pads) " . $row[2] . "\n\n\n";
        }
        if ((NULL === $row[3])){
		$pdf->SetTextColor(255,255,255);
	} else {
		$pdf->SetTextColor(0,0,0);
	}
//	    $pdf->MultiCell(60,5,$txt);
//    $this->Ln();
    // Mention
//    $this->SetFont('','I');
//    $this->Cell(0,5,'(end of excerpt)');
    // Go back to first column
//    $this->SetCol(0);
    
//	if (isset($_GET['part'])){
//        if (!(NULL === $row[3])){
//            $txtIndex .= $pageCount . "  (" . $_GET['part'] . ") ";
//        }
//	$pdf->MultiCell(60,5,$pageCount . "  (" . $_GET['part'] . ") ");
//	}
	$pdf->SetTextColor(0,0,0);
            if ($i < 10) $txtIndex .=  "  ";
            $txtIndex .= $i++ . ".  " . $row[0] . "\n";
//        $pdf->MultiCell(60,5,$row[0] . "\n");
        if (!(NULL === $row[3])){
		$pageCount = $pageCount + $row[5];
	}
        $rowcount++;
	}
}
//echo $txtIndex;

    $pdf->SetFont('Arial','',12);
    $pdf->SetFillColor(200,220,255);
    
    $pdf->Cell(0,6,$title,0,1,'L',true);
    $pdf->Ln(4);
    // Save ordinate
    $pdf->y0 = $pdf->GetY();
    
	$pdf->MultiCell(100,5,$txtIndex);
    $pdf->Ln(4);
//echo "<pre>" . __FILE__ . print_r($arrange,1) . "</pre>";

$sqlCharts = "SELECT name, description, noteText, date_format(noteDate, '%Y-%m-%d') FROM view_note  ORDER BY name ASC, noteDate DESC"; 
//echo $sqlCharts;
$notes = "";
$oldTitle = "";
$result = mysqli_query($link, $sqlCharts);
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
//    	    if( $oldTitle <> $row[0] . " (" . $row[1] . ")"){
    	    if( $oldTitle <> $row[0] ){
    	        if( $oldTitle<>""){
    	            $notes .= "\n";
    	        }
//    	        $oldTitle = $row[0] . " (" . $row[1] . ")";
    	        $oldTitle = $row[0] ;
//    	        $notes .= $oldTitle . "\n";
    	        $notes .= $oldTitle . ": ";
    	    }
//            $notes .= $row[3] . " " . $row[2] . "\n";
            $notes .=  $row[2] . "\n"; // no date
	}
}
    $pdf->SetCol(0);
    	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
        
    $pdf->SetFont('Arial','',8);
    $pdf->SetFillColor(200,220,255);
    
    $pdf->Cell(0,6,"Notes",0,1,'L',true);
    $pdf->Ln();
    // Save ordinate
    $pdf->y0 = $pdf->GetY();
    
	$pdf->MultiCell(90,5,$notes);
    $pdf->SetCol(0);
//    $pdf->Ln();

} // foreach ($partList AS $partID){

//echo "<pre>" . __FILE__ . print_r($yourFile,1) . "</pre>";
$yourFile =  'output/'. md5(time()) . 'index.pdf';
$pdf->Output(getcwd() . "/" . $yourFile,'F');
//$pdf->Output();      
//echo "<pre>" . __FILE__ . print_r(getcwd() . $yourFile,1) . "</pre>";      
return $yourFile;

//$pdf->Output();
}

function listAllLink(){
    return listAll();
}
