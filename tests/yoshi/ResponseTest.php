<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
  
  public function testSendShouldEchoContents() {
    $response = new Response();
    $response->contents('Test Content');
    
    $this->assertEquals('Test Content', $this->result($response));
  }
  
  private function result($response) {
    ob_start();
    $response->send();
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }
  
  public function testStatusShouldConcatStatusCodeAndReasonPhrase() {
    $response = new Response();
    $response->status(404, 'Nothing Found');
    
    $this->assertEquals('404 Nothing Found', $response->status());
  }
  
  public function testStatusShouldUsePredefinedReasonPhrasesForMatchingStatusCodes() {
    $response = new Response();
    
    $response->status(404);
    $this->assertEquals('404 Not Found', $response->status());
    
    $response->status(405);
    $this->assertEquals('405 Method Not Allowed', $response->status());
  }
  
  public function testAddHeaderShouldAddHeaderToHeadersList() {
    $response = new Response();
    
    $response->header('some header');
    
    $this->assertContains('some header', $response->headers());
  }
    
}

?>