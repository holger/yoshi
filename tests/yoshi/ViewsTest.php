<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

use Exception;

class ViewsTest extends \PHPUnit_Framework_TestCase
{
  
  private $views;
  
  public function setUp() {
    $this->views = new Views(array(
      'TEMPLATES_PATH' => 'tests/resources/views/'
    ));
  }
  
  public function testCreateShouldReturnViewInstance() {
    $view = $this->views->create('test.php');
    $this->assertTrue($view instanceof View);
  }
  
  public function testRenderShouldReturnTemplateContent() {
    $content = $this->views->render('test.php');
    $this->assertEquals('Test Content', $content);
  }
  
  public function testRegisteredApplicationHelpersShouldBeAccessibleInViews() {
    $this->views->helper('function', function() { return 'Content'; });
    $content = $this->views->render('helpers.php');

    $this->assertEquals('Test Content', $content);
  }
  
  public function testShouldNotBeAbleToRegisterAnApplicationHelpernameWichIsAlreadyUsedAsMethodnameInView() {
    try {
      $this->views->helper('helper', function() {});
    } catch (Exception $e) {
      return;
    }
    
    $this->fail('Should not be able to register a helper with a name which is already used as method name inside of View class');
  }
    
}