<?php

namespace yoshi;

class RouterTest extends \PHPUnit_Framework_TestCase {
  
  public function testGetShouldAddARouteForHttpGet() {
    $router = new Router();
    
    $router->get('/test', function() {});
    $routes = $router->routes();
    
    $this->assertAdditionOfOneRoute($routes, '/test', 'GET');
  }
  
  public function testPostShoudAddARouteForHttpPost() {
    $router = new Router();
    
    $router->post('/test', function() {});
    $routes = $router->routes();
    
    $this->assertAdditionOfOneRoute($routes, '/test', 'POST');
  }
  
  public function testPutShoudAddARouteForHttpPost() {
    $router = new Router();
    
    $router->put('/test', function() {});
    $routes = $router->routes();
    
    $this->assertAdditionOfOneRoute($routes, '/test', 'PUT');
  }
  
  public function testDeleteShoudAddARouteForHttpPost() {
    $router = new Router();
    
    $router->delete('/test', function() {});
    $routes = $router->routes();
    
    $this->assertAdditionOfOneRoute($routes, '/test', 'DELETE');
  }

  public function testHeadShoudAddARouteForHttpPost() {
    $router = new Router();

    $router->head('/test', function() {});
    $routes = $router->routes();

    $this->assertAdditionOfOneRoute($routes, '/test', 'HEAD');
  }

  public function testOptionsShoudAddARouteForHttpPost() {
    $router = new Router();
  
    $router->options('/test', function() {});
    $routes = $router->routes();
  
    $this->assertAdditionOfOneRoute($routes, '/test', 'OPTIONS');
  }
  
  private function assertAdditionOfOneRoute($routes, $path, $http_method) {
    $this->assertEquals(1, count($routes));
    $this->assertTrue(is_array($routes));
    $this->assertTrue($routes[0] instanceof Route);
    $this->assertTrue($routes[0]->matches(Request::create($path, null, $http_method)));
  }
  
  public function testHandleShouldReturnResponse() {
    $router = new Router();
    $router->get('/test', function() {});
    $request = Request::create('/test');
    
    $response = $router->handle($request);
    
    $this->assertNotNull($response);
    $this->assertTrue($response instanceof Response, 'handleRequest should return an instance of Response');
  }
  
  public function testHandleShouldExecuteMatchingRouteForRequest() {
    $router = new Router();
    $router->get('/test', function() { return 'GET /test called'; });
    $request = Request::create('/test');

    $response = $router->handle($request);
    
    $this->assertNotNull($response);
    $this->assertTrue($response instanceof Response, 'handleRequest should return an instance of Response');
    $this->assertEquals('GET /test called', $response->contents());
  }
  
  public function testHandleShouldReturnAHttpError404ForUnknownRequests() {
    $router = new Router();
    $router->get('/test', function() {});
    $request = Request::create('/unknown-route');
    
    $response = $router->handle($request);
    
    $this->assertNotNull($response);
    $this->assertTrue($response instanceof Response, 'handleRequest should return an instance of Response');
    $this->assertEquals('404 Not Found', $response->status());
  }
  
  public function testHandleShouldReturnAHttpError403ForRequestWithAnUnsupportedHttpMethod() {
    $router = new Router();
    $router->get('/test', function() {});
    $request = Request::create('/test', null, 'POST');
    
    $response = $router->handle($request);
    
    $this->assertNotNull($response);
    $this->assertTrue($response instanceof Response, 'handleRequest should return an instance of Response');
    $this->assertEquals('405 Method Not Allowed', $response->status());
  }
  
}

?>