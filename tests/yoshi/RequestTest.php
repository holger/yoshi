<?php

namespace yoshi;

class RequestTest extends \PHPUnit_Framework_TestCase
{
  
  public function testRequestPartShouldBeSplittedFromRequestUri() {
    $request = Request::create('/test?url=1234');
    $this->assertEquals('GET', $request->method());
    $this->assertEquals('/test?url=1234', $request->uri());
    $this->assertEquals('/test', $request->uriPath());
    
    $request = Request::create('http://www.test.de/test?url=1234');
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
  
  public function testCreateWithScriptNameShouldSetRootUri() {
    $request = Request::create('/webroot/test', '/webroot');
    
    $this->assertEquals('/webroot', $request->rootUri());
  }
  
  public function testCreateFormGlobalsWithScriptNameShouldSetRootUri() {
    $_SERVER['REQUEST_URI'] = '/webroot/test';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['SCRIPT_NAME'] = '/webroot';
    $request = Request::createFromGlobals();
    
    $this->assertEquals('/webroot', $request->rootUri());
  }
  
  public function testToStringShouldReturnMethodAndUri() {
    $request = Request::create('/test?url=1234');
    
    $this->assertEquals('GET /test?url=1234', (string)$request);
  }
    
}