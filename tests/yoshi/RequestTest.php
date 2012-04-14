<?php

namespace yoshi;

class RequestTest extends \PHPUnit_Framework_TestCase
{
  
  public function testRequestPartShouldBeSplittedFromRequestUri() {
    $request = Request::create('/test?url=1234');
    $this->assertEquals('GET', $request->getRequestMethod());
    $this->assertEquals('/test?url=1234', $request->getRequestUri());
    $this->assertEquals('/test', $request->getRequestUriPath());
    
    $request = Request::create('http://www.test.de/test?url=1234');
    $this->assertEquals('GET', $request->getRequestMethod());
    $this->assertEquals('http://www.test.de/test?url=1234', $request->getRequestUri());
    $this->assertEquals('/test', $request->getRequestUriPath());
  }
  
  public function testCreateFromGlobalsShouldUseServerVariables() {
    $_SERVER['REQUEST_URI'] = '/test?url=1234';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $request = Request::createFromGlobals();
    
    $this->assertEquals('GET', $request->getRequestMethod());
    $this->assertEquals('/test?url=1234', $request->getRequestUri());
    $this->assertEquals('/test', $request->getRequestUriPath());
  
    $_SERVER['REQUEST_URI'] = 'http://www.test.de/test?url=1234';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $request = Request::createFromGlobals();

    $this->assertEquals('GET', $request->getRequestMethod());
    $this->assertEquals('http://www.test.de/test?url=1234', $request->getRequestUri());
    $this->assertEquals('/test', $request->getRequestUriPath());
  }
  
  public function testCreateWithScriptNameShouldSetRootUri() {
    $request = Request::create('/webroot/test', '/webroot');
    
    $this->assertEquals('/webroot', $request->getRootUri());
  }
  
  public function testCreateFormGlobalsWithScriptNameShouldSetRootUri() {
    $_SERVER['REQUEST_URI'] = '/webroot/test';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['SCRIPT_NAME'] = '/webroot';
    $request = Request::createFromGlobals();
    
    $this->assertEquals('/webroot', $request->getRootUri());
  }
    
}