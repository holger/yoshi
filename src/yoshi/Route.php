<?php

namespace yoshi;

class Route {
  
  private $name;
  private $method;
  private $path;
  private $compiled_path;
  private $callback;
  
  public function __construct($method, $path, $callback) {
    $path_pattern = str_replace('/', '\/', $path);
    $path_pattern = preg_replace('/[{][^}]*[}]/', '([^\/]+)', $path_pattern);
    
    $this->compiled_path = '/^' . $method . '#' . $path_pattern . '$/i';
    $this->path = $path;
    $this->method = $method;
    $this->callback = $callback;
  }
  
  private function matchesPath($path) {
    return preg_match($this->compiled_path, $this->method . '#' . $path) > 0;
  }
  
  private function matchesName($name) {
    return $this->name == $name;
  }
  
  private function matchesRequest(Request $request) {
    return count($this->_matches($request)) > 0;
  }
  
  public function matches($search) {
    if ($search instanceof Request) {
      return $this->matchesRequest($search);
    }
    
    return $this->matchesName($search) or $this->matchesPath($search);
  }

  private function _matches(Request $request) {
    $path = str_replace($request->getRootUri(), '', $request->getRequestUriPath());
    $method = $request->getRequestMethod();

    preg_match($this->compiled_path, $method . '#' . $path, $matches);
    return $matches;
  }
  
  public function execute(Request $request) {
    if (count($matches = $this->_matches($request)) > 0) {
      array_shift($matches);
      return call_user_func_array($this->callback, $matches);
    }
  }
  
  public function link(Request $request, $params = array()) {
    if (!is_array($params)) {
      $params = array($params);
    }
    
    $path = preg_replace_callback('/[{][^}]*[}]/', function($matches) use (&$params) {
      return array_shift($params);
    }, $this->path);
    
    return $request->getRootUri() . $path;
  }
  
  public function named($name) {
    $this->name = $name;
  }

}

?>