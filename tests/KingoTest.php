<?php

class KingoTest extends TestCase {
  public function __construct() {
    $this->config   = $this->fixture('config');
    $this->students = $this->fixture('students');
  }

  public function testLogin() {
    foreach ($this->students as $student) {
      $kingo = new Kingo($this->config->url, $this->config->xnxq);
      $this->assertTrue($kingo->login($student->sid, $student->pwd));
    }

    foreach ($this->students as $student) {
      $kingo = new Kingo($this->config->url, $this->config->xnxq);
      $this->assertFalse($kingo->login($student->sid, 'wrong password'));
    }
  }
}
