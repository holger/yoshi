<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

use yoshi\exceptions\NotFoundException;
use yoshi\exceptions\MethodNotAllowedException;

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
  
  public function testHandleShouldReturnCallbackResponseForMatchingRoute() {
    $router = new Router();
    $router->get('/test', function() { return 'GET /test called'; });
    $request = Request::create('/test');

    $response = $router->handle($request);
    
    $this->assertEquals('GET /test called', $response);
  }
  
  public function testHandleShouldThrowANotFoundExceptionForUnknownRequests() {
    $router = new Router();
    $router->get('/test', function() {});
    $request = Request::create('/unknown-route');
    
    try {
      $response = $router->handle($request);
    } catch(NotFoundException $e) {
      return;
    }
    
    $this->fail('handle should throw exceptions for unknown routes');
  }
  
  public function testHandleShouldThrowAMethodNotAllowedExceptionForRequestWithAnUnsupportedHttpMethod() {
    $router = new Router();
    $router->get('/test', function() {});
    $request = Request::create('/test', null, 'POST');
    
    try {
      $response = $router->handle($request);
    } catch (MethodNotAllowedException $e) {
      return;
    }
    
    $this->fail('handle should throw a MethodNotAllowedException when a route would match for a diffrent method');
  }
  
  public function testHandleShouldAddAllowedMethodsForThrownMethodNotAllowedException() {
    $router = new Router();
    $router->get('/test', function() {});
    $request = Request::create('/test', null, 'POST');
    
    try {
      $response = $router->handle($request);
    } catch (MethodNotAllowedException $e) {
      $this->assertEquals('GET', $e->allowedMethods());
      return;
    }
    
    $this->fail('handle should throw a MethodNotAllowedException when a route would match for a diffrent method');
  }
  
  public function testRouterFiltersShouldBeExcecutedBeforeAnyRouteExecution() {
    $router = new Router();
    $router->before(function() { return 'before1'; })
           ->before(function() { return 'before2'; })
           ->after(function() { return 'after1'; })
           ->after(function() { return 'after2'; });
    
    $router->get('/test', function() { return 'test'; });
    $router->get('/foo', function() { return 'foo'; });
    
    $response = $router->handle(Request::create('/test'));
    $this->assertEquals('before1before2testafter1after2', $response);
    
    $response = $router->handle(Request::create('/foo'));
    $this->assertEquals('before1before2fooafter1after2', $response);
  }
  
  public function testRouterFiltersShouldBeExcecutedArroundRouteFilters() {
    $router = new Router();
    $router->before(function() { return 'before_router'; })
           ->after(function() { return 'after_router'; });
    
    $router->get('/test', function() { return 'test'; })
           ->before(function() { return 'before_route'; })
           ->after(function() { return 'after_route'; });
    
    $response = $router->handle(Request::create('/test'));
    $this->assertEquals('before_routerbefore_routetestafter_routeafter_router', $response);
  }
  
}

?>