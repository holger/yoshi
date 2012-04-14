<?php

namespace yoshi;

class UrlHelperTest extends \PHPUnit_Framework_TestCase
{
  
  public function testLinkShouldAddWebrootFromRequest() {
    $helper = new UrlHelper();
    $request = Request::create('/webroot/test', '/webroot');
    
    $this->assertEquals('/webroot/page', $helper->link('/page', $request));
  }
  
  public function testLinkShouldCreateRequestFormGlobalsWhenNoRequestIsGiven() {
    $helper = new UrlHelper();
    $_SERVER['REQUEST_URI'] = '/webroot/test';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['SCRIPT_NAME'] = '/webroot';
    
    $this->assertEquals('/webroot/page', $helper->link('/page'));
  }

}

?>