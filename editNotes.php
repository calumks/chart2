<?php


function deleteNote($noteID){
    if ($noteID > 0){
        $sql = "DELETE FROM note where noteID='" . $noteID . "'";
        $result = my_execute( $sql);
    }
}


function updateNote($noteID, $noteText){
    if ($noteID > 0 && strlen($noteText) > 3){
        $sql = "UPDATE note SET noteText='" . $noteText . "', noteDate = NOW() where noteID='" . $noteID . "'";
        $result = my_execute( $sql);
    }
}


function addNote($publicationID, $noteText){
    if ($publicationID > 0 && strlen($noteText) > 3){
        $sql = "INSERT INTO note(publicationID, noteText, noteDate) VALUES('" . $publicationID . "','". $noteText . "', NOW())";
        $result = my_execute( $sql);
    }
}

function getEditNoteForm(){
$form = "";
$sqlCharts = "SELECT N.noteID, V.name, V.description, N.noteText, date_format(N.noteDate, '%Y-%m-%d'), N.publicationID FROM note as N, view_publication as V WHERE V.publicationID=N.publicationID  ORDER BY name ASC, noteDate DESC"; 
    	foreach( listMultiple( $sqlCharts ) AS $index=>$row ){
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
            
return $form;
}


function getNewNoteForm(){
$form = "";
$sqlCharts = "SELECT V.publicationID, V.name, V.description FROM view_publication as V  ORDER BY V.name ASC"; 
            $form .= "<div>";
            $form .= "<fieldset><legend>New note</legend>";
            $form .= "<form method='POST' action=''>";
            $form .= "<select name='publicationID'>";
            $form .= "<option value='-1'></option>";
    	foreach( listMultiple( $sqlCharts ) AS $index=>$row ){

            $form .= "<option value='". $row[0] . "'>" . $row[1] . " " . $row[2] . "</option>";
    	}
            $form .= "</select>";
            $form .= "<div><textarea rows='5' cols='60' name='noteText'></textarea></div>";
            $form .= "<input type='hidden' value='addNote' name='action'>";
            $form .= "<p><input type='submit' value='Add'></p>";
            $form .= "</form>";
            $form .= "</fieldset>";
            $form .= "</div>";
            
return $form;
}
