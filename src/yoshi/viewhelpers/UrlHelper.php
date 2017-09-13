<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi\viewhelpers;

use yoshi\Request;

class UrlHelper {
  
  public function link($path, Request $request = null) {
    if ($request === null) {
      $request = Request::createFromGlobals();
    }
    return $request->baseUri() . '/' . ltrim($path, '/');
  }


  public function absoluteLink($path, Request $request = null) {
    if ($request === null) {
      $request = Request::createFromGlobals();
    }
    return $request->rootUri() . '/' . ltrim($path, '/');
  }
  
}

?>