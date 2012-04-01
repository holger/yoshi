<?php

namespace yoshi;

class File {
  
  private $filename;
  
  public function __construct($filename) {
    $this->filename = $filename;
  }
  
  public function save($data) {
    file_put_contents($this->filename, serialize($data));
  }

  public function load() {
    return unserialize(@file_get_contents($this->filename));
  }
  
}

?>