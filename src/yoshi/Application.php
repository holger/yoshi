<?php

namespace yoshi;

class Application {
  
  private $routes = array();
  private $config = array(
    'BASE_PATH' => '' 
  );
  
  public function __construct($config = array()) {
    $this->config = array_merge($this->config, $config);
  }
  
  public function addRoute($path, $callback) {
    $compiled_path = str_replace('/', '\/', $path);
    $compiled_path = '/^' . preg_replace('/[{][^}]*[}]/', '([^\/]*)', $compiled_path) . '$/i';

    $this->routes[] = array(
      'path' => $path,
      'compiled_path' => $compiled_path,
      'callback' => $callback
    );
  }
  
  public function request() {
    $url_parts = parse_url($_SERVER['REQUEST_URI']);
    $path = str_replace($this->config['BASE_PATH'], '', $url_parts['path']);
    
    foreach ($this->routes as $route) {
      if (preg_match($route['compiled_path'], $path, $args)) {
        array_shift($args);
        return call_user_func_array($route['callback'], $args);
      }
    }
  }
  
}

?>