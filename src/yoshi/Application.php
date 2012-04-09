<?php

namespace yoshi;

class Application {
  
  private $routes = array();
  private $config = array(
    'BASE_PATH' => ''
  );
  
  public function __construct($config = array()) {
    $this->config = array_merge($this->config, $config);
  }
  
  public function get($path, $callback, $callback_method = null) {
    return $this->addRoute('GET', $path, $callback, $callback_method);
  }
  
  public function post($path, $callback, $callback_method = null) {
    return $this->addRoute('POST', $path, $callback, $callback_method);
  }
  
  public function put($path, $callback, $callback_method = null) {
    return $this->addRoute('PUT', $path, $callback, $callback_method);
  }
  
  public function delete($path, $callback, $callback_method = null) {
    return $this->addRoute('DELETE', $path, $callback, $callback_method);
  }
  
  public function head($path, $callback, $callback_method = null) {
    return $this->addRoute('HEAD', $path, $callback, $callback_method);
  }
  
  public function options($path, $callback, $callback_method = null) {
    return $this->addRoute('OPTIONS', $path, $callback, $callback_method);
  }
  
  private function addRoute($method, $path, $callback, $callback_method) {
    if ($callback_method !== null) {
      $callback = array($callback, $callback_method);
    }
    $route = new Route($method, $path, $callback);
    $this->routes[] = $route;
    return $route;
  }
  
  public function run(Request $request = null) {
    if ($request === null) {
      $request = Request::createFromGlobals();
    }
    
    foreach ($this->routes as $route) {
      if ($route->matches($request, $this->config['BASE_PATH'])) {
        return $route->execute($request, $this->config['BASE_PATH']);
      }
    }
  }
  
}

?>