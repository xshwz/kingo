<?php

class TestCase extends PHPUnit_Framework_TestCase {
  /**
   * @param string $fixture
   * @param bool $assoc
   * @return array
   */
  protected function fixture($fixture, $assoc=false) {
    return json_decode(file_get_contents(
      __dir__ . '/fixtures/' . $fixture . '.json'), $assoc);
  }

  /**
   * @param object $needle
   * @param array $haystack
   */
  protected function assertContainsSome($needle, $haystack) {
    foreach ($needle as $index => $item) {
      foreach ($item as $key => $value) {
        $this->assertEquals($haystack[$index][$key], $value);
      }
    }
  }
}
