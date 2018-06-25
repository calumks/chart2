<?php

class Put_Test extends PHPUnit_Framework_TestCase
{
  private $debug = false;
  
  public function setup(){
  }

  public function tearDown(){
  }
  
  public function test_StoreEmail(){
    $this->assertTrue( !User::storeEmail() );
  }

  public function test_ReceiveFile(){
	Arrangement::receiveFile();
  }

  public function test_DeleteFile(){
	Arrangement::deleteFile( 'noFile.abc');
  }


  public function test_postNewPerson(){
        Arrangement::postNewPerson();
}

  public function test_storeNewUser(){
                User::storeNewUser('dud@gmail.com','Dopey');
}

  public function test_postNewSong(){
        Arrangement::postNewSong();
}


  public function test_setPublication(){
        Arrangement::setPublication();
}

  public function test_deletePartPage(){
            Arrangement::deletePartPage( 1 );
}

  public function test_setPartPage(){
            Arrangement::setPartPage( 1, 1, 1, 1);
}

  public function test_addNote(){
            Arrangement::addNote(1, 'Some text');
            Arrangement::updateNote(1, 'Some text');
}

  public function test_deleteNote(){
            Arrangement::deleteNote(1);
}

  public function test_toggle(){
            Arrangement::addToBackup( 1, 1 );
            Arrangement::addToBackup( 1, 0 );
            Arrangement::addToPads( 1, 1 );
            Arrangement::addToPads( 1, 0 );
}

  public function test_deleteSetListPart(){
            Gig::deleteSetListPart(1);
}

  public function test_addToSet(){
            Gig::addToSet(1, 10, 1);
}

  public function test_postNewSetList(){
	$_in = array('isGig'=>'isPublic','gigName'=>'A good name','gigDate'=>'2018');
        Gig::postNewSetList($_in);
}

  public function test_copySetList(){
            Gig::copySetList( 1, 2);
}

  public function test_deleteSet(){
        Gig::deleteSet();
	$_in = array('gigID'=>1);
        Gig::deleteSet($_in);
}

  public function test_SetCookie(){
//	deleteCookie(); Can't test -- it sets cookies
    $this->assertTrue( !User::setValidCookie( 'dudCode' ) );
  }


}

