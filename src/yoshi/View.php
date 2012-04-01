<?php

namespace yoshi;

class View {
  
  protected $view;
  protected $layout;
  protected $variables = array();
  protected $helpers = array();
  
  public static function create($view, $layout = null) {
    return new static($view, $layout);
  }
  
  public function __construct($view, $layout = null) {
    $this->view = $view;
    $this->layout == null and $this->layout = 'views' . DS . 'layouts' . DS . 'application.php';
    $this->set('view', $view);
  }
  
  public function set($key, $value) {
    $this->variables[$key] = $value;
    return $this;
  }
  
  public function registerHelper($helper, $name) {
    $this->helpers[$name] = $helper;
    return $this;
  }
  
  public function __call($name, $arguments) {
    $helper = $this->helpers[$name];
    
    if ($helper != null) {
      return call_user_func(array($helper, $name), $arguments);
    }
  }
  
  public function render() {
    extract($this->variables);
    
    ob_start();
    include APP_PATH . 'views' . DS . $this->view . '.php';
    $content = ob_get_contents();
    ob_clean();
    
    include APP_PATH . $this->layout;
    $site = ob_get_contents();
    ob_end_clean();

    return $site;
  }
  
}

?>