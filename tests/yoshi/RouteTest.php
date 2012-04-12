<?php

namespace yoshi;

class RouteTest extends \PHPUnit_Framework_TestCase
{
  
  public function testRoutesShouldMatchRequestPaths() {
    $route = new Route('GET', '/test', function() { return 'GET /test'; });
    
    $request = Request::create('/test');
    $this->assertTrue($route->matches($request));
    
    $request = Request::create('/test', '', 'POST');
    $this->assertFalse($route->matches($request));
    
    $request = Request::create('/tested');
    $this->assertFalse($route->matches($request));
  }
  
  public function testRoutesShouldMatchRequestPathsWithPattern() {
    $route = new Route('GET', '/user/{id}', function($id) { return 'GET /user/{id}'; });
    
    $request = Request::create('/user/1');
    $this->assertTrue($route->matches($request));
    
    $request = Request::create('/user/a');
    $this->assertTrue($route->matches($request));
    
    $request = Request::create('/user/1/');
    $this->assertFalse($route->matches($request));
    
    $request = Request::create('/user/');
    $this->assertFalse($route->matches($request));
  }
  
  public function testRoutesShouldExecuteCallbacksForMatchingRequests() {
    $route = new Route('GET', '/test', function() { return 'GET /test'; });

    $request = Request::create('/test');
    $this->assertEquals('GET /test', $route->execute($request));

    $request = Request::create('/test', '', 'POST');
    $this->assertEquals(null, $route->execute($request));

    $request = Request::create('/tested');
    $this->assertEquals(null, $route->execute($request));
  }

  public function testRoutesShouldExecuteCallbacksForMatchingRequestsIncludingPathArguments() {
    $route = new Route('GET', '/user/{id}', function($id) { return 'GET /user/'.$id; });
    
    $request = Request::create('/user/1');
    $this->assertEquals('GET /user/1', $route->execute($request));
    
    $request = Request::create('/user/a');
    $this->assertEquals('GET /user/a', $route->execute($request));
    
    $request = Request::create('/user/1/');
    $this->assertEquals(null, $route->execute($request));
    
    $request = Request::create('/user/');
    $this->assertEquals(null, $route->execute($request));
  }
  
  public function testMatchesPathShouldReturnTrueForMatchingPaths() {
    $route = new Route('GET', '/test', function() {});
    $this->assertTrue($route->matches('/test'));
    $this->assertFalse($route->matches('/testt'));
  }
  
  public function testMatchesPathShouldReturnTrueForMatchingPathsAndParameters() {
    $route = new Route('GET', '/test/{id}', function($id) {});
    $this->assertTrue($route->matches('/test/1234'));
  }
  
  public function testMatchesNameShouldReturnTrueForMatchingNames() {
    $route = new Route('GET', '/test', function() {});
    $route->named('root');
    $this->assertTrue($route->matches('root'));
    $this->assertFalse($route->matches('rooot'));
  }
  
  public function testLink() {
    $route = new Route('GET', '/test', function() {});
    $request = Request::create('/someothersite');
    
    $this->assertEquals('/test', $route->link($request));
  }
  
  public function testLinkIncludingWebroot() {
    $route = new Route('GET', '/test', function() {});
    $request = Request::create('/webroot/someothersite', '/webroot');
    
    $this->assertEquals('/webroot/test', $route->link($request));
  }
  
  public function testLinkWithParameters() {
    $route = new Route('GET', '/test/{id}', function($id) {});
    $request = Request::create('/someothersite');
    
    $this->assertEquals('/test/1', $route->link($request, 1));
    $this->assertEquals('/test/1', $route->link($request, array(1, 2)));
    $this->assertEquals('/test/', $route->link($request));    
  }
  
  public function testLinkWithMultipleParameters() {
    $route = new Route('GET', '/test/{id}/sub/{x}', function($id, $x) {});
    $request = Request::create('/someothersite');
    
    $this->assertEquals('/test/1/sub/2', $route->link($request, array(1, 2)));
    $this->assertEquals('/test/1/sub/2', $route->link($request, array(1, 2, 3)));
    $this->assertEquals('/test//sub/', $route->link($request));
  }
    
}