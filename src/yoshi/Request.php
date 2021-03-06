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

    if(isset($url_parts['query'])) {
        parse_str($url_parts['query'], $query);
        if (isset($query['_method'])) {
          $request->method = strtoupper($query['_method']);
        }
    }
    if (isset($_POST['_method'])) {
      $request->method = strtoupper($_POST['_method']);
    }

    return $request;
  }
  
  public static function createFromGlobals() {
    $https = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on";
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $script_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
    $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
    //$_SERVER['QUERY_STRING']
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

  public function path() {
    return substr($this->uriPath(), strlen($this->baseUri()));
  }
  
  public function method() {
    return $this->method;
  }
  
  public function __toString() {
    return sprintf('%s %s', $this->method, $this->uri);
  }

}

?>