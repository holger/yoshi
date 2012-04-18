<?php

namespace yoshi;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
  
  public function testSendShouldEchoContents() {
    $response = new Response();
    $response->setContents('Test Content');
    
    $this->assertEquals('Test Content', $this->result($response));
  }
  
  private function result($response) {
    ob_start();
    $response->send();
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }
    
}

?>