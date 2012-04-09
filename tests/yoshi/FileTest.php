<?php

namespace yoshi;

class FileTest extends \PHPUnit_Framework_TestCase
{
  private $file_name = 'tests/resources/file.ser';
  
  public function testSavedDataShouldBeReloadable() {
    $write_file = new File($this->file_name);
    $write_file->save('Test Content');
    
    $read_file = new File($this->file_name);
    $content = $read_file->load();
    
    $this->assertEquals('Test Content', $content);
  }
  
  public function tearDown() {
    unlink($this->file_name);
  }
    
}

?>