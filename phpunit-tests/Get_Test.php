<?php

class Get_Test extends PHPUnit_Framework_TestCase
{
  private $debug = false;
  
  public function setup(){
  }

  public function tearDown(){
  }
  

  public function test_getOutputLink(){
	getOutputLink( listAll(1) );
  }

  public function test_listAll(){
	 listAll(1);
  }

  public function test_pdfFromGet(){
	$in = array();
	getOutputLink( pdfFromGet($in) );
	$in['arrangement'] = array(1);
	getOutputLink( pdfFromGet($in) );
  }

  public function test_pdfFromGig(){
	$in = array();
	getOutputLink( pdfFromGig($in) );
	$in['gigID'] = 1;
	$in['partID'] = 1;
	getOutputLink( pdfFromGig($in) );
  }



}

