<?php

namespace yoshi;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
  
  public function testRoutesShouldCallAssociatedCallbacks() {
    $app = new Application();

    $app->get('/test', function() { return 'GET /test'; });
    $app->post('/test', function() { return 'POST /test'; });
    $app->put('/test', function() { return 'PUT /test'; });
    $app->delete('/test', function() { return 'DELETE /test'; });
    $app->head('/test', function() { return 'HEAD /test'; });
    $app->options('/test', function() { return 'OPTIONS /test'; });
    
    $this->assertRoute('GET /test', $app, '/test');
    $this->assertRoute('POST /test', $app, '/test', 'post');
    $this->assertRoute('PUT /test', $app, '/test', 'put');
    $this->assertRoute('DELETE /test', $app, '/test', 'delete');
    $this->assertRoute('HEAD /test', $app, '/test', 'head');
    $this->assertRoute('OPTIONS /test', $app, '/test', 'options');
  }
  
  private function assertRoute($expected, $app, $uri, $method = 'get') {
    $response = new ResponseMock();
    $app->run(Request::create($uri, '', $method), $response);
    $this->assertEquals($expected, $response->contents());
  }
  
  public function testRunShouldSetAHttpError404ForUnknownRoutes() {
    $app = new Application();
    $app->get('/test', function() {});
    
    $response = new ResponseMock();
    $app->run(Request::create('/unknown-route'), $response);
    
    $this->assertEquals('404 Not Found', $response->status());
  }
  
  public function testRunShouldSetAHttpError405ForRequestWithUnallowedMethod() {
    $app = new Application();
    $app->get('/test', function() {});
    $app->put('/test', function() {});

    $response = new ResponseMock();
    $app->run(Request::create('/test', '', 'POST'), $response);
    
    $this->assertEquals('405 Method Not Allowed', $response->status());
    $this->assertContains('Allow: GET, PUT', $response->headers(), 'No header \'Allow: GET, PUT\' in ' . print_r($response->headers(), true));
  }
    
}

class ResponseMock extends Response {
  
  public function send() {
  }
  
}

?>