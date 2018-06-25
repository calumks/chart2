<?php 
//echo "<pre>POST " . print_r($_POST,1) . "</pre>";
//echo "<pre>FILES " . print_r($_FILES,1) . "</pre>";
// do all post stuff first then redirect
//
// authenticate user.  Valid cookie or no valid cookie
// if valid cookie, provide content
// if no valid cookie, go to main menu
include_once "../include_refsC.php";
if ($_POST){
if (User::hasValidCookie()){
if (isset($_POST['action'])){

if (User::hasAdminCookie()){
    if ('uploadPDF'==$_POST['action']){
    	if (isset($_FILES['myUpload'])){
        	Arrangement::receiveFile( $_FILES['myUpload']);
	}
    }

    if ('deletePDF'==$_POST['action'] && isset( $_POST['fileNameExclPath'])){
        Arrangement::deleteFile($_POST['fileNameExclPath']);
    }

    if ('addPerson'==$_POST['action']){
        Arrangement::postNewPerson($_POST);
    }

    if ('addNewUser'==$_POST['action']){
        if (isset($_POST['newEmail']) && isset($_POST['newNickName'])){
            if (strlen($_POST['newNickName']) > 3){
                User::storeNewUser($_POST['newEmail'],$_POST['newNickName']);
            }
        }
    }
    
    if ('addSong'==$_POST['action']){
        Arrangement::postNewSong($_POST);
    }

    if ('setPublication'==$_REQUEST['action']){
        Arrangement::setPublication($_POST);
    }
}

    if ('deleteEfilePart'==$_POST['action']){
        if(isset( $_POST['efilePartID']) ){
            Arrangement::deletePartPage( $_POST['efilePartID'] );
        }
    }

    if ('addEfilePart'==$_POST['action']){
        if(isset( $_POST['efileID']) && isset($_POST['partID']) && isset($_POST['startPage']) && isset($_POST['endPage'])){
            Arrangement::setPartPage( $_POST['efileID'], $_POST['partID'], $_POST['startPage'], $_POST['endPage']);
        }
    }

    if ('addNote'==$_POST['action']){
        if(isset( $_POST['noteText'])  && isset( $_POST['publicationID'])){
            Arrangement::addNote($_POST['publicationID'], $_POST['noteText']);
        }
    }

    if ('deleteNote'==$_POST['action']){
        if( isset( $_POST['noteID'])){
            Arrangement::deleteNote($_POST['noteID']);
        }
    }

    if ('updateNote'==$_POST['action']){
        if(isset( $_POST['noteText'])  && isset( $_POST['noteID'])){
            Arrangement::updateNote($_POST['noteID'], $_POST['noteText']);
        }
    }


    if ('addToBackup'==$_POST['action']){
        if(isset( $_POST['arrangementID']) ){
            Arrangement::addToBackup( $_POST['arrangementID'], 1 );
        }
    }

    if ('removeFromBackup'==$_POST['action']){
        if(isset( $_POST['arrangementID']) ){
            Arrangement::addToBackup( $_POST['arrangementID'], 0 );
        }
    }


    if ('addToPads'==$_POST['action']){
        if(isset( $_POST['arrangementID']) ){
            Arrangement::addToPads( $_POST['arrangementID'], 1 );
        }
    }

    if ('removeFromPads'==$_POST['action']){
        if(isset( $_POST['arrangementID']) ){
            Arrangement::addToPads( $_POST['arrangementID'], 0 );
        }
    }

    if(isset( $_POST['action']) && 'deleteSetListPart'==$_POST['action']){
        if(isset( $_POST['setListID'])) {
            Gig::deleteSetListPart($_POST['setListID']);
        }
    }

    if(isset( $_POST['action']) && 'addSetListPart'==$_POST['action']){
        if(isset( $_POST['gigID'])) {
            Gig::addToSet($_POST['gigID'], $_POST['setListOrder'], $_POST['arrangementID']);
        }
    }


    if ('addSetList'==$_POST['action']){
        Gig::postNewSetList($_POST);
    }


    if ('copySetList'==$_POST['action']){
        if (isset($_POST['sourceGigID']) && isset($_POST['targetGigID'])){
            Gig::copySetList( $_POST['sourceGigID'], $_POST['targetGigID']);
        }
    }



    if ('deleteSetList'==$_REQUEST['action']){
        Gig::deleteSet($_POST);
    }
}
}
header("Location: " . $_SERVER['REQUEST_URI']);
exit();
}
?>      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
         <head>
          <title>Maintenance</title>
        </head>

        <body>
<?php 

// authenticate user.  Valid cookie or no valid cookie
// if valid cookie, provide content
// if no valid cookie, go to main menu
//include_once "../include_refsB.php";
if (User::hasValidCookie()){
echo "<p><a href='../'>Main menu</a></p>";
echo "<p><a href='./?action=getNewSetListForm'>Edit set</a></p>";
echo "<p><a href='./?action=getNotes'>Edit notes</a></p>";
if (User::hasAdminCookie()){
echo "<p><a href='./?action=getParts'>Assign parts</a></p>";
echo "<p><a href='./?action=listPdf'>Add pdf</a></p>";
echo "<p><a href='./?action=getNewPersonForm'>Add song/person</a></p>";
}

if (isset($_GET['action'])){
    if ('getParts'==$_GET['action']){
        if (isset($_GET['publicationID'])){
            echo Arrangement::getEFileForm($_GET['publicationID']);
        } else {
            echo Arrangement::getEFileForm();
        }    
    }

    if ('getPartsForSet'==$_GET['action']){
        if (isset($_GET['gigID'])){
            Gig::getSetPartsOutput( $_GET['gigID'], dirname(getcwd()));
            echo "<a href='../output/'>Output directory</a>";

        }
    }

    if ('getNotes'==$_GET['action']){
        echo Arrangement::getNewNoteForm();
        echo Arrangement::getEditNoteForm();
        
    }

    if ('getEfileParts'==$_GET['action']){
        if (isset($_GET['efileID']) && $_GET['efileID'] > 0){
            echo Arrangement::getPartForm($_GET['efileID']);
        }
        echo Arrangement::getEFileForm();
    }

    if ('listPdf'==$_GET['action']){
        Arrangement::listPdf();
        echo Arrangement::getPublicationForm();
        echo Arrangement::getUploadFileForm();
    }

    if ('getSetList'==$_GET['action']){
        if (isset($_REQUEST['gigID']) && $_REQUEST['gigID'] > 0){
            echo Gig::getGigSetForm($_REQUEST['gigID']);
        }
        echo Gig::getEditSetForm();
        echo Gig::getSetPartsForm();
        echo Gig::getCopySetForm();
        echo Gig::getNewSetListForm();
        echo Gig::getDeleteSetForm();
    }

    if ('getNewSetListForm'==$_GET['action']){
        echo Gig::getEditSetForm();
        echo Gig::getSetPartsForm();
        echo Gig::getCopySetForm();
        echo Gig::getNewSetListForm();
        echo Gig::getDeleteSetForm();
    }

    if ('getNewPersonForm'==$_GET['action']){
        echo Arrangement::getNewSongForm();
        echo Arrangement::getSongs();
        echo Arrangement::getNewPersonForm();
        echo Arrangement::getPeople();
        echo User::getNewUserForm();
    }
    
    

}

} else{
    echo "<p><a href='../'>Index</a></p>";
}
?>
        </body>

</html>
