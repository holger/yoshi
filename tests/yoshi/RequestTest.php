<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

class RequestTest extends \PHPUnit_Framework_TestCase
{
  
  public function testRequestPartShouldBeSplittedFromRequestUri() {
    $request = Request::create(false, 'localhost', '/test?url=1234');
    $this->assertEquals('GET', $request->method());
    $this->assertEquals('/test?url=1234', $request->uri());
    $this->assertEquals('/test', $request->uriPath());
    
    $request = Request::create(false, 'localhost', 'http://www.test.de/test?url=1234');
    $this->assertEquals('GET', $request->method());
    $this->assertEquals('http://www.test.de/test?url=1234', $request->uri());
    $this->assertEquals('/test', $request->uriPath());
  }
  
  public function testCreateFromGlobalsShouldUseServerVariables() {
    $_SERVER['REQUEST_URI'] = '/test?url=1234';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $request = Request::createFromGlobals();
    
    $this->assertEquals('GET', $request->method());
    $this->assertEquals('/test?url=1234', $request->uri());
    $this->assertEquals('/test', $request->uriPath());
  
    $_SERVER['REQUEST_URI'] = 'http://www.test.de/test?url=1234';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $request = Request::createFromGlobals();

    $this->assertEquals('GET', $request->method());
    $this->assertEquals('http://www.test.de/test?url=1234', $request->uri());
    $this->assertEquals('/test', $request->uriPath());
  }

  public function testRootUriFromGlobals() {
      // $_SERVER['HTTPS']
      $_SERVER['SERVER_NAME'] = 'localhost';
      $_SERVER['SCRIPT_NAME'] = '/yoshi/index.php';
      $_SERVER['REQUEST_URI'] = '/yoshi/login';
      $_SERVER['REQUEST_METHOD'] = 'GET';

      $request = Request::createFromGlobals();

      $this->assertEquals('http://localhost/yoshi', $request->rootUri());
  }
  
  public function testCreateWithScriptNameShouldSetBaseUri() {
    $request = Request::create(false, 'localhost', '/webroot/test', '/webroot');
    
    $this->assertEquals('/webroot', $request->baseUri());
  }
  
  public function testCreateFormGlobalsWithScriptNameShouldSetBaseUri() {
    $_SERVER['REQUEST_URI'] = '/webroot/test';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['SCRIPT_NAME'] = '/webroot';
    $request = Request::createFromGlobals();
    
    $this->assertEquals('/webroot', $request->baseUri());
  }
  
  public function testToStringShouldReturnMethodAndUri() {
    $request = Request::create(false, 'localhost', '/test?url=1234');
    
    $this->assertEquals('GET /test?url=1234', (string)$request);
  }
    
}