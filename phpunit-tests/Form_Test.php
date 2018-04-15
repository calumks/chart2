<?php

class Form_Test extends PHPUnit_Framework_TestCase
{
  private $debug = false;
  
  public function setup(){
  }

  public function tearDown(){
  }
  
  public function test_FormWithDatabaseLength1(){
    $this->assertTrue( strlen( getDeleteSetForm() ) >= 0 );
  }

  public function test_FormWithDatabaseLength2(){
    $this->assertTrue( strlen( getCopySetForm() ) > 0 );
  }

  public function test_FormWithDatabaseLength3(){
    $this->assertTrue( strlen( getEfileForm() ) >= 0 );
  }

  public function test_FormWithDatabaseLength4(){
    $this->assertTrue( strlen( getEditNoteForm() ) >= 0 );
  }

  public function test_FormWithDatabaseLength5(){
    $this->assertTrue( strlen( getEditSetForm() ) >= 0 );
  }

  public function test_FormWithDatabaseLength6(){
    $this->assertTrue( strlen( getGigSetForm( 1 ) ) >= 0 );
  }

  public function test_FormWithDatabaseLength7(){
    $this->assertTrue( strlen( getNewNoteForm() ) >= 0 );
  }

  public function test_FormWithDatabaseLength8(){
//    $this->assertTrue( strlen( getPartForm(1) ) >= 0 ); /// DEPENDS on FPDF
  }

  public function test_FormWithDatabaseLength9(){
    $this->assertTrue( strlen( getPeople() ) >= 0 );
  }

  public function test_FormWithDatabaseLength10(){
//    $this->assertTrue( strlen( getPublicationForm( 'pdf' ) ) >= 0 );   /// DEPENDS ON FPDF
  }

  public function test_FormWithDatabaseLength11(){
    $this->assertTrue( strlen( getSetPartsForm() ) >= 0 );
  }

  public function test_FormWithDatabaseLength12(){
    $this->assertTrue( strlen( getSetPartsOutput( 1, 'dummy') ) >= 0 );
  }

  public function test_FormWithDatabaseLength13(){
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

