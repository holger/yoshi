<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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