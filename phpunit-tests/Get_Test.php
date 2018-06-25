<?php

class Get_Test extends PHPUnit_Framework_TestCase
{
  private $debug = false;
  
  public function setup(){
  }

  public function tearDown(){
  }
  

  public function test_getOutputLink(){
	Render::getOutputLink( Arrangement::listAll(1) );
  }

  public function test_listAll(){
	 Arrangement::listAll(1);
  }

  public function test_pdfFromGet(){
	$in = array();
	Render::getOutputLink( Arrangement::pdfFromGet($in) );
	$in['arrangement'] = array(1);
	Render::getOutputLink( Arrangement::pdfFromGet($in) );
  }

  public function test_pdfFromGig(){
	$in = array();
	Render::getOutputLink( Gig::pdfFromGig($in) );
	$in['gigID'] = 1;
	$in['partID'] = 1;
	Render::getOutputLink( Gig::pdfFromGig($in) );
  }



}

