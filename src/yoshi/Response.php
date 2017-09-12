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
    foreach ($this->headers as $header) {
      header($header);
    }
    if ($this->status_code !== null) {
      header(sprintf('HTTP/1.1 %s', $this->status()));
    }
    echo $this->contents();
    $this->has_been_sent = true;
  }

  public function sendRedirect($location) {
    $request = Request::createFromGlobals();
    $location = $request->baseUri() . '/' . ltrim($location, '/');
    $this->header('Location: ' . $location);
    $this->send();
  }

  public function hasBeenSent() {
    return $this->has_been_sent;
  }
  
}

?>