<?php

namespace yoshi;

use yoshi\exceptions\NotFoundException;
use yoshi\exceptions\MethodNotAllowedException;

class Router {
  
  private $routes = array();
  
  public function get($path, $callback, $callback_method = null) {
    $this->addRoute('GET', $path, $callback, $callback_method);
  }
  
  public function post($path, $callback, $callback_method = null) {
    $this->addRoute('POST', $path, $callback, $callback_method);
  }
  
  public function put($path, $callback, $callback_method = null) {
    $this->addRoute('PUT', $path, $callback, $callback_method);
  }
  
  public function delete($path, $callback, $callback_method = null) {
    $this->addRoute('DELETE', $path, $callback, $callback_method);
  }
  
  public function head($path, $callback, $callback_method = null) {
    $this->addRoute('HEAD', $path, $callback, $callback_method);
  }
  
  public function options($path, $callback, $callback_method = null) {
    $this->addRoute('OPTIONS', $path, $callback, $callback_method);
  }
  
  private function addRoute($method, $path, $callback, $callback_method) {
    if ($callback_method !== null) {
      $callback = array($callback, $callback_method);
    }
    $route = new Route($method, $path, $callback);
    $this->routes[] = $route;
    return $route;
  }
  
  public function routes() {
    return $this->routes;
  }
  
  public function handle(Request $request) {
    $response = new Response();
    $matches_without_http_method = false;
    $allowed_methods = array();
    
    foreach ($this->routes as $route) {
      if ($route->matches($request)) {
        return $route->execute($request);
      }
      if ($route->matchesWithoutHttpMethod($request)) {
        $matches_without_http_method = true;
        $allowed_methods[] = $route->method();
      }
    }
    
    if ($matches_without_http_method) {
      throw new MethodNotAllowedException($allowed_methods);
    } else {
      throw new NotFoundException('Unable to find a route for ' . $request);
    }
    return $response;
  }
  
}

?>