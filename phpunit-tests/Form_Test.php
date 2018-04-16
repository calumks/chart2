<?php

class Form_Test extends PHPUnit_Framework_TestCase
{
  private $debug = false;
  
  public function setup(){
  }

  public function tearDown(){
  }
  
// forms with probably zero length in a blank database

  public function test_FormDeleteSetForm(){
    $this->assertTrue( strlen( getDeleteSetForm() ) >= 0 );
  }

  public function test_FormCopySetForm(){
    $this->assertTrue( strlen( getCopySetForm() ) >= 0 );
  }

  public function test_FormEfileForm(){
    $this->assertTrue( strlen( getEfileForm() ) >= 0 );
  }

  public function test_FormEditNoteForm(){
    $this->assertTrue( strlen( getEditNoteForm() ) >= 0 );
  }

  public function test_FormEditSetForm(){
    $this->assertTrue( strlen( getEditSetForm() ) >= 0 );
  }

  public function test_FormGigSetForm(){
    $this->assertTrue( strlen( getGigSetForm( 1 ) ) >= 0 );
  }

  public function test_FormNewNoteForm(){
    $this->assertTrue( strlen( getNewNoteForm() ) >= 0 );
  }

  public function test_FormPartFormNOTRUN(){
//    $this->assertTrue( strlen( getPartForm(1) ) >= 0 ); /// DEPENDS on FPDF
  }

  public function test_FormPeople(){
    $this->assertTrue( strlen( getPeople() ) >= 0 );
  }

  public function test_FormPublicationFormNOTRUN(){
//    $this->assertTrue( strlen( getPublicationForm( 'pdf' ) ) >= 0 );   /// DEPENDS ON FPDF
  }

  public function test_FormSetParts(){
    $this->assertTrue( strlen( getSetPartsForm() ) >= 0 );
  }

  public function test_FormSetPartsOutput(){
    $this->assertTrue( strlen( getSetPartsOutput( 1, 'dummy') ) >= 0 );
  }

  public function test_FormGetSongs(){
    $this->assertTrue( strlen( getSongs() ) >= 0 );
  }

// forms with positive length (even in a blank database)

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

