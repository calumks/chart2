<?php
use \setasign\Fpdi;

// setup the autoload function
require_once('vendor/autoload.php');

define ('SITE_ROOT', realpath(dirname(__FILE__)));

function deleteFile($fileNameExclPath){

include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$sql = "SELECT COUNT(*) from efile where name='" . $fileNameExclPath . "'";
//echo $sql;
$bFound = true;
$result = mysqli_query($link, $sql);
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
        		if ( $row[0]==0 ){
        		    $bFound = false;
        		 }
        }
}
mysqli_close( $link );
if( !$bFound){
    unlink(SITE_ROOT . '/pdf/' . $fileNameExclPath);
}    

}

function newName($oldName, $countDigits){
    $newName = preg_replace('/[^A-Za-z1-9]/u','', strip_tags($oldName));
    for( $i = 1; $i <= $countDigits; $i++){
        $newName .= intval(rand(0,20));
    }
    return $newName;
}

function renameFile($efileID){
    foreach (listMultiple("SELECT E.name from efile as E where E.efileID = " . $efileID )  as $key=>$song){
        $oldName = $song[0];
    }
    $newName = newName($oldName,10) . ".pdf";
    rename(SITE_ROOT . '/pdf/' . $oldName, SITE_ROOT . '/pdf/' . $newName);
    $sql = "UPDATE efile SET name='" . $newName . "' WHERE efileID = " . $efileID;
 //   echo $sql;
    include "mysql-cred.php";
    $link  = mysqli_connect( $servername, $username, $password, $database);
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error());
    } 
    $result = mysqli_execute(mysqli_prepare($link, $sql));
    return $newName;
}

//echo renameFile(14);

function receiveFile(){
    
//    define ('SITE_ROOT', realpath(dirname(__FILE__)));

//    echo SITE_ROOT;
   $uploads_dir = "pdf";
// https://secure.php.net/manual/en/function.move-uploaded-file.php'    

//echo "<br>";
//print_r($_FILES['myUpload']);
//echo "<br>";
//print_r($_FILES['myUpload']['error']);
//echo "<br>";

if ($_FILES['myUpload']['error'] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['myUpload']['tmp_name'];
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
    $name = basename($_FILES['myUpload']['name']);
    $newName = newName($name,10) . ".pdf";
    if(mime_content_type($_FILES['myUpload']['tmp_name']) == "application/pdf" && !file_exists(SITE_ROOT . "/$uploads_dir/$newName")){
        move_uploaded_file($tmp_name, SITE_ROOT . "/$uploads_dir/$newName");
        return true;
    } else {
        return false;
    }
}

}


function setPublication(){
    
include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
  if (isset($_POST['efile']) && isset($_POST['formatID']) && strlen($_POST['efile'])>4 ){
        
    if (isset($_POST['publicationID']) && $_POST['publicationID'] > 0){
        $sql = "INSERT INTO efile (name, efileTypeID, formatID, publicationID) VALUES('". $_POST['efile'] . "',1,". $_POST['formatID'] . "," . $_POST['publicationID'] . ");";
        $result = mysqli_execute(mysqli_prepare($link, $sql));

    } elseif (isset($_POST['description']) && strlen($_POST['description']) > 0 && isset($_POST['songID']) && isset($_POST['arrangerPersonID'])  && $_POST['songID']>0 && $_POST['arrangerPersonID']>0 ){
        $sql = "INSERT INTO arrangement (songID, arrangerPersonID) VALUES(". $_POST['songID'] . ",". $_POST['arrangerPersonID'] . ");";
//        echo $sql;
        $result = mysqli_execute(mysqli_prepare($link, $sql));
        $last_id = mysqli_insert_id($link);
        $sql = "INSERT INTO publication (arrangementID, description) VALUES(". $last_id . ",'". $_POST['description'] . "');";
        $result = mysqli_execute(mysqli_prepare($link, $sql));
        $last_id = mysqli_insert_id($link);
        $sql = "INSERT INTO efile (name, efileTypeID, formatID, publicationID) VALUES('". $_POST['efile'] . "',1,". $_POST['formatID'] . "," . $last_id . ");";
        $result = mysqli_execute(mysqli_prepare($link, $sql));
    }
  }
mysqli_close( $link );
 
}

function getPublicationForm( $path = '../pdf'){
    
    $return = "<fieldset><legend>Pair pdf to publication</legend><form action='' method='POST'>";
    $return .= "<input type='hidden' name='action' value='setPublication'>";
    $return .= "<p>Efile <select name='efile'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    foreach (listPdfUnlisted( $path ) as $key=>$filename){
        $return .= "<option value='" . $filename . "'>" . $filename . "</option>";
    }
    $return .= "</select>";
    $return .= "<p>Format <select name='formatID'>";
    $return .= "<option value='0'>Portrait</option>";
    $return .= "<option value='1'>Landscape</option>";
    $return .= "</select>";    $return .= "<p>Publication <select name='publicationID'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    foreach (listMultiple("SELECT P.publicationID, CONCAT(S.name,',',P.description,', ', PP.firstName , ' ' ,PP.lastName) FROM publication as P, arrangement as A, person as PP, song AS S WHERE  P.arrangementID=A.arrangementID and A.arrangerPersonID=PP.personID AND A.songID = S.songID order by S.name  ASC")  as $key=>$song){
        $return .= "<option value='" . $song[0] . "'>" . $song[1] . "</option>";
    }
    $return .= "</select>";
    $return .= "<p>OR (if publication not defined)</p>";
    $return .= "<p>Song <select name='songID'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    foreach (listMultiple("SELECT songID, name from song order by name  ASC")  as $key=>$song){
        $return .= "<option value='" . $song[0] . "'>" . $song[1] . "</option>";
    }
    $return .= "</select>";
    $return .= "<p>Arranger <select name='arrangerPersonID'>";
    $return .= "<option value='" . -1 . "'>" . "" . "</option>";
    foreach (listMultiple("SELECT personID, CONCAT(firstName,' ',lastName) from person order by LastName  ASC")  as $key=>$song){
        $return .= "<option value='" . $song[0] . "'>" . $song[1] . "</option>";
    }
    $return .= "</select>";
    $return .= "<p><textarea name='description'>Publication description</textarea></p>";

    $return .= "<p><input type='submit' value='ADD'>";
    $return .= "</form></fieldset>";
