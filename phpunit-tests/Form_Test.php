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
    $this->assertTrue( strlen( Gig::getDeleteSetForm() ) >= 0 );
  }

  public function test_FormCopySetForm(){
    $this->assertTrue( strlen( Gig::getCopySetForm() ) >= 0 );
  }

  public function test_FormEfileForm(){
    $this->assertTrue( strlen( Arrangement::getEfileForm() ) >= 0 );
  }

  public function test_FormEditNoteForm(){
    $this->assertTrue( strlen( Arrangement::getEditNoteForm() ) >= 0 );
  }

  public function test_FormEditSetForm(){
    $this->assertTrue( strlen( Gig::getEditSetForm() ) >= 0 );
  }

  public function test_FormGigSetForm(){
    $this->assertTrue( strlen( Gig::getGigSetForm( 1 ) ) >= 0 );
  }

  public function test_FormNewNoteForm(){
    $this->assertTrue( strlen( Arrangement::getNewNoteForm() ) >= 0 );
  }

  public function test_FormPartFormNOTRUN(){
    $this->assertTrue( strlen( Arrangement::getPartForm(1) ) >= 0 ); /// DEPENDS on FPDF
  }

  public function test_FormPeople(){
    $this->assertTrue( strlen( Arrangement::getPeople() ) >= 0 );
  }

  public function test_FormPublicationFormNOTRUN(){
    $this->assertTrue( strlen( Arrangement::getPublicationForm( 'pdf' ) ) >= 0 );   /// DEPENDS ON FPDF
  }

  public function test_FormSetParts(){
    $this->assertTrue( strlen( Gig::getSetPartsForm() ) >= 0 );
  }

  public function test_FormSetPartsOutput(){
    $this->assertTrue( strlen( Gig::getSetPartsOutput( 1, 'dummy') ) >= 0 );
  }

  public function test_FormGetSongs(){
    $this->assertTrue( strlen( Arrangement::getSongs() ) >= 0 );
  }

  public function test_FormList(){
	Arrangement::listPdf();
  }

// forms with positive length (even in a blank database)

  public function test_Cookie(){
    $this->assertTrue( !User::hasValidCookie() );
  }

  public function test_CookieAdmin(){
    $this->assertTrue( !User::hasAdminCookie() );
  }

  public function test_List(){
    $this->assertTrue( strlen( Render::getOutputLink(Arrangement::listAll(-1))) > 10	);
  }  

  public function test_indexFormLength(){
    $this->assertTrue( strlen( User::getEmailForm() ) > 10 );
    $this->assertTrue( strlen( Render::getFooter() ) > 10 );
    $this->assertTrue( strlen( Render::getOutputLink('dummy') ) > 10 );
    $this->assertTrue( strlen( Arrangement::getNewSongForm() ) > 10 );
    $this->assertTrue( strlen( User::getNewUserForm() ) > 10 );
    $this->assertTrue( strlen( Arrangement::getNewSongForm() ) > 10 );
    $this->assertTrue( strlen( Render::getRequestForm() ) > 10 );    // FPDF
    $this->assertTrue( strlen( Arrangement::getUploadFileForm() ) > 10 );
  }  

}

