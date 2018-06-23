<?php
use \setasign\Fpdi;

// setup the autoload function
require_once('vendor/autoload.php');

define ('SITE_ROOT', realpath(dirname(__FILE__)));

function deleteFile($fileNameExclPath){

$sql = "SELECT COUNT(*) from efile where name='" . $fileNameExclPath . "'";
$bFound = true;
foreach( listMultiple($sql) as $index=>$row ){
        		if ( $row[0]==0 ){
        		    $bFound = false;
        		 }
        }
if( !$bFound){
	if( file_exists(SITE_ROOT . '/pdf/' . $fileNameExclPath)){
	    unlink(SITE_ROOT . '/pdf/' . $fileNameExclPath);
	}
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
    include "mysql-cred.php";
    $link  = mysqli_connect( $servername, $username, $password, $database);
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error());
    } 
    $result = mysqli_execute(mysqli_prepare($link, $sql));
    return $newName;
}


function receiveFile( $file=array()){
//echo "<pre>" .     print_r($file,1) . "</pre>";
   if (!isset($file['error']) || !isset($file['tmp_name']) || !isset($file['name'])){
	return false;
   }
 
   $uploads_dir = "pdf";
// https://secure.php.net/manual/en/function.move-uploaded-file.php'    


if ($file['error'] == UPLOAD_ERR_OK) {
    $tmp_name = $file['tmp_name'];
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
    $name = basename($file['name']);
    $newName = newName($name,10) . ".pdf";
    if(mime_content_type($file['tmp_name']) == "application/pdf" && !file_exists(SITE_ROOT . "/$uploads_dir/$newName")){
        move_uploaded_file($tmp_name, SITE_ROOT . "/$uploads_dir/$newName");
        return true;
    } else {
        return false;
    }
}

}


function setPublication($input = array()){
    
  if (isset($input['efile']) && isset($input['formatID']) && strlen($input['efile'])>4 ){
        
    if (isset($input['publicationID']) && $input['publicationID'] > 0){
        $sql = "INSERT INTO efile (name, efileTypeID, formatID, publicationID) VALUES('". $input['efile'] . "',1,". $input['formatID'] . "," . $input['publicationID'] . ");";
        $result = mysqli_execute(mysqli_prepare($link, $sql));

    } elseif (isset($input['description']) && strlen($input['description']) > 0 && isset($input['songID']) && isset($input['arrangerPersonID'])  && $input['songID']>0 && $input['arrangerPersonID']>0 ){
        $sql = "INSERT INTO arrangement (songID, arrangerPersonID) VALUES(". $input['songID'] . ",". $input['arrangerPersonID'] . ");";
        $last_id = my_insert_id($sql);
        $sql = "INSERT INTO publication (arrangementID, description) VALUES(". $last_id . ",'". $input['description'] . "');";
        $last_id = my_insert_id($sql);
        $sql = "INSERT INTO efile (name, efileTypeID, formatID, publicationID) VALUES('". $input['efile'] . "',1,". $input['formatID'] . "," . $last_id . ");";
        $result = my_execute( $sql);
    }
  }
 
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
    $return .= "<p>Publication description<textarea name='description'></textarea></p>";

    $return .= "<p><input type='submit' value='ADD'>";
    $return .= "</form></fieldset>";

    return $return;
}

function listPdfUnlisted( $path = '../pdf' ){

$files = scandir($path);
$files = array_diff(scandir($path), array('.', '..'));
asort($files);
$sql = "SELECT name from efile";
$pairedFiles = array();
    	foreach( listMultiple( $sql ) AS $index=>$row ){
    	$pairedFiles[] = $row[0];
    	}


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
        $pdf = new Fpdi\Fpdi();
        $numPages = $pdf->setSourceFile("$filename");
    }
    catch(Exception $e) {
        $numPages = -1;
    }
    return $numPages;
}

function getPublicationList(){

$sql = "SELECT E.name, S.name, P.description, PP.firstName, PP.lastName FROM efile as E, publication as P, arrangement as A, person as PP, song AS S WHERE E.publicationID = P.publicationID and P.arrangementID=A.arrangementID and A.arrangerPersonID=PP.personID AND A.songID = S.songID ORDER BY E.name";
$details = array();
    	foreach(listMultiple( $sql ) AS $index=>$row ){
    	    $details[] = $row;
    	}
return $details;
}


function arrangement( $filename ){

$sql = "SELECT E.name, P.description, PP.firstName, PP.lastName FROM efile as E, publication as P, arrangement as A, person as PP WHERE E.publicationID = P.publicationID and P.arrangementID=A.arrangementID and A.arrangerPersonID=PP.personID AND E.name = '" . $filename . "'";
$details = "";
	$i = 1;
    	foreach(listMultiple( $sql ) AS $index=>$row ){
    	if (1==$i){
    	    $details .= $row[1];
    	}
    	$details .= ", " . $row[2] . " " . $row[3];
    	$i++;
    	}
return $details;
}

function countParts( $filename ){

$sql = "SELECT COUNT(*) FROM (SELECT DISTINCT partName FROM view_efilePart AS V INNER JOIN efile AS E on E.efileID = V.efileID where E.name = '" . $filename . "') AS C";

$details = "";
    	foreach( listMultiple( $sql ) AS $index=>$row){
    	$details = $row[0];
    	}
return $details;
}


function listSingle($sql){

$pairedFiles = array();
    	foreach(listMultiple( $sql ) AS $index=>$row ){
    	$pairedFiles[] = $row[0];
    	}
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