//    $return .= "<fieldset><legend>Paired pdfs</legend><table>";
//    foreach (getPublicationList() as $key=>$row){
//        $return .= "<tr><td><a href='../pdf/" . $row[0] . "'>" . $row[0] . "</a></td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td></tr>";
//    }
//    $return .= "</table></fieldset>";

    return $return;
}

function listPdfUnlisted( $path = '../pdf' ){

//$path    = '../pdf';
$files = scandir($path);
$files = array_diff(scandir($path), array('.', '..'));
asort($files);
include "mysql-cred.php";
$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$sql = "SELECT name from efile";
//echo $sql;
$pairedFiles = array();
$result = mysqli_query($link, $sql);
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
    	$pairedFiles[] = $row[0];
    	}
}

mysqli_close( $link );

$unpaired = array_diff($files, $pairedFiles);
$unpaired2 = array();
foreach ($unpaired as $key=>$filename){
    if (numPages($path . "/" . $filename) > 0){
        $unpaired2[] = $filename;
    }
}
asort($unpaired2);
return $unpaired2;    
}

function listPdf(){
$path    = '../pdf';
echo "<fieldset><legend>Unpaired pdfs</legend>";

foreach (listPdfUnlisted() as $key=>$filename){
    echo "<p><a href='" . $path . "/" . $filename . "'>" . $filename . "</a> " . numPages($path . "/" . $filename) . "\n\n";
    echo "<form action='' method='post'>";
    echo "<input type='submit' value='delete " . $filename . "' ><input type='hidden' name='action' value='deletePDF'>";
    echo "<input type='hidden' name='fileNameExclPath' value='" . $filename . "'>";
    echo "</form></p>";
    
}
echo "</fieldset>";

}

function numpages($filename){
    try {
//        require_once('fpdf/fpdf.php');
//        require_once('fpdi2/src/autoload.php');
        $pdf = new Fpdi\Fpdi();
        $numPages = $pdf->setSourceFile("$filename");
    }
    catch(Exception $e) {
        $numPages = -1;
    }
    return $numPages;
}

function getPublicationList(){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$sql = "SELECT E.name, S.name, P.description, PP.firstName, PP.lastName FROM efile as E, publication as P, arrangement as A, person as PP, song AS S WHERE E.publicationID = P.publicationID and P.arrangementID=A.arrangementID and A.arrangerPersonID=PP.personID AND A.songID = S.songID ORDER BY E.name";
//echo $sql;
$result = mysqli_query($link, $sql);
$details = array();
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
    	    $details[] = $row;
    	}
}

mysqli_close( $link );
return $details;
}


function arrangement( $filename ){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$sql = "SELECT E.name, P.description, PP.firstName, PP.lastName FROM efile as E, publication as P, arrangement as A, person as PP WHERE E.publicationID = P.publicationID and P.arrangementID=A.arrangementID and A.arrangerPersonID=PP.personID AND E.name = '" . $filename . "'";
//echo $sql;
$result = mysqli_query($link, $sql);
$details = "";
if ($result){
	$i = 1;
    	while($row = mysqli_fetch_row( $result )) {
    	if (1==$i){
    	    $details .= $row[1];
    	}
    	$details .= ", " . $row[2] . " " . $row[3];
    	$i++;
    	}
}

mysqli_close( $link );
return $details;
}

function countParts( $filename ){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$sql = "SELECT COUNT(*) FROM (SELECT DISTINCT partName FROM view_efilePart AS V INNER JOIN efile AS E on E.efileID = V.efileID where E.name = '" . $filename . "') AS C";

//echo $sql;
$result = mysqli_query($link, $sql);
$details = "";
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
    	$details = $row[0];
    	}
}

mysqli_close( $link );
return $details;
}


function listSingle($sql){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$result = mysqli_query($link, $sql);
$pairedFiles = array();
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
    	$pairedFiles[] = $row[0];
    	}
}
mysqli_close( $link );
return $pairedFiles;
}

function listMultiple($sql){

include "mysql-cred.php";

$link  = mysqli_connect( $servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
$result = mysqli_query($link, $sql);
$pairedFiles = array();
if ($result){
    	while($row = mysqli_fetch_row( $result )) {
    	$pairedFiles[] = $row;
    	}
}
mysqli_close( $link );
return $pairedFiles;
}

function getUploadFileForm(){
    $form = '
        <fieldset>
        <legend>Upload file</legend>
        <form action="" method="POST" role="form" class="uploadForm" enctype="multipart/form-data" >
        <input type="hidden" name="action" value="uploadPDF">
        <input type="file" name = "myUpload" class = "file">
        <input type="submit" value="Upload file" id="boton" class = "btn btn-success btn-lg">
        </form>
        </fieldset>
        ';
        return $form;
}
