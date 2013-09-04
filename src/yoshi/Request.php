<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

class Request {

  private $https;
  private $host;
  private $uri;
  private $uri_path;
  private $method;
  private $script_name;

  public static function create($https, $host, $uri, $script_name = null, $method = 'GET') {
    $request = new Request();
    $request->https = $https;
    $request->host = $host;
    $request->method = $method;
    $request->script_name = $script_name;

    $url_parts = parse_url($uri);
    $request->uri = $uri;
    $request->uri_path = $url_parts['path'];

    return $request;
  }
  
  public static function createFromGlobals() {
    $https = !empty($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on";
        $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $method = $_SERVER['REQUEST_METHOD'];
    return self::create($https, $host, $uri, $script_name, $method);
  }

  public function rootUri() {
      return 'http' . ($this->https ? 's' : '') . '://' . $this->host . $this->baseUri();
  }
  
  public function baseUri() {
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