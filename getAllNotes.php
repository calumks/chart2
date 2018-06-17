<?php
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
    	foreach( listMultiple( $sqlCharts ) AS $index=>$row ){
            $pdf->Write(5,$row[0] . " (" . $row[1] . ")\n\n");
            $pdf->Write(5,$row[2] . "\n\n\n\n"); // no date
	}
            
return $pdf;
}
