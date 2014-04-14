<?php

class FunctionsTest extends TestCase {
  protected $students = array();

  public function __construct() {
    $this->config  = $this->fixture('config');
    $this->kingo   = new Kingo($this->config->url, $this->config->xnxq);
    $this->courses = $this->fixture('courses');

    foreach ($this->fixture('students') as $student) {
      $student->kingo = new Kingo($this->config->url, $this->config->xnxq);
      $student->kingo->login($student->sid, $student->pwd);
      $this->students[] = $student;
    }
  }

  public function testGetArchives() {
    foreach ($this->students as $student) {
      $archives = $student->kingo->getArchives();

      foreach ($student->archives as $key => $value) {
        if (isset($archives[$key])) {
          $this->assertEquals($archives[$key], $value);
        }
      }
    }
  }

  public function testGetScores() {
    foreach ($this->students as $student) {
      foreach (array(0, 1) as $type) {
        $scores = $student->kingo->getScores($type);

        foreach ($student->scores[$type] as $semester => $grades) {
          foreach ($grades as $index => $grade) {
            foreach ($grade as $key => $value) {
              $this->assertEquals(
                $scores['tbody'][$semester][$index][array_search(
                  $key, $scores['thead'])], $value);
            }
          }
        }
      }
    }
  }

  public function testGetRegistrations() {
    foreach ($this->students as $student) {
      $registrations = $student->kingo->getRegistrations();

      $this->assertEquals(
        $registrations['tbody'][0][1], $student->archives->{'院(系)/部'});
      $this->assertEquals(
        $registrations['tbody'][0][3], $student->archives->{'行政班级'});
    }
  }

  public function testGetPersonalCourses() {
    foreach ($this->students as $student) {
      $this->assertContainsSome(
        $student->courses, $student->kingo->getPersonalCourses());
    }
  }

  public function testGetClassCourses() {
    foreach ($this->courses->class as $className => $courses) {
      $this->assertContainsSome(
        $courses, $this->kingo->getClassCourses($className));
    }
  }
}
