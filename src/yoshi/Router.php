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

class Router {
  
  private $routes = array();
  private $before_filters = array();
  private $after_filters = array();
  
  public function get($path, $callback, $callback_method = null) {
    return $this->route('GET', $path, $callback, $callback_method);
  }
  
  public function post($path, $callback, $callback_method = null) {
    return $this->route('POST', $path, $callback, $callback_method);
  }
  
  public function put($path, $callback, $callback_method = null) {
    return $this->route('PUT', $path, $callback, $callback_method);
  }
  
  public function delete($path, $callback, $callback_method = null) {
    return $this->route('DELETE', $path, $callback, $callback_method);
  }
  
  public function head($path, $callback, $callback_method = null) {
    return $this->route('HEAD', $path, $callback, $callback_method);
  }
  
  public function options($path, $callback, $callback_method = null) {
    return $this->route('OPTIONS', $path, $callback, $callback_method);
  }
  
  private function route($method, $path, $callback, $callback_method) {
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
    $matches_without_http_method = false;
    $allowed_methods = array();
    
    foreach ($this->routes as $route) {
      if ($route->matches($request)) {
        return $this->execute($route, $request);
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
    return '';
  }
  
  private function execute(Route $route, Request $request) {
    $result = '';
    
    foreach ($this->before_filters as $filter) {
      $result .= call_user_func($filter);
    }
    
    $result .= $route->execute($request);
    
    foreach ($this->after_filters as $filter) {
      $result .= call_user_func($filter);
    }
    
    return $result;
  }
  
  public function before($callback, $callback_method = null) {
    if ($callback_method !== null) {
      $callback = array($callback, $callback_method);
    }
    $this->before_filters[] = $callback;
    return $this;
  }
  
  public function after($callback, $callback_method = null) {
    if ($callback_method !== null) {
      $callback = array($callback, $callback_method);
    }
    $this->after_filters[] = $callback;
    return $this;
  }
  
}

?>