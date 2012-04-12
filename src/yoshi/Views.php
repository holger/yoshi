<?php

namespace yoshi;

use Exception;

class Views {

  private $config = array(
    'TEMPLATES_PATH' => 'app/views/',
    'LAYOUTS_PATH' => 'layouts/', 
    'DEFAULT_LAYOUT' => 'application.php'
  );
  
  private $application_helpers = array();
  
  public function __construct($config) {
    $this->config = array_merge($this->config, $config);
  }
  
  public function create($template, $layout = null) {
    if ($layout === null) {
      $layout = $this->config['DEFAULT_LAYOUT'];
    }
   
    $template = $this->config['TEMPLATES_PATH'] . $template;
    $layout = $this->config['TEMPLATES_PATH'] . $this->config['LAYOUTS_PATH'] . $layout;
    
    $view = new View($template, $layout);
    foreach ($this->application_helpers as $name => $callback) {
      $view->helper($name, $callback);
    }
    return $view;
  }

  public function render($template, $data = array()) {
    $view = $this->create($template);
    foreach ($data as $name => $value) {
      $view->bind($name, $value);
    }
    return $view->render();
  }
  
  public function helper($name, $callback) {
    if (method_exists('yoshi\View', $name)) {
      throw new Exception(sprintf('Can\'t use helper with the name %s, since this name is already used as a method name inside of the View class.', $name));
    }
    $this->application_helpers[$name] = $callback;
    return $this;
  }
  
}

?>