<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

use yoshi\exceptions\NotFoundException;
use yoshi\exceptions\MethodNotAllowedException;

class Application {
  
  private $router;
  private $error_callback;
  
  public function __construct() {
    $this->router = new Router();
  }
  
  public function get($path, $callback, $callback_method = null) {
    return $this->router->get($path, $callback, $callback_method);
  }
  
  public function post($path, $callback, $callback_method = null) {
    return $this->router->post($path, $callback, $callback_method);
  }
  
  public function put($path, $callback, $callback_method = null) {
    return $this->router->put($path, $callback, $callback_method);
  }
  
  public function delete($path, $callback, $callback_method = null) {
    return $this->router->delete($path, $callback, $callback_method);
  }
  
  public function head($path, $callback, $callback_method = null) {
    return $this->router->head($path, $callback, $callback_method);
  }
  
  public function options($path, $callback, $callback_method = null) {
    return $this->router->options($path, $callback, $callback_method);
  }
  
  public function error($callback, $callback_method = null) {
    if ($callback_method !== null) {
      $callback = array($callback, $callback_method);
    }
    $this->error_callback = $callback;
  }
  
  public function before($callback, $callback_method = null) {
    $this->router->before($callback, $callback_method);
  }
  
  public function after($callback, $callback_method = null) {
    $this->router->after($callback, $callback_method);
  }
  
  public function run(Request $request = null, Response $response = null) {
    $request = $request == null ? Request::createFromGlobals() : $request;
    $response = $response == null ? new Response() : $response;
    $result;
    $error = false;
    
    try {
      $response->contents($this->router->handle($request));
    } catch (NotFoundException $e) {
      $response->status(404);
      $error = true;
    } catch (MethodNotAllowedException $e) {
      $response->status(405);
      $response->header('Allow: ' . $e->allowedMethods());
      $error = true;
    }
    
    if ($error && $this->error_callback !== null) {
      $contents = call_user_func($this->error_callback);
      $response->contents($contents);
    }
    
    $response->send();
  }
  
}

?>