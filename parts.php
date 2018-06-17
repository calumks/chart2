<?php

function addToSet( $gigID, $order, $arrangementID){
    
include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
    if ($arrangementID > 0 && $gigID > 0){
        $sql = "INSERT INTO setList2 (arrangementID, gigID, setListOrder) VALUES('". $arrangementID . "',". $gigID . "," . $order . ");";
        $result = mysqli_execute(mysqli_prepare($link, $sql));
}
mysqli_close( $link );
 
}


function deleteSetListPart( $setListID){
    
include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
        $sql = "DELETE FROM  setList2 where setListID = ". $setListID . ";";
        $result = mysqli_execute(mysqli_prepare($link, $sql));

mysqli_close( $link );
 
}


function deletePartPage( $efilePartID){
    
include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
    if ($efilePartID > 0 ){
        $sql = "DELETE FROM  efilePart where eFilePartID = ". $efilePartID . ";";
        $result = mysqli_execute(mysqli_prepare($link, $sql));
}
mysqli_close( $link );
 
}

function setPartPage( $efileID, $partID, $startPage, $endPage){
    
include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
    if ($endPage >= $startPage && $efileID > 0 && $partID > 0){
        $sql = "INSERT INTO efilePart (efileID, partID, startPage, endPage) VALUES('". $efileID . "',". $partID . "," . $startPage . "," . $endPage . ");";
        $result = mysqli_execute(mysqli_prepare($link, $sql));
}
mysqli_close( $link );
 
}

function getEfileFormOrder( $publicationID = -1, $orderby = 'order by E.name  ASC', $label = "alphabetical"){
    
    if ( 0 < $publicationID ){
        $wherePub = " AND E.publicationID =" . $publicationID . " ";
    } else {
        $wherePub = " AND 1 ";
    }
    $return = "<form action='' method='GET'>";
    $return .= "<input type='hidden' name='action' value='getEfileParts'>";
    $return .= "<p>Efile " . $label . "<select name='efileID'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    foreach (listMultiple("SELECT E.efileID, CONCAT(E.name,': ', S.name,',',P.description,', ', PP.firstName , ' ' ,PP.lastName), V.countPages, E.name  FROM efile as E, publication as P, arrangement as A, person as PP, song AS S, view_efilePages AS V  WHERE  E.publicationID=P.publicationID and P.arrangementID=A.arrangementID and A.arrangerPersonID=PP.personID AND A.songID = S.songID and E.efileID=V.efileID " . $wherePub . $orderby )  as $key=>$song){
        $return .= "<option value='" . $song[0] . "'>" . $song[2] . "/" . numPages('../pdf/' . $song[3]) . " " . $song[1] . "</option>";
    }
    $return .= "</select>";
    $return .= "<p><input type='submit' value='Get parts'>";
    $return .= "</form>";

    return $return;
}


function getEfileFormOld(){
    
    $return = "<form action='' method='GET'>";
    $return .= "<input type='hidden' name='action' value='getEfileParts'>";
    $return .= "<p>Efile <select name='efileID'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    foreach (listMultiple("SELECT E.efileID, CONCAT(E.name,': ', S.name,',',P.description,', ', PP.firstName , ' ' ,PP.lastName), V.countPages, E.name  FROM efile as E, publication as P, arrangement as A, person as PP, song AS S, view_efilePages AS V  WHERE  E.publicationID=P.publicationID and P.arrangementID=A.arrangementID and A.arrangerPersonID=PP.personID AND A.songID = S.songID and E.efileID=V.efileID order by E.name  ASC")  as $key=>$song){
        $return .= "<option value='" . $song[0] . "'>" . $song[2] . "/" . numPages('../pdf/' . $song[3]) . " " . $song[1] . "</option>";
    }
    $return .= "</select>";
    $return .= "<p><input type='submit' value='GET'>";
    $return .= "</form>";

    return $return;
}

function getEfileForm( $publicationID = -1){
    
    $return =  getEfileFormOrder( $publicationID );

    $return .= getEfileFormOrder( $publicationID, ' order by E.efileID DESC ', ' date');
    $return .= getPubForm();
    return $return;
    
}

function getPartForm($efileID){
 
    foreach (listMultiple("SELECT E.name AS Ename  FROM efile as E WHERE  E.efileID = " . $efileID . "")  as $key=>$song){
        $fname = $song[0];
    }   
//    echo "get part form";
    $return = "";
    $return .= "<fieldset><legend>Delete part/page pairs</legend>";
    $return .= "<div><table>";
    $return .= "<tr><th>Part<th>Start Page<th>End Page<th>Efile</tr>";
    $fname = "";
    foreach (listMultiple("SELECT X.efilePartID, X.startPage, X.endPage, P.name, E.name AS Ename  FROM efilePart as X, part as P, efile as E WHERE  X.partID=P.partID and X.efileID = E.efileID  and E.efileID = " . $efileID . " order by X.startPage  ASC")  as $key=>$song){
        $fname = $song[4];
        $return .= "<tr>";
        $return .= "<td>" . $song[3] . "</td>";
        $return .= "<td>" . $song[1] . "</td>";
        $return .= "<td>" . $song[2] . "</td>";
        $return .= "<td>" . $song[4] . "</td>";
        $return .= "<td>";
        $return .= "<form action='' method='POST'>";
        $return .= "<input type='hidden' name='action' value='deleteEfilePart'>";
        $return .= "<input type='hidden' name='efilePartID' value='" . $song[0] . "'>";
        $return .= "<input type='submit' value='DELETE'>";
        $return .= "</form>";
        $return .= "</td>";
        $return .= "</tr>";
    }
    $return .= "</table></div>";
    $return .= "</fieldset>";
    $return .= "<fieldset><legend>Add part/page pair</legend>";
    $return .= "<p><a href='../pdf/" . $fname . "'>" . $fname . "</a></p>";
    $numpages = numPages('../pdf/' . $fname);
//    echo $numpages;
    $return .= "<div>";
    $return .= "<form action='' method='POST'>";
    $return .= "<input type='hidden' name='action' value='addEfilePart'>";
    $return .= "<input type='hidden' name='efileID' value='" . $efileID . "'>";
    $return .= "<p>Part <select name='partID'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    foreach (listMultiple("SELECT P.partID, P.name FROM part as P  order by P.name  ASC")  as $key=>$song){
        $return .= "<option value='" . $song[0] . "'>" . $song[1] . "</option>";
    }
    $return .= "</select>";
    $return .= "<p>Start Page <select name='startPage'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    for ($i = 1; $i <= $numpages; $i++){
        $return .= "<option value='" . $i . "'>" . $i . "</option>";
    }
    $return .= "</select>";
    $return .= "<p>End Page <select name='endPage'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    for ($i = 1; $i <= $numpages; $i++){
        $return .= "<option value='" . $i . "'>" . $i . "</option>";
    }
    $return .= "</select>";
    $return .= "<p><input type='submit' value='ADD to " . $fname . "'>";
    $return .= "</form>";
    $return .= "</fieldset>";
    return $return;
}


