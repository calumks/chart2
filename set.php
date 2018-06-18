<?php

function copySetList( $sourceGigID, $targetGigID){

$sqlDeleteSetList = "DELETE FROM setList2 WHERE gigID = " . $targetGigID;
$result = my_execute( $sqlDeleteSetList);
$sqlCopySetList = "INSERT INTO setList2(arrangementID,  gigID, setListOrder) SELECT arrangementID,  " . $targetGigID . ", setListOrder FROM setList2 WHERE gigID = " . $sourceGigID;
$result = my_execute( $sqlCopySetList );

}


function postNewSetList(){

$isGig = 0;    
if (isset($_POST['isGig'])){
	if ('isPublic'==$_POST['isGig']){
		$isGig = 1;
	}
}

$sqlNewGig = "insert into gig (name, gigDate, isGIG) VALUES( '".$_POST['gigName'] ."', '".$_POST['gigDate']."', " . $isGig . ");";
$result = my_execute( $sqlNewGig);


}

function getNewSetListForm(){

$form = "<fieldset><legend>New set</legend>";
$form .= "<form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='addSetList' />";
$form .= "<p>Gig name<textarea name='gigName'>Gig name (enter here)</textarea></p> ";
$form .= "<p>Gig date<input type='date' name='gigDate' ></p> ";
$form .= "<p>Performance (leave unticked if it's a practice)<input type='checkbox' name='isGig' value='isPublic' ></p> ";
$form .= "<input type='submit' value='ADD SET'></form>";
$form .= "</fieldset>";
return $form;
}

