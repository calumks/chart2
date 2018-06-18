<?php

function my_execute( $sql ){

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$result = mysqli_execute(mysqli_prepare($link, $sql));
mysqli_close($link);
return $result;    
}


function my_insert_id( $sql ){

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$result = mysqli_execute(mysqli_prepare($link, $sql));
$lastID = mysqli_insert_id( $link );
mysqli_close($link);
return $lastID;    
}


function addToPads( $arrangementID, $iAdd){

$sqlUpdate = "update arrangement set isInPads = " . $iAdd . " WHERE arrangementID = ". $arrangementID . ";";
//echo $sqlUpdate;
$result = my_execute( $sqlUpdate);

}


function postNewPerson(){

$sql = "insert into person (firstName, lastName, nickName) VALUES( '".$_POST['firstName'] ."', '".$_POST['lastName']."', '".$_POST['nickName']."');";
$result = my_execute( $sql);

}


function postNewSong(){
    
$sqlNewSong = "insert into song  (name) VALUES( '".$_POST['songName'] ."');";
$result = my_execute( $sqlNewSong );

}

function postNewArrangement(){

$sqlNewGig = "insert into gig (name, gigDate) VALUES( '".$_POST['gigName'] ."', '".$_POST['gigDate']."');";
$result = my_execute( $sqlNewGig );

foreach ($_POST['arrangement'] as $key => $value) {
    if (!""==$value){        
        $sqlNewSetMember = "insert into setList2 (arrangementID,  gigID, setListOrder) select '" . $key . "',  t1.gigID, '". $value ."' from  gig as t1 where t1.name='" . $_POST['gigName'] . "' and t1.gigDate='".$_POST['gigDate']."' ;";
        
        $result = my_execute( $sqlNewSetMember );

    }
}

echo "</pre>"; // ????

}

function getFormPads( $arrID, $isInPads, $arrLabel){
    if ($isInPads){
        return getRemoveFromPadsForm( $arrID, $arrLabel);
    }
    else{
        return getAddToPadsForm( $arrID, $arrLabel);
    }
}

function getAddToPadsForm( $arrID, $arrLabel){

$form = "<form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='addToPads' />";
$form .= "<input type='hidden' name='arrangementID' value='" . $arrID . "' />";
$form .= "<input type='submit' value='Add " . $arrLabel . " to pads'></form>";
return $form;
}

function getRemoveFromPadsForm( $arrID, $arrLabel){

$form = "<form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='removeFromPads' />";
$form .= "<input type='hidden' name='arrangementID' value='" . $arrID . "' />";
$form .= "<input type='submit' value='Remove " . $arrLabel . " from pads'></form>";
return $form;
}

function getNewSongForm(){

$form = "<form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='addSong' />";
$form .= "<p>Song name<textarea name='songName'>Song name (enter here)</textarea></p> ";
$form .= "<input type='submit' value='Add'></form>";
return $form;
}

function getNewPersonForm(){

$form = "<form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='addPerson' />";
$form .= "<p>First name<textarea name='firstName'>First name (enter here)</textarea></p> ";
$form .= "<p>Last name<textarea name='lastName'>Last name (enter here)</textarea></p> ";
$form .= "<p>Nickname<textarea name='nickName'></textarea></p> ";
$form .= "<input type='submit' value='Add'></form>";
return $form;
}

function getNewUserForm(){

$form = "";
$form .= "<fieldset><legend>New user</legend>";
$form .= "<form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='addNewUser' />";
$form .= "<p>Nickname<textarea name='newNickName'></textarea></p> ";
$form .= "<p>Email<input type='email' name='newEmail'></p> ";
$form .= "<input type='submit' value='Add new user'></form>";
$form .= "</fieldset>";
return $form;
}

function getSongs(){

$sql = "SELECT S.name, A.arrangementID, A.isInPads, CONCAT(S.Name, ' ', P.firstName, ' ', P.lastName) as ArrLabel from song AS S LEFT JOIN ( arrangement as A INNER JOIN person as P ON A.arrangerPersonID = P.personID)  ON A.songID = S.songID order by S.name  ASC";
$return = "<table> \n <tr><th>Name<th>Pads</tr> \n";
    	foreach( listMultiple( $sql ) AS $index=>$row ){
		$return .= "<tr><td>" . $row[0] . "</td><td>". getFormPads($row[1], $row[2], $row[3]) . "</td></tr> \n";
    	}

$return .= "</table>";
return $return;
}

function getPeople(){

$sql = "SELECT firstName, lastName, nickName from person order by lastName  ASC";
$return = "<table> \n <tr><th>First Name<th>Last Name<th>Nick Name</tr> \n";
    	foreach( listMultiple( $sql ) AS $index=>$row ){
		$return .= "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr> \n";
    	}
$return .= "</table>";
return $return;
}

function getPublications(){

$sql = "SELECT name, description, arrangerFirstName, arrangerLastName FROM view_publication order by name ASC, arrangerLastName ASC, description ASC";
$return = "<table> \n <tr><th>Name<th>Description (publication)<th>Arranger First Name<th>Arranger Last Name</tr> \n";
    	foreach( listMultiple( $sql ) AS $index=>$row ){
		$return .= "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr> \n";
    	}
$return .= "</table>";
return $return;
}

function getNewSetArrangementForm(){

$form = "<form action = '' method='POST'>";
$form .= "<input type='hidden' name='action' value='addPersonSetList' />";
$form .= "<p>Gig name<textarea name='gigName'>Gig name (enter here)</textarea></p> ";
$form .= "<p>Gig date<input type='date' name='gigDate' ></p> ";
$form .= "<p>Set list (lowest numbers come first)</p> ";

$sql = "SELECT DISTINCT arrangementID, songName FROM view_efilePart ORDER BY songName ASC";
//echo $sql;
	$i = 1;
    	foreach( listMultiple( $sql ) AS $index=>$row ){
		$check = "<p><input type='text' name='arrangement[" . $row[0] . "]' > " . $row[1];
//		$check = "<p><input type='checkbox' name='arrangement[]' value=" . $row[0] . " > " . $row[1];
		$form = $form . $check;
    	}
$form .= "<input type='submit' value='submit'></form>";
return $form;
}
