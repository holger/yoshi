<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi\viewhelpers;

use yoshi\Request;

class UrlHelperTest extends \PHPUnit_Framework_TestCase
{

  public function testLinkShouldAddRelativeBaseUriFromRequest() {
    $helper = new UrlHelper();
    $request = Request::create(false, 'localhost', '/webroot/test', '/webroot');
    
    $this->assertEquals('/webroot/page', $helper->link('/page', $request));
  }
  
  public function testLinkShouldCreateRequestFormGlobalsWhenNoRequestIsGiven() {
    $helper = new UrlHelper();
    $_SERVER['REQUEST_URI'] = '/webroot/test';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['SCRIPT_NAME'] = '/webroot';
    
    $this->assertEquals('/webroot/page', $helper->link('/page'));
  }

  public function testAbsoluteLinkShouldAddRootUriFromRequest() {
    $helper = new UrlHelper();

    $request = Request::create(false, 'localhost', '/webroot/test', '/webroot');
    $this->assertEquals('http://localhost/webroot/page', $helper->absoluteLink('/page', $request));

    $request = Request::create(true, 'localhost', '/webroot/test', '/webroot');
    $this->assertEquals('https://localhost/webroot/page', $helper->absoluteLink('/page', $request));
  }

  public function testAbsoluteLinkShouldCreateRequestFormGlobalsWhenNoRequestIsGiven() {
    $helper = new UrlHelper();
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['HTTP_HOST'] = 'localhost';
    $_SERVER['REQUEST_URI'] = '/webroot/test';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['SCRIPT_NAME'] = '/webroot';

    $this->assertEquals('https://localhost/webroot/page', $helper->absoluteLink('/page'));
  }

}

?>