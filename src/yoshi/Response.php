<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

class Response {
  
  private $status_code;
  private $reason_phrase;
  private $contents;
  private $headers = array();
  protected $has_been_sent = false;
  
  private static $reason_phrases = array(
    '404' => 'Not Found',
    '405' => 'Method Not Allowed'
  );
  
  public function contents($contents = null) {
    if ($contents !== null) {
      $this->contents = $contents;
    }
    return $this->contents;
  }

  public function appendContents($contents) {
      $this->contents .= $contents;
  }
  
  public function status($status_code = null, $reason_phrase = null) {
    if ($status_code !== null) {
      $this->status_code = $status_code; 
      if ($reason_phrase === null) {
        $reason_phrase = self::$reason_phrases[$status_code];
      }
      $this->reason_phrase = $reason_phrase;
    }
    
    $status = $this->status_code;
    if (!empty($this->reason_phrase)) {
      $status .= ' ' . $this->reason_phrase;
    }
    return $status;
  }

  public function header($header) {
    $this->headers[] = $header;
  }

  public function headers() {
    return $this->headers;
  }
  
  public function send() {
    if (!empty($this->status)) {
      header(sprintf('HTTP/1.0 %s', $this->status()));
    }
    foreach ($this->headers as $header) {
      header($header);
    }
    echo $this->contents();
    $this->has_been_sent = true;
  }

  public function sendRedirect($location) {
    $this->header('Location: ' . $location);
    $this->send();
  }

  public function hasBeenSent() {
    return $this->has_been_sent;
  }
  
}

?>