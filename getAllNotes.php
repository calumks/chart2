<?php
function getAllNotes($pdf, $arrangements){
include "mysql-cred.php";
$filter = "(";
foreach ($arrangements as $key=>$value){
	$filter .= $value . ", ";
}
$filter .= "-999)";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 
$sqlCharts = "SELECT name, description, noteText, date_format(noteDate, '%Y-%m-%d') FROM view_note  WHERE noteID in (SELECT noteID from note where publicationID in (SELECT publicationID from publication where arrangementID IN " . $filter . ")) ORDER BY name ASC, noteDate DESC"; 
//echo $sqlCharts;
$result = mysqli_query($link, $sqlCharts);
if ($result){
	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
//    $pdf->Write(5,"Notes (for everything whether selected or not -- filter this if it's too much) . \n\n\n");
	$rowcount = 0;
    	while($row = mysqli_fetch_row( $result )) {
//echo $row[0];
            $pdf->Write(5,$row[0] . " (" . $row[1] . ")\n\n");
//            $pdf->Write(5,$row[3] . " " . $row[2] . "\n\n\n\n");
            $pdf->Write(5,$row[2] . "\n\n\n\n"); // no date
	}
}
            
return $pdf;
}
