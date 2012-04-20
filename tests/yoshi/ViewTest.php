<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

use Exception;

class ViewTest extends \PHPUnit_Framework_TestCase
{
  
  public function testRenderShouldReturnTemplateContent() {
    $view = new View('tests/resources/views/test.php');
    $this->assertEquals('Test Content', $view->render());
  }
  
  public function testRenderWithMissingLayoutsDirectoryShouldReturnPlainTemplate() {
    $view = new View('tests/resources/views/test.php');
    $this->assertEquals('Test Content', $view->render());
  }
  
  public function testRenderWithMissingTemplateShouldThrowAnException() {
    try {
      $view = new View('unknown_file.php');
      $view->render();
    } catch (Exception $e) {
      return;
    }  
    $this->fail('Template file could not be found, but no exception was thrown.');
  }
  
  public function testBoundVariablesShouldBeAccessibleInViews() {
    $view = new View('tests/resources/views/variables.php');
    $content = $view->bind('variable', 'Content')->render();

    $this->assertEquals('Test Content', $content);
  }
  
  public function testRegisteredHelpersShouldBeAccessibleInViews() {
    $view = new View('tests/resources/views/helpers.php');
    $content = $view->helper('function', function() { return 'Content'; })->render();

    $this->assertEquals('Test Content', $content);
  }
  
  public function testShouldNotBeAbleToRegisterAHelpernameWichIsAlreadyUsedAsMethodnameInView() {
    try {
      $view = new View('tests/resources/views/test.php');
      $view->helper('helper', function() {});
    } catch (Exception $e) {
      return;
    }
    
    $this->fail('Should not be able to register a helper with a name which is already used as method name inside of View class');
  }
    
}