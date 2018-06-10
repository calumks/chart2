<?php
function showRequests(){
    include "mysql-cred.php";
    $link  = mysqli_connect( $servername, $username, $password, $database);
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error);
    } 
    $sql = "SELECT * from request";
    $result = mysqli_query($link, $sql);
    $check = "<table>";
    if ($result){
    	while($row = mysqli_fetch_row( $result )) {
		$check .= "<tr>";
		$check .= "<td>"  . $row[0] ;
		$check .= "<td>"  . $row[1] ;
		$check .= "<td>"  . $row[2] ;
//		$check .= "<td>"  . $row[3] ;
		$check .= "</tr>";
    	}
    }
    $check .= "</table>";

    return $check;

}
function countRequests(){
    include "mysql-cred.php";
    $link  = mysqli_connect( $servername, $username, $password, $database);
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error);
    } 
    $sql = "SELECT COUNT(*) as count, COUNT(DISTINCT requestIP) as countIP from request";
    $result = mysqli_query($link, $sql);
    $check = "";
    if ($result){
    	while($row = mysqli_fetch_row( $result )) {
		$check .= $row[0] . " requests from " . $row[1] . " distinct addresses";
    	}
    }
    return $check;

}
