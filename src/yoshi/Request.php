<?php

namespace yoshi;

class Request {
  
  private $request_uri;
  private $request_uri_path;
  private $request_method;

  public static function create($uri, $method = 'GET') {
    $request = new Request();
    $request->setRequestUri($uri);
    $request->request_method = $method;
    return $request;
  }
  
  public static function createFromGlobals() {
    $request = new Request();
    $request->setRequestUri($_SERVER['REQUEST_URI']);
    $request->request_method = $_SERVER['REQUEST_METHOD'];
    return $request;
  }
  
  private function setRequestUri($uri) {
    $url_parts = parse_url($uri);
    $this->request_uri = $uri;
    $this->request_uri_path = $url_parts['path'];
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

}

?>