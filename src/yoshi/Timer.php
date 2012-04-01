<?php

namespace yoshi;

class Timer {
  
  private $prefix;
  private $last_time;
  
  public function __construct($message) {
    $this->prefix = $message;
    $this->printMessage($message);
    $this->last_time = $this->getTime();
    $this->first_time = $this->last_time;
  }
  
  public function time($message) {
    $time = $this->getTime();
    $diff = $time - $this->last_time;
    $total_diff = $time - $this->first_time;
    $this->printMessage($message . ': ' . round($diff, 4) . ' / ' . round($total_diff, 4));
    $this->last_time = $time;
  }
  
  private function getTime() {
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    return $time;
  }
  
  private function printMessage($message) {
    echo '['. $this->prefix .'] ' . $message . '<br />';
  }
  
}

?>