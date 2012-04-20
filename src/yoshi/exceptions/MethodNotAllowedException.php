<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi\exceptions;

use \Exception;

class MethodNotAllowedException extends Exception {
  
  private $allowed_methods;
  
  public function __construct($allowed_methods = array(), $message = null) {
    $this->allowed_methods = $allowed_methods;
    parent::__construct($message);
  }
  
  public function allowedMethods() {
    return implode(', ', $this->allowed_methods);
  }
  
}

?>