<?php

namespace yoshi;

use yoshi\exceptions\NotFoundException;
use yoshi\exceptions\MethodNotAllowedException;

class Application {
  
  private $router;
  
  public function __construct() {
    $this->router = new Router();
  }
  
  public function get($path, $callback, $callback_method = null) {
    $this->router->get($path, $callback, $callback_method);
  }
  
  public function post($path, $callback, $callback_method = null) {
    $this->router->post($path, $callback, $callback_method);
  }
  
  public function put($path, $callback, $callback_method = null) {
    $this->router->put($path, $callback, $callback_method);
  }
  
  public function delete($path, $callback, $callback_method = null) {
    $this->router->delete($path, $callback, $callback_method);
  }
  
  public function head($path, $callback, $callback_method = null) {
    $this->router->head($path, $callback, $callback_method);
  }
  
  public function options($path, $callback, $callback_method = null) {
    $this->router->options($path, $callback, $callback_method);
  }
  
  public function run(Request $request = null, Response $response = null) {
    $request = $request == null ? Request::createFromGlobals() : $request;
    $response = $response == null ? new Response() : $response;
    $result;
    
    try {
      $response->setContents($this->router->handle($request));
    } catch (NotFoundException $e) {
      $response->setStatus(404);
    } catch (MethodNotAllowedException $e) {
      $response->setStatus(405);
      $response->header('Allow: ' . $e->allowedMethods());
    }
    
    $response->send();
  }
  
}

?>