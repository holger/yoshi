<?php

namespace yoshi;

class Request {
  
  private $uri;
  private $uri_path;
  private $method;
  private $script_name;

  public static function create($uri, $script_name = null, $method = 'GET') {
    $request = new Request();
    $request->method = $method;
    $url_parts = parse_url($uri);
    $request->uri = $uri;
    $request->uri_path = $url_parts['path'];
    $request->script_name = $script_name;
    return $request;
  }
  
  public static function createFromGlobals() {
    $uri = $_SERVER['REQUEST_URI'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $method = $_SERVER['REQUEST_METHOD'];
    return self::create($uri, $script_name, $method);
  }
  
  public function rootUri() {
    if ($this->script_name == null) {
      return '';
    }
    $base_uri = strpos($this->uri, $this->script_name) === 0 ? $this->script_name : str_replace('\\', '/', dirname($this->script_name));
    return rtrim($base_uri, '/');
  }
  
  public function uri() {
    return $this->uri;
  }
  
  public function uriPath() {
    return $this->uri_path;
  }
  
  public function method() {
    return $this->method;
  }
  
  public function __toString() {
    return sprintf('%s %s', $this->method, $this->uri);
  }

}

?>