<?php

/**
 * @param int $type
 */
class Kingo_Functions_GetScores extends Kingo_Functions {
  public function __invoke($args) {
    $dom = $this->DOM($this->kingo->POST('xscj/stu_myscore_rpt.aspx', array(
      'SJ'       => $args[0],
      'SelXNXQ'  => 0,
      'zfx_flag' => 0,
      'zxf'      => 0,
    )));

    if ($args[0]) {
      return $this->parseEffectiveScore($dom);
    } else {
      return $this->parseOriginalScore($dom);
    }
  }

  /**
   * @param DOMDocument $dom
   * @return array
   */
  public function parseEffectiveScore($dom) {
    $semester = '学期学年';
    $scores = array(
      'thead' => array(
        '课程/环节', '学分', '类别', '课程类别', '考核方式', '修读性质',
        '成绩', '取得学分', '绩点', '学分绩点', '备注',
      ),
    );

    foreach ($this->parseTable($dom->getElementById('ID_Table')) as $row) {
      if ($row[0]) {
        $semester = $row[0];
      }

      array_shift($row);
      $row[0] = preg_replace('/\[.*\]/', '', $row[0]);
      $scores['tbody'][$semester][] = $row;
    }

    return $scores;
  }

  /**
   * @param DOMDocument $dom
   * @return array
   */
  public function parseOriginalScore($dom) {
    $scores = array(
      'thead' => array(
        '课程/环节', '学分', '类别', '课程类别', '考核方式', '修读性质',
        '平时', '中考', '末考', '技能', '综合', '备注',
      ),
    );

    $tables = $dom->getElementsByTagName('table');

    for ($i = 1; $i < $tables->length; $i += 3) {
      $semester = substr(
        $tables->item($i)->getElementsByTagName('td')->item(0)->textContent, 15);

      foreach ($this->parseTable($tables->item($i + 2)) as $row) {
        array_shift($row);
        $row[0] = preg_replace('/\[.*\]/', '', $row[0]);
        $scores['tbody'][$semester][] = $row;
      }
    }

    return $scores;
  }
}
