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
  
  public function get($path, $callback) {
    $this->addRoute('GET', $path, $callback);
  }
  
  public function post($path, $callback) {
    $this->addRoute('POST', $path, $callback);
  }
  
  public function put($path, $callback) {
    $this->addRoute('PUT', $path, $callback);
  }
  
  public function delete($path, $callback) {
    $this->addRoute('DELETE', $path, $callback);
  }
  
  public function head($path, $callback) {
    $this->addRoute('HEAD', $path, $callback);
  }
  
  public function options($path, $callback) {
    $this->addRoute('OPTIONS', $path, $callback);
  }
  
  private function addRoute($method, $path, $callback) {
    $this->routes[] = new Route($method, $path, $callback);
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