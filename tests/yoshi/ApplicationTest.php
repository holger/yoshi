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
    $result = $app->run(Request::create($uri, $method));
    $this->assertEquals($expected, $result);
  }
    
}