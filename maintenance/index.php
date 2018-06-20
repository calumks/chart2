<?php 
// do all post stuff first then redirect
//echo "\n\n GET";
//print_r($_GET);
//echo "\n\n POST";
//print_r($_POST);
//echo "\n\n FILES";
//print_r($_FILES);
//
// authenticate user.  Valid cookie or no valid cookie
// if valid cookie, provide content
// if no valid cookie, go to main menu
include_once "../include_refsB.php";
//include_once "include_refsMain.php";
if ($_POST){
if (hasValidCookie()){
if (isset($_POST['action'])){

if (hasAdminCookie()){
    if ('uploadPDF'==$_POST['action']){
        receiveFile();
    }

    if ('deletePDF'==$_POST['action'] && isset( $_POST['fileNameExclPath'])){
        deleteFile($_POST['fileNameExclPath']);
    }

    if ('addPerson'==$_POST['action']){
        postNewPerson();
//        echo getNewSongForm();
//        echo getSongs();
//        echo getNewPersonForm();
//        echo getPeople();
    }

    if ('addNewUser'==$_POST['action']){
        if (isset($_POST['newEmail']) && isset($_POST['newNickName'])){
            if (strlen($_POST['newNickName']) > 3){
                storeNewUser($_POST['newEmail'],$_POST['newNickName']);
            }
        }
    }
    
    if ('addSong'==$_POST['action']){
        postNewSong();
//        echo getNewSongForm();
//        echo getSongs();
//        echo getNewPersonForm();
//        echo getPeople();
    }

    if ('setPublication'==$_REQUEST['action']){
        setPublication();
//        echo getPublicationForm();
//        echo print_r(listPdfUnlisted(),1);
//        listPdf();
    }
}

    if ('deleteEfilePart'==$_POST['action']){
        if(isset( $_POST['efilePartID']) ){
            deletePartPage( $_POST['efilePartID'] );
        }
    }

    if ('addEfilePart'==$_POST['action']){
        if(isset( $_POST['efileID']) && isset($_POST['partID']) && isset($_POST['startPage']) && isset($_POST['endPage'])){
            setPartPage( $_POST['efileID'], $_POST['partID'], $_POST['startPage'], $_POST['endPage']);
        }
    }

    if ('addNote'==$_POST['action']){
        if(isset( $_POST['noteText'])  && isset( $_POST['publicationID'])){
            addNote($_POST['publicationID'], $_POST['noteText']);
        }
    }

    if ('deleteNote'==$_POST['action']){
        if( isset( $_POST['noteID'])){
            deleteNote($_POST['noteID']);
        }
    }

    if ('updateNote'==$_POST['action']){
        if(isset( $_POST['noteText'])  && isset( $_POST['noteID'])){
            updateNote($_POST['noteID'], $_POST['noteText']);
        }
    }


    if ('addToBackup'==$_POST['action']){
        if(isset( $_POST['arrangementID']) ){
            addToBackup( $_POST['arrangementID'], 1 );
        }
    }

    if ('removeFromBackup'==$_POST['action']){
        if(isset( $_POST['arrangementID']) ){
            addToBackup( $_POST['arrangementID'], 0 );
        }
    }


    if ('addToPads'==$_POST['action']){
        if(isset( $_POST['arrangementID']) ){
            addToPads( $_POST['arrangementID'], 1 );
        }
    }

    if ('removeFromPads'==$_POST['action']){
        if(isset( $_POST['arrangementID']) ){
            addToPads( $_POST['arrangementID'], 0 );
        }
    }

    if(isset( $_POST['action']) && 'deleteSetListPart'==$_POST['action']){
        if(isset( $_POST['setListID'])) {
            deleteSetListPart($_POST['setListID']);
        }
    }

    if(isset( $_POST['action']) && 'addSetListPart'==$_POST['action']){
        if(isset( $_POST['gigID'])) {
            addToSet($_POST['gigID'], $_POST['setListOrder'], $_POST['arrangementID']);
        }
    }


    if ('addSetList'==$_POST['action']){
        postNewSetList();
//        echo getEditSetForm();
//        echo getNewSetListForm();
//        echo getDeleteSetForm();
    }


    if ('copySetList'==$_POST['action']){
        if (isset($_POST['sourceGigID']) && isset($_POST['targetGigID'])){
            copySetList( $_POST['sourceGigID'], $_POST['targetGigID']);
        }
    }



    if ('deleteSetList'==$_REQUEST['action']){
        deleteSet();
//        echo getEditSetForm();
//        echo getNewSetListForm();
//        echo getDeleteSetForm();
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
//echo "\n\n GET";
//print_r($_GET);
//echo "\n\n POST";
//print_r($_POST);
//echo "\n\n FILES";
//print_r($_FILES);

// authenticate user.  Valid cookie or no valid cookie
// if valid cookie, provide content
// if no valid cookie, go to main menu
include_once "../include_refsB.php";
//include_once "include_refsMain.php";
if (hasValidCookie()){
echo "<p><a href='../'>Main menu</a></p>";
echo "<p><a href='./?action=getNewSetListForm'>Edit set</a></p>";
echo "<p><a href='./?action=getNotes'>Edit notes</a></p>";
if (hasAdminCookie()){
//echo "<p><a href='./?action=getNewPublicationForm'>Add publication</a></p>";
echo "<p><a href='./?action=getParts'>Assign parts</a></p>";
echo "<p><a href='./?action=listPdf'>Add pdf</a></p>";
echo "<p><a href='./?action=getNewPersonForm'>Add song/person</a></p>";
}

if (isset($_GET['action'])){
    if ('getParts'==$_GET['action']){
        if (isset($_GET['publicationID'])){
            echo getEFileForm($_GET['publicationID']);
        } else {
            echo getEFileForm();
        }    
    }

    if ('getPartsForSet'==$_GET['action']){
        if (isset($_GET['gigID'])){
            getSetPartsOutput( $_GET['gigID'], dirname(getcwd()));
            echo "<a href='../output/'>Output directory</a>";

        }
    }

    if ('getNotes'==$_GET['action']){
        echo getNewNoteForm();
        echo getEditNoteForm();
        
    }

    if ('getEfileParts'==$_GET['action']){
        if (isset($_GET['efileID']) && $_GET['efileID'] > 0){
            echo getPartForm($_GET['efileID']);
        }
        echo getEFileForm();
    }

    if ('listPdf'==$_GET['action']){
        listPdf();
        echo getPublicationForm();
//        echo print_r(listPdfUnlisted(),1);
        echo getUploadFileForm();
    }

    if ('getSetList'==$_GET['action']){
        if (isset($_REQUEST['gigID']) && $_REQUEST['gigID'] > 0){
            echo getGigSetForm($_REQUEST['gigID']);
        }
        echo getEditSetForm();
        echo getSetPartsForm();
        echo getCopySetForm();
        echo getNewSetListForm();
        echo getDeleteSetForm();
    }

    if ('getNewSetListForm'==$_GET['action']){
        echo getEditSetForm();
        echo getSetPartsForm();
        echo getCopySetForm();
        echo getNewSetListForm();
        echo getDeleteSetForm();
//        echo getPopular();
    }

    if ('getNewPersonForm'==$_GET['action']){
        echo getNewSongForm();
        echo getSongs();
        echo getNewPersonForm();
        echo getPeople();
        echo getNewUserForm();
    }

//    if ('getNewPublicationForm'==$_REQUEST['action']){
//        echo getPublications();
//    }
    
    

}

} else{
    echo "<p><a href='../'>Index</a></p>";
}
?>
        </body>

</html>
