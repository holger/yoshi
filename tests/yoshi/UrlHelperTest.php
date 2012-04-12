<?php

namespace yoshi;

class UrlHelperTest extends \PHPUnit_Framework_TestCase
{
  
  public function testLinkShouldReturnLinkForPaths() {
    $app = new Application();
    $app->get('/test', function() {});
    $request = Request::create('/test');
    
    $helper = new UrlHelper($app->routes());
    
    $this->assertEquals('/test', $helper->link($request, '/test'));
  }
  
  public function testLinkShouldReturnLinkForPathsIncludingWebroot() {
    $app = new Application();
    $app->get('/test', function() {});
    $request = Request::create('/webroot/test', '/webroot');

    $helper = new UrlHelper($app->routes());

    $this->assertEquals('/webroot/test', $helper->link($request, '/test'));
  }
  
  public function testLinkShouldReturnLinkForNamedRoutes() {
    $app = new Application();
    $app->get('/test', function() {})->named('root');
    $request = Request::create('/test');
    
    $helper = new UrlHelper($app->routes());
    
    $this->assertEquals('/test', $helper->link($request, 'root'));
  }

  public function testLinkShouldReturnLinkForNamedRoutesIncludingWebroot() {
    $app = new Application();
    $app->get('/test', function() {})->named('root');
    $request = Request::create('/webroot/test', '/webroot');

    $helper = new UrlHelper($app->routes());

    $this->assertEquals('/webroot/test', $helper->link($request, 'root'));
  }
  
  public function testLinkShouldReturnLinkForNamedRoutesWithParameters() {
    $app = new Application();
    $app->get('/test/{id}', function($id) {})->named('root');
    $request = Request::create('/test');
    
    $helper = new UrlHelper($app->routes());
    
    $this->assertEquals('/test/12345', $helper->link($request, 'root', 12345));
  }

}

?>