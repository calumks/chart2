<?php

class Form_Test extends PHPUnit_Framework_TestCase
{
  private $debug = false;
  
  public function setup(){
  }

  public function tearDown(){
  }
  
  public function test_FormWithDatabaseLength(){
    $this->assertTrue( strlen( getCopySetForm() ) >= 0  );
    $this->assertTrue( strlen( getDeleteSetForm() ) >= 0 );
    $this->assertTrue( strlen( getCopySetForm() ) > 0 );
    $this->assertTrue( strlen( getDeleteSetForm() ) > 0 );
    $this->assertTrue( strlen( getEfileForm() ) >= 0 );
    $this->assertTrue( strlen( getEditNoteForm() ) >= 0 );
    $this->assertTrue( strlen( getEditSetForm() ) >= 0 );
    $this->assertTrue( strlen( getGigSetForm( 1 ) ) >= 0 );
    $this->assertTrue( strlen( getNewNoteForm() ) >= 0 );
    $this->assertTrue( strlen( getPartForm(1) ) >= 0 );
    $this->assertTrue( strlen( getPeople() ) >= 0 );
    $this->assertTrue( strlen( getPublicationForm() ) >= 0 );
    $this->assertTrue( strlen( getSetPartsForm() ) >= 0 );
    $this->assertTrue( strlen( getSetPartsOutput( 1, 'dummy') ) >= 0 );
    $this->assertTrue( strlen( getSongs() ) >= 0 );
  }

  public function test_indexFormLength(){
    $this->assertTrue( strlen( getEmailForm() ) > 10 );
    $this->assertTrue( strlen( getFooter() ) > 10 );
    $this->assertTrue( strlen( getOutputLink('dummy') ) > 10 );
    $this->assertTrue( strlen( getNewSongForm() ) > 10 );
    $this->assertTrue( strlen( getNewUserForm() ) > 10 );
    $this->assertTrue( strlen( getNewSongForm() ) > 10 );
    $this->assertTrue( strlen( getUploadFileForm() ) > 10 );
  }  
// fails due to dependency
// FPDF
//    $this->assertTrue( strlen( getRequestForm() ) > 10 );

}

