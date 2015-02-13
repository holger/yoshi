<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

use Exception;

class View {
  
  protected $view;
  protected $layout;
  protected $variables = array();
  protected $helpers = array();
  protected $partials = array();
  protected $current_partial_key;
  
  public function __construct($view, $layout = null) {
    $this->view = $view;
    $this->layout = $layout;
  }
  
  public function bind($keyOrArray, $value = null) {
    if (is_array($keyOrArray)) {
      $this->variables = array_merge($this->variables, $keyOrArray);
    } else {
      $this->variables[$keyOrArray] = $value;
    }
    return $this;
  }
  
  public function helper($name, $callback, $callback_method = null) {
    if (method_exists($this, $name)) {
      throw new Exception(sprintf('Can\'t use helper with the name %s, since this name is already used as a method name inside of the View class.', $name));
    }
    if ($callback_method !== null) {
      $callback = array($callback, $callback_method);
    }
    $this->helpers[$name] = $callback;
    return $this;
  }
  
  public function __call($name, $arguments) {
    $helper = $this->helpers[$name];
    
    if ($helper != null) {
      $request = Request::createFromGlobals();
      array_push($arguments, $request);
      return call_user_func_array($helper, $arguments);
    }
  }

  public function content_for($key, $value = null) {
    if( ! isset($value) ) {
      $this->current_partial_key = $key; 
      ob_start();
    }
    else {
      $this->partials[$key] = $value;
    }
  }
  
  public function end_content_for() {
    if(is_null($this->current_partial_key)) {
      return;
    }

    $this->partials[$this->current_partial_key] .= ob_get_clean();
    $this->current_partial_key = null;  
  }
  
  public function render() {
    if (file_exists($this->view) === false) {
      throw new Exception(sprintf('Template file %s was not found.', $this->view));
    }
    
    extract($this->variables);
    
    ob_start();
    include $this->view;
    $content = ob_get_contents();
    ob_clean();
    
    if (file_exists($this->layout)) {
      extract($this->partials);
      include $this->layout;
      $site = ob_get_contents();
    } else {  
      $site = $content;
    }
    ob_end_clean();

    return $site;
  }
  
}

?>