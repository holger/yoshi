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
  
  public function matches(Request $request, $base_path = '') {
    return count($this->_matches($request, $base_path)) > 0;
  }
  
  public function execute(Request $request, $base_path = '') {
    if (count($matches = $this->_matches($request, $base_path)) > 0) {
      array_shift($matches);
      return call_user_func_array($this->callback, $matches);
    }
  }
  
  private function _matches(Request $request, $base_path) {
    $path = str_replace($base_path, '', $request->getRequestUriPath());
    $method = $request->getRequestMethod();
  
    preg_match($this->compiled_path, $method . '#' . $path, $matches);
    return $matches;
  }

}

?>