<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
  
  public function testRoutesShouldCallAssociatedCallbacks() {
    $app = new Application();

    $app->get('/test', function(Response $response) { $response->contents('GET /test'); });
    $app->post('/test', function(Response $response) { $response->contents('POST /test'); });
    $app->put('/test', function(Response $response) { $response->contents('PUT /test'); });
    $app->delete('/test', function(Response $response) { $response->contents('DELETE /test'); });
    $app->head('/test', function(Response $response) { $response->contents('HEAD /test'); });
    $app->options('/test', function(Response $response) { $response->contents('OPTIONS /test'); });
    
    $this->assertRoute('GET /test', $app, '/test');
    $this->assertRoute('POST /test', $app, '/test', 'post');
    $this->assertRoute('PUT /test', $app, '/test', 'put');
    $this->assertRoute('DELETE /test', $app, '/test', 'delete');
    $this->assertRoute('HEAD /test', $app, '/test', 'head');
    $this->assertRoute('OPTIONS /test', $app, '/test', 'options');
  }
  
  public function testAddRouteMethodShouldReturnAnInstanceOfRoute() {
    $app = new Application();

    $this->assertInstanceOf('yoshi\Route', $app->get('/test', function(Response $response) { $response->contents('GET /test'); }));
    $this->assertInstanceOf('yoshi\Route', $app->post('/test', function(Response $response) { $response->contents('POST /test'); }));
    $this->assertInstanceOf('yoshi\Route', $app->put('/test', function(Response $response) { $response->contents('PUT /test'); }));
    $this->assertInstanceOf('yoshi\Route', $app->delete('/test', function(Response $response) { $response->contents('DELETE /test'); }));
    $this->assertInstanceOf('yoshi\Route', $app->head('/test', function(Response $response) { $response->contents('HEAD /test'); }));
    $this->assertInstanceOf('yoshi\Route', $app->options('/test', function(Response $response) { $response->contents('OPTIONS /test'); }));
  }
  
  private function assertRoute($expected, $app, $uri, $method = 'get') {
    $response = new ResponseMock();
    $app->run(Request::create(false, 'localhost', $uri, '', $method), $response);
    $this->assertEquals($expected, $response->contents());
  }
  
  public function testRunShouldSetAHttpError404ForUnknownRoutes() {
    $app = new Application();
    $app->get('/test', function() {});
    
    $response = new ResponseMock();
    $app->run(Request::create(false, 'localhost', '/unknown-route'), $response);
    
    $this->assertEquals('404 Not Found', $response->status());
  }
  
  public function testRunShouldSetAHttpError405ForRequestWithUnallowedMethod() {
    $app = new Application();
    $app->get('/test', function() {});
    $app->put('/test', function() {});

    $response = new ResponseMock();
    $app->run(Request::create(false, 'localhost', '/test', '', 'POST'), $response);
    
    $this->assertEquals('405 Method Not Allowed', $response->status());
    $this->assertContains('Allow: GET, PUT', $response->headers(), 'No header \'Allow: GET, PUT\' in ' . print_r($response->headers(), true));
  }
  
  public function testErrorShouldAssociateCallbackForHttpErrors() {
    $app = new Application();
    $app->error(function() { return 'Error callback'; });
    
    $response = new ResponseMock();
    $app->run(Request::create(false, 'localhost', '/unknown-route'), $response);
    
    $this->assertEquals('404 Not Found', $response->status());
    $this->assertEquals('Error callback', $response->contents());
  }
  
  public function testBeforeApplicationFiltersShouldBeCalledBeforeRouteExecution() {
    $app = new Application();
    $app->get('/test', function(Response $response) { $response->appendContents('route')  ; });
    $app->before(function(Response $response) { $response->appendContents('before1'); });
    $app->before(function(Response $response) { $response->appendContents('before2'); });

    $response = new ResponseMock();
    $app->run(Request::create(false, 'localhost', '/test'), $response);

    $this->assertEquals('before1before2route', $response->contents());
  }

  public function testAfterApplicationFiltersShouldBeCalledAfterRouteExecution() {
    $app = new Application();
    $app->get('/test', function(Response $response) { $response->appendContents('route'); });
    $app->after(function(Response $response) { $response->appendContents('after1'); });
    $app->after(function(Response $response) { $response->appendContents('after2'); });

    $response = new ResponseMock();
    $app->run(Request::create(false, 'localhost', '/test'), $response);

    $this->assertEquals('routeafter1after2', $response->contents());
  }

}

?>