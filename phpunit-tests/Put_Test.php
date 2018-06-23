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


  public function test_postNewPerson(){
        postNewPerson();
}

  public function test_storeNewUser(){
                storeNewUser('dud@gmail.com','Dopey');
}

  public function test_postNewSong(){
        postNewSong();
}


  public function test_setPublication(){
        setPublication();
}

  public function test_deletePartPage(){
            deletePartPage( 1 );
}

  public function test_setPartPage(){
            setPartPage( 1, 1, 1, 1);
}

  public function test_addNote(){
            addNote(1, 'Some text');
            updateNote(1, 'Some text');
}

  public function test_deleteNote(){
            deleteNote(1);
}

  public function test_toggle(){
            addToBackup( 1, 1 );
            addToBackup( 1, 0 );
            addToPads( 1, 1 );
            addToPads( 1, 0 );
}

  public function test_deleteSetListPart(){
            deleteSetListPart(1);
}

  public function test_addToSet(){
            addToSet(1, 10, 1);
}

  public function test_postNewSetList(){
	$_in = array('isGig'=>'isPublic','gigName'=>'A good name','gigDate'=>'2018');
        postNewSetList($_in);
}

  public function test_copySetList(){
            copySetList( 1, 2);
}

  public function test_deleteSet(){
        deleteSet();
	$_in = array('gigID'=>1);
        deleteSet($_in);
}

  public function test_SetCookie(){
//	deleteCookie(); Can't test -- it sets cookies
    $this->assertTrue( !setValidCookie( 'dudCode' ) );
  }


}

