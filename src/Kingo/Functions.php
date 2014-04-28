<?php

class Kingo_Functions {
  /**
   * @var array
   */
  private static $weeksDict = array(
    '一' => 1,
    '二' => 2,
    '三' => 3,
    '四' => 4,
    '五' => 5,
    '六' => 6,
    '日' => 7,
  );

  /**
   * @param Kingo $kingo
   */
  public function __construct($kingo) {
    $this->kingo = $kingo;
  }

  /**
   * @param string $html
   * @return DOMDocument
   */
  protected function DOM($html) {
    $dom = new DOMDocument;
    @$dom->loadHTML($this->htmlFinishing($html));
    return $dom;
  }

  /**
   * @param string $html
   * @return string
   */
  protected function htmlFinishing($html) {
    $html = str_replace('<br>', '', $html);
    $html = str_replace('&nbsp;', '', $html);
    $html = '
      <meta
        http-equiv="content-type"
        content="text/html; charset=utf-8">' . $html;
    return $html;
  }

  /**
   * @param DOMElement|DOMNode $table
   * @param array $range
   * @return array
   */
  protected function parseTable($table, $range=null) {
    $array = array();

    foreach ($table->getElementsByTagName('tr') as $row => $tr) {
      if ($range) {
        foreach ($range as $i) {
          $array[$row][] = $tr->getElementsByTagName('td')->item($i)->textContent;
        }
      } else {
        foreach ($tr->getElementsByTagName('td') as $td) {
          $array[$row][] = $td->textContent;
        }
      }
    }

    return $array;
  }

  /**
   * @param array $courses
   * @param array $indexs [课程, 学分, 总学时, 考核方式, 教师, 周次, 节次, 地点]
   * @return array
   */
  protected function parseCourses($courses, $indexs) {
    return array_map(function ($course) use ($indexs) {
      preg_match('/(.*)\[(\d+)-(\d+)/', $course[$indexs[6]], $lesson);

      return array(
        'name'     => preg_replace('/\[.*\]/', '', $course[$indexs[0]]),
        'teacher'  => preg_replace('/\[.*\]/', '', $course[$indexs[4]]),
        'type'     => $course[$indexs[3]],
        'location' => $course[$indexs[7]],
        'credit'   => $course[$indexs[1]],
        'hours'    => $course[$indexs[2]],
        'week'     => explode('-', $course[$indexs[5]]),
        'wday'     => self::$weeksDict[$lesson[1]],
        'lesson'   => array((int)$lesson[2], (int)$lesson[3]),
      );
    }, $courses);
  }
}
