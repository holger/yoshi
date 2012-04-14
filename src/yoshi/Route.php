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

  public function execute(Request $request) {
    if (count($matches = $this->_matchesRequest($request)) > 0) {
      array_shift($matches);
      return call_user_func_array($this->callback, $matches);
    }
  }
  
  public function matches($request_or_path) {
    if ($request_or_path instanceof Request) {
      return $this->matchesRequest($request_or_path);
    }
    
    return $this->matchesPath($request_or_path);
  }

  private function matchesPath($path) {
    return preg_match($this->compiled_path, $this->method . '#' . $path) > 0;
  }

  private function matchesRequest(Request $request) {
    return count($this->_matchesRequest($request)) > 0;
  }

  private function _matchesRequest(Request $request) {
    $path = str_replace($request->getRootUri(), '', $request->getRequestUriPath());
    $method = $request->getRequestMethod();

    preg_match($this->compiled_path, $method . '#' . $path, $matches);
    return $matches;
  }

}

?>