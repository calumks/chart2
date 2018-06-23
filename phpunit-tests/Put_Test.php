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


}

