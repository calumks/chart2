<?php

class Put_Test extends PHPUnit_Framework_TestCase
{
  private $debug = false;
  
  public function setup(){
  }

  public function tearDown(){
  }
  
  public function test_StoreEmail(){
    $this->assertTrue( !storeEmail() );
  }

  public function test_ReceiveFile(){
	receiveFile();
  }

  public function test_DeleteFile(){
	deleteFile( 'noFile.abc');
  }

/*
  public function test_postNewPerson(){
        postNewPerson();
}

  public function test_MiscPut1(){
//        postNewPerson();
                storeNewUser($_POST['newEmail'],$_POST['newNickName']);
        postNewSong();
        setPublication();
            deletePartPage( $_POST['efilePartID'] );
            setPartPage( $_POST['efileID'], $_POST['partID'], $_POST['startPage'], $_POST['endPage']);
            addNote($_POST['publicationID'], $_POST['noteText']);
            deleteNote($_POST['noteID']);
            updateNote($_POST['noteID'], $_POST['noteText']);
            addToBackup( $_POST['arrangementID'], 1 );
            addToBackup( $_POST['arrangementID'], 0 );
            addToPads( $_POST['arrangementID'], 1 );
            addToPads( $_POST['arrangementID'], 0 );
            deleteSetListPart($_POST['setListID']);
            addToSet($_POST['gigID'], $_POST['setListOrder'], $_POST['arrangementID']);
        postNewSetList();
            copySetList( $_POST['sourceGigID'], $_POST['targetGigID']);
        deleteSet();
}
*/

  public function test_SetCookie(){
//	deleteCookie(); Can't test -- it sets cookies
    $this->assertTrue( !setValidCookie( 'dudCode' ) );
  }


}

