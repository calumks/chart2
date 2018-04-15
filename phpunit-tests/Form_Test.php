<?php

class Form_Test extends PHPUnit_Framework_TestCase
{
  private $debug = false;
  
  public function setup(){
  }

  public function tearDown(){
	}
  

  public function test_indexFormLength(){
    $this->assertTrue( strlen( getEmailForm() ) > 10 );
    $this->assertTrue( strlen( getFooter() ) > 10 );
    $this->assertTrue( strlen( getOutputLink('dummy') ) > 10 );
    $this->assertTrue( strlen( getNewSongForm() ) > 10 );
    $this->assertTrue( strlen( getNewUserForm() ) > 10 );
    $this->assertTrue( strlen( getNewSongForm() ) > 10 );
    $this->assertTrue( strlen( getUploadFileForm() ) > 10 );
/*
    fails due to dependency
// FPDF
    $this->assertTrue( strlen( getRequestForm() ) > 10 );

// mysqli
    $this->assertTrue( strlen( getCopySetForm() ) > 10 );
    $this->assertTrue( strlen( getDeleteSetForm() ) > 10 );
    $this->assertTrue( strlen( getEfileForm() ) > 10 );
    $this->assertTrue( strlen( getEditNoteForm() ) > 10 );
    $this->assertTrue( strlen( getEditSetForm() ) > 10 );
    $this->assertTrue( strlen( getGigSetForm( 1 ) ) > 10 );
    $this->assertTrue( strlen( getNewNoteForm() ) > 10 );
    $this->assertTrue( strlen( getPartForm(1) ) > 10 );
    $this->assertTrue( strlen( getPeople() ) > 10 );
    $this->assertTrue( strlen( getPublicationForm() ) > 10 );
    $this->assertTrue( strlen( getSetPartsForm() ) > 10 );
    $this->assertTrue( strlen( getSetPartsOutput( 1, 'dummy') ) > 10 );
    $this->assertTrue( strlen( getSongs() ) > 10 );
*/
  }  


}

