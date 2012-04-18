<?php

namespace yoshi;

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
  
  public function run(Request $request = null) {
    if ($request === null) {
      $request = Request::createFromGlobals();
    }
    
    $response = $this->router->handle($request);
    $response->send();
  }
  
}

?>