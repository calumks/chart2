<?php
function sendCode( $email ){

$md5now = md5(time());
$sql = "SELECT userID from user where md5email = md5(trim(upper(' " . $email . "'))) LIMIT 1";

    foreach( listMultiple( $sql ) AS $index=>$row ){
	$userID = $row[0];
    }

$sql = "INSERT into confirmation (userID, confirmationCode, ip) VALUES( " . $userID . ", '" . $md5now . "', '" . getIP() . "');";
$result = my_execute( $sql);
if ($result){
	$msg = "To use the TSB chart printer please paste this address into your browser.\n  http://tsbchart.000webhostapp.com/?confirmation=" . $md5now;
	$msg = wordwrap($msg, 70);
	
	$headers = 'Reply-To: ' . getOneAdminEmail(). "\r\n" . 'Cc: ' .getOneAdminEmail();
	mail( $email, "TSB Chart confirm email", $msg, $headers);
}

}

function getAdminEmails(){
    $return = array();
    $sql = "SELECT AES_DECRYPT(aesEmail, UNHEX(SHA2('A String Of Pearls',512))) from user where aesEmail is not null"; 
    foreach( listMultiple( $sql ) AS $index=>$row ){
	$return[] = $row[0];
    }
    return $return;
}

function getOneAdminEmail(){
    $all = getAdminEmails();
    if (count($all) > 0){
        return $all[0]; 
    } else {
        return "null@null";
}
}


function storeNewUser( $email, $nickName ){

$sql = "INSERT INTO user(md5email, nickName) SELECT md5(trim(upper(' " . $email . "'))), '" . $nickName . "';";
$result = my_execute($sql);
}
