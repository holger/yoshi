<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

use Exception;
use yoshi\viewhelpers\RenderPartialHelper;
use yoshi\viewhelpers\UrlHelper;

class Views {

  private $config = array(
    'TEMPLATES_PATH' => 'views/',
    'LAYOUTS_PATH' => 'layouts/', 
    'DEFAULT_LAYOUT' => 'application.php'
  );

  private $application_variables = array();
  private $application_helpers = array();
  
  public function __construct($config = array()) {
    $this->config = array_merge($this->config, $config);
    $this->addDefaultHelpers();
  }
  
  public function create($template, $layout = 'DEFAULT_LAYOUT') {
    if ($layout == 'DEFAULT_LAYOUT') {
      $layout = $this->config['DEFAULT_LAYOUT'];
    }
    if ($layout !== null) {
      $layout = $this->config['TEMPLATES_PATH'] . $this->config['LAYOUTS_PATH'] . $layout;
    }

    $template = $this->config['TEMPLATES_PATH'] . $template;
    
    $view = new View($template, $layout);
    $view->bind($this->application_variables);
    foreach ($this->application_helpers as $name => $callback) {
      $view->helper($name, $callback);
    }
    return $view;
  }

  public function render($template, $data = array()) {
    return $this->create($template)->bind($data)->render();
  }

  public function bind($key, $value) {
    $this->application_variables[$key] = $value;
    return $this;
  }
  
  public function helper($name, $callback, $callback_method = null) {
    if (method_exists('yoshi\View', $name)) {
      throw new Exception(sprintf('Can\'t use helper with the name %s, since this name is already used as a method name inside of the View class.', $name));
    }
    if ($callback_method !== null) {
      $callback = array($callback, $callback_method);
    }
    $this->application_helpers[$name] = $callback;
    return $this;
  }

  private function addDefaultHelpers() {
    $this->helper('link', array(new UrlHelper(), 'link'));
    $this->helper('absoluteLink', array(new UrlHelper(), 'absoluteLink'));
    $this->helper('renderPartial', array(new RenderPartialHelper($this), 'renderPartial'));
  }
  
}

?>