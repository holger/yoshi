<?php

namespace yoshi;

class UrlHelper {
  
  public function link($path, Request $request = null) {
    if ($request === null) {
      $request = Request::createFromGlobals();
    }
    return $request->rootUri() . '/' . ltrim($path, '/');
  }
  
}

?>