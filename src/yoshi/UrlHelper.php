<?php

namespace yoshi;

class UrlHelper {
  
  private $routes = array();
  
  public function __construct($routes) {
    $this->routes = $routes;
  }
  
  public function link(Request $request, $name, $params = array()) {
    foreach ($this->routes as $route) {
      if ($route->matches($name)) {
        return $route->link($request, $params);
      }
    }
    return '';
  }
  
}

?>