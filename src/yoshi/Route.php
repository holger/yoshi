<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

class Route {
  
  private $method;
  private $path;
  private $compiled_path;
  private $compiled_path_without_http_method;
  private $callback;
  
  public function __construct($method, $path, $callback) {
    $path_pattern = str_replace('/', '\/', $path);
    $path_pattern = preg_replace('/[{][^}]*[}]/', '([^\/]+)', $path_pattern);
    
    $this->compiled_path = '/^' . $method . '#' . $path_pattern . '$/i';
    $this->compiled_path_without_http_method = '/^' . $path_pattern . '$/i';
    $this->path = $path;
    $this->method = $method;
    $this->callback = $callback;
  }

  public function execute(Request $request) {
    if ($this->matches($request, true, $matches)) {
      array_shift($matches);
      return call_user_func_array($this->callback, $matches);
    }
  }

  public function matches(Request $request, $include_http_method = true, &$matches = null) {
    $path = str_replace($request->rootUri(), '', $request->uriPath());
    $method = $request->method();

    if ($include_http_method) {
      preg_match($this->compiled_path, $method . '#' . $path, $matches);
    } else {
      preg_match($this->compiled_path_without_http_method, $path, $matches);
    }
    return count($matches) > 0;
  }

  public function matchesWithoutHttpMethod(Request $request) {
    return $this->matches($request, false);
  }
  
  public function method() {
    return $this->method;
  }

}

?>