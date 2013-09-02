<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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
    $route = new Route('GET', '/test', function(Response $response) { $response->contents('GET /test'); });

    $request = Request::create('/test');
    $response = new ResponseMock();
    $route->execute($request, $response);
    $this->assertEquals('GET /test', $response->contents());

    $request = Request::create('/test', '', 'POST');
    $response = new ResponseMock();
    $route->execute($request, $response);
    $this->assertEquals(null, $response->contents());

    $request = Request::create('/tested');
    $response = new ResponseMock();
    $route->execute($request, $response);
    $this->assertEquals(null, $response->contents());
  }

  public function testRoutesShouldExecuteCallbacksForMatchingRequestsIncludingPathArguments() {
    $route = new Route('GET', '/user/{id}', function($id) { return 'GET /user/'.$id; });
    
    $request = Request::create('/user/1');
    $response = new ResponseMock();
    $route->execute($request, $response);
    $this->assertEquals('GET /user/1', $response->contents());
    
    $request = Request::create('/user/a');
    $response = new ResponseMock();
    $route->execute($request, $response);
    $this->assertEquals('GET /user/a', $response->contents());
    
    $request = Request::create('/user/1/');
    $response = new ResponseMock();
    $route->execute($request, $response);
    $this->assertEquals(null, $response->contents());
    
    $request = Request::create('/user/');
    $response = new ResponseMock();
    $route->execute($request, $response);
    $this->assertEquals(null, $response->contents());
  }
  
  public function testMatchesWithoutHttpMethodShouldReturnTrueForMatchingPaths() {
    $route = new Route('GET', '/test', function() {});
    $request = Request::create('/test', null, 'POST');
    
    $this->assertFalse($route->matches($request));
    $this->assertTrue($route->matchesWithoutHttpMethod($request));
  }
  
  public function testMatchesWithoutHttpMethodShouldReturnFalseForNonMatchingPaths() {
    $route = new Route('GET', '/test', function() {});
    $request = Request::create('/unknown-route', null, 'POST');
    
    $this->assertFalse($route->matches($request));
    $this->assertFalse($route->matchesWithoutHttpMethod($request));
  }
  
  public function testBeforeFilterShouldBeCalledBeforeCallbackGetsExecuted() {
    $route = new Route('GET', '/test', function(Response $response) { $response->appendContents('callback'); });
    $route->before(function(Response $response) { $response->appendContents('before1'); })
          ->before(function(Response $response) { $response->appendContents('before2'); });
    
    $response = new ResponseMock();
    $route->execute(Request::create('/test'), $response);
    
    $this->assertEquals('before1before2callback', $response->contents());
  }

  public function testAfterFilterShouldBeCalledAfterCallbackGetsExecuted() {
    $route = new Route('GET', '/test', function(Response $response) { $response->appendContents('callback'); });
    $route->after(function(Response $response) { $response->appendContents('after1'); })
          ->after(function(Response $response) { $response->appendContents('after2'); });

    $response = new ResponseMock();
    $route->execute(Request::create('/test'), $response);

    $this->assertEquals('callbackafter1after2', $response->contents());
  }

  public function testACompleteResponseShouldStopFurtherRouteExecution() {
      $route = new Route('GET', '/test', function() { return 'callback'; });
      $route->before(function(Response $response) { $response->sendRedirect('./login'); });

      $response = new ResponseMock();
      $route->execute(Request::create('/test'), $response);

      $this->assertEquals('', $response->contents());
      $this->assertContains('Location: ./login', $response->headers());
  }
    
}