<?php

namespace yoshi;

class Response {
  
  private $status;
  private $contents;
  
  public function setContents($contents) {
    $this->contents = $contents;
  }
  
  public function contents() {
    return $this->contents;
  }
  
  public function setStatus($status) {
    $this->status = $status;
  }
  
  public function status() {
    return $this->status;
  }
  
  public function send() {
    if (!empty($this->status)) {
      header(sprintf('HTTP/1.0 %s', $this->status));
    }
    echo $this->contents();
  }
  
}

?>