function getGigSetForm($gigID){
 
    $lastOrder = -999;
    $gigLabel = "";
    foreach (listMultiple("SELECT G.name, G.gigDate FROM gig as G WHERE  G.gigID = " . $gigID . "")  as $key=>$song){
        $gigLabel = $song[0] . " " . $song[1];
    }   
    $return = "";
    $return .= "<fieldset><legend>Edit set list for " . $gigLabel . "</legend>";
    $return .= "<div><table>";
    $return .= "<tr><th>Song<th> </tr>";
    $order = 999;
    foreach (listMultiple("SELECT T.setListID, T.setListOrder, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName)  FROM setList2 AS T, view_arrangement AS V WHERE T.arrangementID = V.arrangementID AND T.gigID = " . $gigID . " order by T.setListOrder ASC")  as $key=>$song){
        $order = $song[1];
        $midOrder = 0.5 * ($lastOrder + $order);
        $lastOrder = $order;
        $return .= "<tr><td>";
        $return .= "<form action='' method='POST'>";
        $return .= "<input type='hidden' name='action' value='addSetListPart'>";
        $return .= "<input type='hidden' name='gigID' value='" . $gigID . "'>";
        $return .= "<input type='hidden' name='setListOrder' value='" . $midOrder . "'>";
        $return .= "<select name='arrangementID'>";
        $return .= "<option value='" . -1 . "'>" . "" . "</option>";
        foreach (listMultiple("SELECT V.arrangementID, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName)  FROM view_arrangement AS V  order by V.name  ASC")  as $keyy=>$songg){
            $return .= "<option value='" . $songg[0] . "'>" . $songg[1] . "</option>";
        }
        $return .= "</select>";
        $return .= "<input type='submit' value='INSERT'>";
        $return .= "</form>";
        $return .= "</td></tr>";
        $return .= "<tr>";
//        $return .= "<td>" . $song[2] . "</td>";
        $return .= "<td>";
        $return .= "<form action='' method='POST'>";
        $return .= "<input type='hidden' name='action' value='deleteSetListPart'>";
        $return .= "<input type='hidden' name='setListID' value='" . $song[0] . "'>";
        $return .= "<input type='submit' value='(DELETE) " . $song[2] . "'>";
        $return .= "</form>";
        $return .= "</td>";
        $return .= "</tr>";

    }
        $lastOrder = $order + 10;
        $return .= "<tr><td>";
        $return .= "<form action='' method='POST'>";
        $return .= "<input type='hidden' name='action' value='addSetListPart'>";
        $return .= "<input type='hidden' name='gigID' value='" . $gigID . "'>";
        $return .= "<input type='hidden' name='setListOrder' value='" . $lastOrder . "'>";
        $return .= "<select name='arrangementID'>";
        $return .= "<option value='" . -1 . "'>" . "" . "</option>";
        foreach (listMultiple("SELECT V.arrangementID, CONCAT(V.name, ', ', V.arrangerFirstName, ' ', V.arrangerLastName)  FROM view_arrangement AS V  order by V.name  ASC")  as $key=>$song){
            $return .= "<option value='" . $song[0] . "'>" . $song[1] . "</option>";
        }
        $return .= "</select>";
        $return .= "<input type='submit' value='INSERT'>";
        $return .= "</form>";
        $return .= "</td></tr>";
    $return .= "</table></div>";
    $return .= "</fieldset>";
    return $return;
}

function getPubForm(){
    $return = "";
    $return .= "<fieldset><legend>Limit pdfs to publication</legend>";
    $return .= "<form action='' method='GET'>";
    $return .= "<input type='hidden' name='action' value='getParts'>";
    $return .= "<p>Publication <select name='publicationID'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    foreach (listMultiple("SELECT P.publicationID, CONCAT(S.name,',',P.description,', ', PP.firstName , ' ' ,PP.lastName) FROM publication as P, arrangement as A, person as PP, song AS S WHERE  P.arrangementID=A.arrangementID and A.arrangerPersonID=PP.personID AND A.songID = S.songID order by S.name  ASC")  as $key=>$song){
        $return .= "<option value='" . $song[0] . "'>" . $song[1] . "</option>";
    }
    $return .= "</select>";
    $return .= "<input type='submit' value='Limit pdfs on offer'>";
    $return .= "</form>";
    $return .= "</p>";
return $return;
}
