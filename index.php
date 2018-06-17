<?php
// https://stackoverflow.com/questions/1907653/how-to-force-page-not-to-be-cached-in-php
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Pragma: no-cache"); // HTTP/1.0
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
?>
<?php 
$bShowEmailForm = true;
include_once "include_refsB.php";
//echo getLatestGigID();
if (isset($_REQUEST['confirmation'])) {
	setValidCookie( $_REQUEST['confirmation'] );
	header('Location: https://tsbchart.000webhostapp.com');
} elseif (isset($_REQUEST['action'])) {
    if ( 'logout'==$_REQUEST['action']) {
        
			deleteCookie();
	        header('Location: https://tsbchart.000webhostapp.com');
//			echo "deleterequested";
    }
	if ('storeEmail'==$_REQUEST['action']) {
		if (!(storeEmail())){
			echo "<p>Email not recognised.</p>";
			echo getEmailForm();
			echo getFooter();
			exit();
		} else {
			echo "<p>Please check your email for a confirmation code. If it doesn't arrive within 5 mins please contact Owen.</p>";
			echo getFooter();
			exit();
		}

	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
         <head>
          <title>TSB Printer</title>
        </head>
        <body>
<?php 
// authenticate user.  Valid cookie or no valid cookie
// if valid cookie, provide content
// if no valid cookie, show disabled forms and offer means of authentication
//include_once "include_refsB.php";
if (hasValidCookie()){
//    echo "has valid cookie";
    $arrangementID = -1; $gigID = -1;
	if (isset($_GET['arrangementID'])) {
        $arrangementID = $_GET['arrangementID'];
    }
	if (isset($_GET['gigID'])) {
        $gigID = $_GET['gigID'];
    }
	if (isset($_REQUEST['action'])) {
		if ( 'getChartList'==$_GET['action'] ) {
	        if (isset($_GET['partID'])) {
			echo getOutputLink( listAll($_GET['partID']) );
	        }
			echo getRequestForm($arrangementID, $gigID);
			echo getFooter();
			exit();
	       
		} elseif ( 'getChart'==$_GET['action']) {
			echo getOutputLink( pdfFromGet() );
			echo getRequestForm($arrangementID, $gigID);
			echo getFooter();
			exit();
		} elseif ( 'getGig'==$_GET['action']) {
			echo getOutputLink( pdfFromGig() );
			echo getRequestForm($arrangementID, $gigID);
			echo getFooter();
			exit();
		} else {
			echo getRequestForm($arrangementID, $gigID);
			echo getFooter();
			exit();
		}
	} else {
			echo getRequestForm($arrangementID, $gigID);
		echo getFooter();
			exit();
	}
} else {
	echo getEmailForm();
	echo getFooter();
			exit();
}
//print_r($_COOKIE);

//echo "session" . print_r($_SESSION,1);
?>
</body>
</html>