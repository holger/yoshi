<?php

namespace yoshi;

class RouteTest extends \PHPUnit_Framework_TestCase
{
  
  public function testRoutesShouldMatchRequestPaths() {
    $route = new Route('GET', '/test', function() { return 'GET /test'; });
    
    $request = Request::create('/test');
    $this->assertTrue($route->matches($request));
    
    $request = Request::create('/test', 'POST');
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

    $request = Request::create('/test', 'POST');
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
    
}