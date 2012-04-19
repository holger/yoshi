<?php

namespace yoshi;

class Request {
  
  private $request_uri;
  private $request_uri_path;
  private $request_method;
  private $script_name;

  public static function create($uri, $script_name = null, $method = 'GET') {
    $request = new Request();
    $request->request_method = $method;
    $request->setRequestUri($uri);
    $request->script_name = $script_name;
    return $request;
  }
  
  public static function createFromGlobals() {
    $request = new Request();
    $request->request_method = $_SERVER['REQUEST_METHOD'];
    $request->setRequestUri($_SERVER['REQUEST_URI']);
    $request->script_name = $_SERVER['SCRIPT_NAME'];
    return $request;
  }
  
  private function setRequestUri($uri) {
    $url_parts = parse_url($uri);
    $this->request_uri = $uri;
    $this->request_uri_path = $url_parts['path'];
  }
  
  public function getRootUri() {
    if ($this->script_name == null) {
      return '';
    }
    $base_uri = strpos($this->request_uri, $this->script_name) === 0 ? $this->script_name : str_replace('\\', '/', dirname($this->script_name));
    return rtrim($base_uri, '/');
  }
  
  public function getRequestUri() {
    return $this->request_uri;
  }
  
  public function getRequestUriPath() {
    return $this->request_uri_path;
  }
  
  public function getRequestMethod() {
    return $this->request_method;
  }
  
  public function __toString() {
    return sprintf('%s %s', $this->request_method, $this->request_uri);
  }

}

?>