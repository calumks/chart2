<?php


function deleteNote($noteID){
    if ($noteID > 0){
        include "mysql-cred.php";
        $sql = "DELETE FROM note where noteID='" . $noteID . "'";
        $link  = mysqli_connect( $servername, $username, $password, $database);
        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error);
        } 
        $result = mysqli_execute(mysqli_prepare($link, $sql));
    }
}


function updateNote($noteID, $noteText){
    if ($noteID > 0 && strlen($noteText) > 3){
        include "mysql-cred.php";
        $sql = "UPDATE note SET noteText='" . $noteText . "', noteDate = NOW() where noteID='" . $noteID . "'";
        $link  = mysqli_connect( $servername, $username, $password, $database);
        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error);
        } 
        $result = mysqli_execute(mysqli_prepare($link, $sql));
    }
}


function addNote($publicationID, $noteText){
    if ($publicationID > 0 && strlen($noteText) > 3){
        include "mysql-cred.php";
        $sql = "INSERT INTO note(publicationID, noteText, noteDate) VALUES('" . $publicationID . "','". $noteText . "', NOW())";
        $link  = mysqli_connect( $servername, $username, $password, $database);
        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error);
        } 
        $result = mysqli_execute(mysqli_prepare($link, $sql));
    }
}

function getEditNoteForm(){
include "mysql-cred.php";
$form = "";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 
$sqlCharts = "SELECT N.noteID, V.name, V.description, N.noteText, date_format(N.noteDate, '%Y-%m-%d'), N.publicationID FROM note as N, view_publication as V WHERE V.publicationID=N.publicationID  ORDER BY name ASC, noteDate DESC"; 
//echo $sqlCharts;
$result = mysqli_query($link, $sqlCharts);
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
            $form .= "<div>";
            $form .= "<fieldset><legend>" . $row[1] . " " . $row[2] . " " . $row[4] . "</legend>";
            $form .= "<form method='POST' action=''>";
            $form .= "<textarea rows='5' cols='60' name='noteText'>" . $row[3] . "</textarea>";
            $form .= "<input type='hidden' value='updateNote' name='action'>";
            $form .= "<input type='hidden' value='" . $row[0] . "' name='noteID'>";
            $form .= "<p><input type='submit' value='UPDATE'></p>";
            $form .= "</form>";
            $form .= "<form method='POST' action=''>";
            $form .= "<input type='hidden' value='deleteNote' name='action'>";
            $form .= "<input type='hidden' value='" . $row[0] . "' name='noteID'>";
            $form .= "<input type='submit' value='Delete'>";
            $form .= "</form>";
            $form .= "</fieldset>";
            $form .= "</div>";
	}
}
            
return $form;
}


function getNewNoteForm(){
include "mysql-cred.php";
$form = "";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error);
} 
$sqlCharts = "SELECT V.publicationID, V.name, V.description FROM view_publication as V  ORDER BY V.name ASC"; 
//echo "NEW" . $sqlCharts;
$result = mysqli_query($link, $sqlCharts);
if ($result){
            $form .= "<div>";
            $form .= "<fieldset><legend>New note</legend>";
            $form .= "<form method='POST' action=''>";
            $form .= "<select name='publicationID'>";
            $form .= "<option value='-1'></option>";
    	while($row = mysqli_fetch_row( $result )) {

            $form .= "<option value='". $row[0] . "'>" . $row[1] . " " . $row[2] . "</option>";
    	}
            $form .= "</select>";
            $form .= "<div><textarea rows='5' cols='60' name='noteText'></textarea></div>";
            $form .= "<input type='hidden' value='addNote' name='action'>";
            $form .= "<p><input type='submit' value='Add'></p>";
            $form .= "</form>";
            $form .= "</fieldset>";
            $form .= "</div>";
}
            
return $form;
}
