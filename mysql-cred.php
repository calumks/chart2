<?php

function db_connect() {

    $servername = "localhost";
    // change $username for  local installation
    $username = "makeUpAUserName";
    // change $password for  local installation
    $password = "makeUpASatisfactoryPassword";
    $database ="chart2";  

    $link  = @mysqli_connect($servername, $username, $password, $database);
    if (!$link) {
        if ($servername == "localhost") {
            // docker credentials
            return db_connect("mysql", $username, $password, $database);
        } 
        die("Connection failed: " . mysqli_connect_error());
    } else {
        return $link;
    } 
}