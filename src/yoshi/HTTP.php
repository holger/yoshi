<?php

namespace yoshi;

class HTTP {
  
  public function get($uri, $data = array()) {
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        $uri .= (strpos($uri, '?') === false) ? '?' : '&'; 
        $uri .= urlencode($key) . '=' . urlencode($value);
      }
    }

    if ($curl = curl_init($uri)) {
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      return curl_exec($curl);
    }
  }

  public function post($uri, $data) {
    if ($curl = curl_init($uri)) {
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl,CURLOPT_POST,true);
      curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
      return curl_exec($curl);
    }  
  }

  public function redirect($uri) {
    header('Location: ' . $uri);
  }

}

?>