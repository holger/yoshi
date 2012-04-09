<?php

namespace yoshi;

class Route {
  
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
  
  public function matches(Request $request) {
    return count($this->_matches($request)) > 0;
  }
  
  public function execute(Request $request) {
    if (count($matches = $this->_matches($request)) > 0) {
      array_shift($matches);
      return call_user_func_array($this->callback, $matches);
    }
  }
  
  private function _matches(Request $request) {
    $path = str_replace($request->getRootUri(), '', $request->getRequestUriPath());
    $method = $request->getRequestMethod();
  
    preg_match($this->compiled_path, $method . '#' . $path, $matches);
    return $matches;
  }

}

?>